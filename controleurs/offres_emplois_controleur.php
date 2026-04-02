<?php
require_once __DIR__ . '/../modeles/offres_emplois_modele.php';

class OffresControleur {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Vérifie si l'utilisateur a le bon rôle
    private function verifierRole(array $rolesAutorises) {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], $rolesAutorises)) {
            header('Location: /index.php?page=auth&action=identifier');
            exit;
        }
    }

    // LISTE — tout le monde peut voir les offres
    public function index() {
        $modele = new OffresModele($this->pdo);

        $quoi    = $_GET['quoi'] ?? '';
        $ou      = $_GET['ou']   ?? '';
        $page    = max(1, (int)($_GET['p'] ?? 1));
        $limite  = 10;
        $offset  = ($page - 1) * $limite;

        $filtres = [
            'f_entreprise'       => $_GET['f_entreprise']       ?? '',
            'f_titre'            => $_GET['f_titre']            ?? '',
            'f_competence'       => $_GET['f_competence']       ?? '',
            'f_salaire_min'      => $_GET['f_salaire_min']      ?? '',
            'f_salaire_max'      => $_GET['f_salaire_max']      ?? '',
            'f_date'             => $_GET['f_date']             ?? '',
            'f_candidatures_min' => $_GET['f_candidatures_min'] ?? '',
            'f_tri'              => $_GET['f_tri']              ?? '',
        ];

        $offres    = $modele->filtrerOffres($quoi, $ou, $filtres, $limite, $offset);
        $total     = $modele->compterOffresFiltrees($quoi, $ou, $filtres);
        $nb_offres = count($offres);
        $nb_pages  = ceil($total / $limite);

        $favoris_ids = [];
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'etudiant') {
            require_once __DIR__ . '/../modeles/favoris_modele.php';
            $favorisModele = new FavorisModele($this->pdo);
            $favoris       = $favorisModele->getFavoris($_SESSION['user']['id_utilisateur']);
            $favoris_ids   = array_column($favoris, 'id_offre');
        }

        require __DIR__ . '/../vues/offres_emplois_vue.php';
    }

    // FICHE — tout le monde peut voir le détail
    public function show() {
        $id = $_GET['id'] ?? null;

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

        // Récupère le nombre de candidatures
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM candidature WHERE id_offre = :id
        ");
        $stmt->execute([':id' => $id]);
        $nb_candidatures = $stmt->fetchColumn();

        // Vérifie si l'offre est en favori
        $estFavori = false;
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'etudiant') {
            require_once __DIR__ . '/../modeles/favoris_modele.php';
            $favorisModele = new FavorisModele($this->pdo);
            $estFavori = $favorisModele->estFavori($_SESSION['user']['id_utilisateur'], $id);
        }

        require __DIR__ . '/../vues/fiche_offre_vue.php';
    }

    // FORMULAIRE CRÉATION — admin et pilote seulement
    public function create() {
        // $this->verifierRole(['admin', 'pilote']);

        $modele      = new OffresModele($this->pdo);
        $entreprises = $modele->getEntreprises();
        $competences = $modele->getCompetences();

        require __DIR__ . '/../vues/offre_form_vue.php';
    }

    // TRAITEMENT CRÉATION — admin et pilote seulement
    public function store() {
        // $this->verifierRole(['admin', 'pilote']);

        $modele = new OffresModele($this->pdo);

        $donnees = [
            'titre'         => $_POST['titre']        ?? '',
            'description'   => $_POST['description']  ?? '',
            'salaire'       => $_POST['salaire']       ?? null,
            'duree'         => $_POST['duree']         ?? null,
            'localisation'  => $_POST['localisation']  ?? '',
            'date_offre'    => $_POST['date_offre']    ?? date('Y-m-d'),
            'id_entreprise' => $_POST['id_entreprise'] ?? null,
        ];
        $competences = $_POST['competences'] ?? [];

        $id_offre = $modele->creerOffre($donnees, $competences);

        header('Location: /index.php?page=offres_emplois&action=show&id=' . $id_offre);
        exit;
    }

    // FORMULAIRE MODIFICATION — admin et pilote seulement
    public function edit() {
        $this->verifierRole(['admin', 'pilote']);

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /index.php?page=offres_emplois');
            exit;
        }

        $modele            = new OffresModele($this->pdo);
        $offre             = $modele->getOffreById($id);
        $entreprises       = $modele->getEntreprises();
        $competences       = $modele->getCompetences();
        $competences_offre = $modele->getCompetencesParOffre($id);

        require __DIR__ . '/../vues/offre_form_vue.php';
    }

    // TRAITEMENT MODIFICATION — admin et pilote seulement
    public function update() {
        $this->verifierRole(['admin', 'pilote']);

        $id     = $_POST['id_offre'] ?? null;
        $modele = new OffresModele($this->pdo);

        $donnees = [
            'titre'         => $_POST['titre']        ?? '',
            'description'   => $_POST['description']  ?? '',
            'salaire'       => $_POST['salaire']       ?? null,
            'duree'         => $_POST['duree']         ?? null,
            'localisation'  => $_POST['localisation']  ?? '',
            'date_offre'    => $_POST['date_offre']    ?? date('Y-m-d'),
            'id_entreprise' => $_POST['id_entreprise'] ?? null,
        ];
        $competences = $_POST['competences'] ?? [];

        $modele->modifierOffre($id, $donnees, $competences);

        header('Location: /index.php?page=offres_emplois&action=show&id=' . $id);
        exit;
    }

    // SUPPRESSION — admin seulement
    public function delete() {
        $this->verifierRole(['admin', 'pilote']); // ← ajoute pilote
        
        $id     = $_GET['id'] ?? null;
        $modele = new OffresModele($this->pdo);
        $modele->supprimerOffre($id);

        header('Location: /index.php?page=offres_emplois');
        exit;
    }
}
?>