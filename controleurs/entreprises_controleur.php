<?php
// Ce fichier a un seul rôle : faire le lien entre le modèle et la vue

// On importe le modèle pour pouvoir l'utiliser ici
require_once __DIR__ . '/../modeles/entreprises_modele.php';

class EntrepriseControleur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }    

    public function index() {
        $model       = new EntrepriseModele($this->pdo);
        $entreprises = $model->getEntreprises();
        require __DIR__ . '/../vues/entreprises_vue.php';
    }

    public function create() {
        // $this->verifierRole(['admin', 'pilote']); // TODO : décommenter quand auth en place
        require __DIR__ . '/../vues/entreprise_form_vue.php';
    }

    public function store() {
        // $this->verifierRole(['admin', 'pilote']); // TODO : décommenter quand auth en place
        $model = new EntrepriseModele($this->pdo);

        $donnees = [
            'nom'         => $_POST['nom']         ?? '',
            'description' => $_POST['description'] ?? '',
            'image_logo'  => $_POST['image_logo']  ?? '',
            'image_fond'  => $_POST['image_fond']  ?? '',
        ];

        $model->creerEntreprise($donnees);

        header('Location: /public/index.php?page=entreprises');
        exit;
    }
}

?>