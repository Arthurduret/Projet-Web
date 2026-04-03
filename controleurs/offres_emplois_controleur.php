<?php
require_once __DIR__ . '/../modeles/offres_emplois_modele.php';
require_once __DIR__ . '/../modeles/favoris_modele.php';
require_once __DIR__ . '/../helper/csrf.php';

class OffresControleur {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // -----------------------------------------------
    // Vérifie que l'utilisateur a le bon rôle
    // -----------------------------------------------
    private function verifierRole(array $rolesAutorises): void {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], $rolesAutorises)) {
            header('Location: /index.php?page=auth&action=connexion');
            exit;
        }
    }

    // -----------------------------------------------
    // LISTE OFFRES — accessible à tous (SFx7)
    // -----------------------------------------------
    public function index(): void {
        $modele = new OffresModele($this->pdo);

        $quoi   = trim($_GET['quoi'] ?? '');
        $ou     = trim($_GET['ou']   ?? '');
        $page   = max(1, (int)($_GET['p'] ?? 1));
        $limite = 10;
        $offset = ($page - 1) * $limite;

        $filtres = [
            'f_entreprise'       => trim($_GET['f_entreprise']       ?? ''),
            'f_titre'            => trim($_GET['f_titre']            ?? ''),
            'f_competence'       => trim($_GET['f_competence']       ?? ''),
            'f_salaire_min'      => $_GET['f_salaire_min']           ?? '',
            'f_salaire_max'      => $_GET['f_salaire_max']           ?? '',
            'f_date'             => $_GET['f_date']                  ?? '',
            'f_candidatures_min' => $_GET['f_candidatures_min']      ?? '',
            'f_tri'              => $_GET['f_tri']                   ?? '',
        ];

        $offres    = $modele->filtrerOffres($quoi, $ou, $filtres, $limite, $offset);
        $total     = $modele->compterOffresFiltrees($quoi, $ou, $filtres);
        $nb_offres = count($offres);
        $nb_pages  = ceil($total / $limite);

        // Récupère les favoris si étudiant connecté (SFx23)
        $favoris_ids = [];
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'etudiant') {
            $favorisModele = new FavorisModele($this->pdo);
            $favoris       = $favorisModele->getFavoris((int)$_SESSION['user']['id_utilisateur']);
            $favoris_ids   = array_column($favoris, 'id_offre');
        }

        $meta_title       = "Jobeo | Offres de stage";
        $meta_description = "Recherchez parmi nos offres de stage en région PACA. Filtrez par compétence, salaire, localisation et date.";
        $meta_keywords    = "offres de stage, stage informatique, stage PACA, stage Marseille, stage développeur";

        require __DIR__ . '/../vues/offres_emplois_vue.php';
    }

    // -----------------------------------------------
    // FICHE OFFRE — accessible à tous (SFx7)
    // -----------------------------------------------
    public function show(): void {
        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            header('Location: /index.php?page=offres_emplois');
            exit;
        }

        $modele = new OffresModele($this->pdo);
        $offre  = $modele->getOffreById($id);

        if (!$offre) {
            header('Location: /index.php?page=offres_emplois');
            exit;
        }

        // Nombre de candidatures
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM candidature WHERE id_offre = :id");
        $stmt->execute([':id' => $id]);
        $nb_candidatures = $stmt->fetchColumn();

        // Vérifie si l'offre est en favori (étudiant seulement)
        $estFavori = false;
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'etudiant') {
            $favorisModele = new FavorisModele($this->pdo);
            $estFavori     = $favorisModele->estFavori((int)$_SESSION['user']['id_utilisateur'], $id);
        }

        $meta_title       = "Jobeo | " . htmlspecialchars($offre['titre']) . " — " . htmlspecialchars($offre['nom_entreprise']);
        $meta_description = "Stage " . htmlspecialchars($offre['titre']) . " chez " . htmlspecialchars($offre['nom_entreprise']) . " — " . htmlspecialchars($offre['localisation']) . ". Durée : " . htmlspecialchars($offre['duree']) . " mois.";
        $meta_keywords    = "stage " . htmlspecialchars($offre['titre']) . ", " . htmlspecialchars($offre['nom_entreprise']) . ", stage " . htmlspecialchars($offre['localisation']);

        require __DIR__ . '/../vues/fiche_offre_vue.php';
    }

    // -----------------------------------------------
    // FORMULAIRE CRÉATION — admin + pilote (SFx8)
    // -----------------------------------------------
    public function create(): void {
        $this->verifierRole(['admin', 'pilote']);

        $modele      = new OffresModele($this->pdo);
        $entreprises = $modele->getEntreprises();
        $competences = $modele->getCompetences();

        $meta_title       = "Jobeo | Créer une offre";
        $meta_description = "Ajoutez une nouvelle offre de stage sur la plateforme Jobeo.";
        $meta_keywords    = "créer offre, ajouter stage, Jobeo admin";

        require __DIR__ . '/../vues/offre_form_vue.php';
    }

    // -----------------------------------------------
    // TRAITEMENT CRÉATION — admin + pilote (SFx8)
    // -----------------------------------------------
    public function store(): void {
        $this->verifierRole(['admin', 'pilote']);

        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalide.');
        }

        // Validation champs obligatoires
        if (empty(trim($_POST['titre'] ?? ''))) {
            header('Location: /index.php?page=offres_emplois&action=create&erreur=titre_obligatoire');
            exit;
        }
        if (empty($_POST['id_entreprise'])) {
            header('Location: /index.php?page=offres_emplois&action=create&erreur=entreprise_obligatoire');
            exit;
        }

        $modele = new OffresModele($this->pdo);

        $donnees = [
            'titre'         => trim($_POST['titre']        ?? ''),
            'description'   => trim($_POST['description']  ?? ''),
            'salaire'       => $_POST['salaire']            ?? null,
            'duree'         => $_POST['duree']              ?? null,
            'localisation'  => trim($_POST['localisation']  ?? ''),
            'date_offre'    => $_POST['date_offre']         ?? date('Y-m-d'),
            'id_entreprise' => (int)$_POST['id_entreprise'],
        ];
        $competences = $_POST['competences'] ?? [];

        $id_offre = $modele->creerOffre($donnees, $competences);

        header('Location: /index.php?page=offres_emplois&action=show&id=' . $id_offre);
        exit;
    }

    // -----------------------------------------------
    // FORMULAIRE MODIFICATION — admin + pilote (SFx9)
    // -----------------------------------------------
    public function edit(): void {
        $this->verifierRole(['admin', 'pilote']);

        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            header('Location: /index.php?page=offres_emplois');
            exit;
        }

        $modele            = new OffresModele($this->pdo);
        $offre             = $modele->getOffreById($id);

        if (!$offre) {
            header('Location: /index.php?page=offres_emplois');
            exit;
        }

        $entreprises       = $modele->getEntreprises();
        $competences       = $modele->getCompetences();
        $competences_offre = $modele->getCompetencesParOffre($id);

        $meta_title       = "Jobeo | Modifier " . htmlspecialchars($offre['titre']);
        $meta_description = "Modifiez les informations de l'offre de stage sur Jobeo.";
        $meta_keywords    = "modifier offre, Jobeo admin";

        require __DIR__ . '/../vues/offre_form_vue.php';
    }

    // -----------------------------------------------
    // TRAITEMENT MODIFICATION — admin + pilote (SFx9)
    // -----------------------------------------------
    public function update(): void {
        $this->verifierRole(['admin', 'pilote']);

        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalide.');
        }

        $id = (int)($_POST['id_offre'] ?? 0);

        if (!$id) {
            header('Location: /index.php?page=offres_emplois');
            exit;
        }

        // Validation champs obligatoires
        if (empty(trim($_POST['titre'] ?? ''))) {
            header('Location: /index.php?page=offres_emplois&action=edit&id=' . $id . '&erreur=titre_obligatoire');
            exit;
        }

        $modele = new OffresModele($this->pdo);

        $donnees = [
            'titre'         => trim($_POST['titre']        ?? ''),
            'description'   => trim($_POST['description']  ?? ''),
            'salaire'       => $_POST['salaire']            ?? null,
            'duree'         => $_POST['duree']              ?? null,
            'localisation'  => trim($_POST['localisation']  ?? ''),
            'date_offre'    => $_POST['date_offre']         ?? date('Y-m-d'),
            'id_entreprise' => (int)($_POST['id_entreprise'] ?? 0),
        ];
        $competences = $_POST['competences'] ?? [];

        $modele->modifierOffre($id, $donnees, $competences);

        header('Location: /index.php?page=offres_emplois&action=show&id=' . $id);
        exit;
    }

    // -----------------------------------------------
    // SUPPRESSION — admin + pilote (SFx10)
    // -----------------------------------------------
    public function delete(): void {
        $this->verifierRole(['admin', 'pilote']);

        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            header('Location: /index.php?page=offres_emplois');
            exit;
        }

        $modele = new OffresModele($this->pdo);
        $modele->supprimerOffre($id);

        header('Location: /index.php?page=offres_emplois');
        exit;
    }
}
?>