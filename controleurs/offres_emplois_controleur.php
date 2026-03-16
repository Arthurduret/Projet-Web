<?php
require_once __DIR__ . '/../modeles/offres_emplois_modele.php';

class OffresControleur {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // -----------------------------------------------
    // Vérifie si l'utilisateur a le bon rôle
    // Utilisé en haut des méthodes protégées
    // -----------------------------------------------
    private function verifierRole(array $rolesAutorises) {
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $rolesAutorises)) {
            header('Location: /public/index.php?page=connexion');
            exit;
        }
    }

    // -----------------------------------------------
    // LISTE — tout le monde peut voir les offres
    // URL : ?page=offres_emplois
    // -----------------------------------------------
    public function index() {
        $modele = new OffresModele($this->pdo);

        $quoi = $_GET['quoi'] ?? '';
        $ou   = $_GET['ou']   ?? '';

        if (!empty($quoi) || !empty($ou)) {
            $offres = $modele->rechercherOffres($quoi, $ou);
        } else {
            $offres = $modele->getOffres();
        }

        $nb_offres = count($offres);

        require __DIR__ . '/../vues/offres_emplois_vue.php';
    }

    // -----------------------------------------------
    // FICHE — tout le monde peut voir le détail
    // URL : ?page=offres_emplois&action=show&id=3
    // -----------------------------------------------
    public function show() {
        $id = $_GET['id'] ?? null;

        // Si pas d'id dans l'URL → retour à la liste
        if (!$id) {
            header('Location: /public/index.php?page=offres_emplois');
            exit;
        }

        $modele = new OffresModele($this->pdo);
        $offre  = $modele->getOffreById($id);

        // Si l'offre n'existe pas en BDD → retour à la liste
        if (!$offre) {
            header('Location: /public/index.php?page=offres_emplois');
            exit;
        }

        require __DIR__ . '/../vues/fiche_offre_vue.php';
    }

    // -----------------------------------------------
    // FORMULAIRE CRÉATION — admin et pilote seulement
    // URL : ?page=offres_emplois&action=create
    // -----------------------------------------------
    public function create() {
        // $this->verifierRole(['admin', 'pilote']); // TODO : décommenter quand auth en place

        $modele      = new OffresModele($this->pdo);
        $entreprises = $modele->getEntreprises();
        $competences = $modele->getCompetences();

        require __DIR__ . '/../vues/offre_form_vue.php';
    }

    // -----------------------------------------------
    // TRAITEMENT CRÉATION — admin et pilote seulement
    // URL : ?page=offres_emplois&action=store (POST)
    // -----------------------------------------------
    public function store() {
        // $this->verifierRole(['admin', 'pilote']); // TODO : décommenter quand auth en place

        $modele = new OffresModele($this->pdo);

        // Récupère les données du formulaire
        $donnees = [
            'titre'         => $_POST['titre']        ?? '',
            'description'   => $_POST['description']  ?? '',
            'salaire'       => $_POST['salaire']       ?? null,
            'duree'         => $_POST['duree']         ?? null,
            'localisation'  => $_POST['localisation']  ?? '',
            'date_offre'    => $_POST['date_offre']    ?? date('Y-m-d'),
            'id_entreprise' => $_POST['id_entreprise'] ?? null,
        ];
        $competences = $_POST['competences'] ?? []; // tableau d'ids

        // Insère l'offre et récupère son id
        $id_offre = $modele->creerOffre($donnees, $competences);

        // Redirige vers la fiche de la nouvelle offre
        header('Location: /public/index.php?page=offres_emplois&action=show&id=' . $id_offre);
        exit;
    }

    // -----------------------------------------------
    // FORMULAIRE MODIFICATION — admin et pilote seulement
    // URL : ?page=offres_emplois&action=edit&id=3
    // -----------------------------------------------
    public function edit() {
        $this->verifierRole(['admin', 'pilote']);

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /public/index.php?page=offres_emplois');
            exit;
        }

        $modele      = new OffresModele($this->pdo);
        $offre       = $modele->getOffreById($id);
        $entreprises = $modele->getEntreprises();
        $competences = $modele->getCompetences();
        // Compétences déjà sélectionnées pour cette offre
        $competences_offre = $modele->getCompetencesParOffre($id);

        require __DIR__ . '/../vues/offre_form_vue.php';
    }

    // -----------------------------------------------
    // TRAITEMENT MODIFICATION — admin et pilote seulement
    // URL : ?page=offres_emplois&action=update (POST)
    // -----------------------------------------------
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

        header('Location: /public/index.php?page=offres_emplois&action=show&id=' . $id);
        exit;
    }

    // -----------------------------------------------
    // SUPPRESSION — admin seulement
    // URL : ?page=offres_emplois&action=delete&id=3
    // -----------------------------------------------
    public function delete() {
        $this->verifierRole(['admin']);

        $id     = $_GET['id'] ?? null;
        $modele = new OffresModele($this->pdo);

        $modele->supprimerOffre($id);

        header('Location: /public/index.php?page=offres_emplois');
        exit;
    }
}
?>