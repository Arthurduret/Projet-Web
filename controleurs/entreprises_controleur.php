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


        $image_logo = $this->uploaderImage('image_logo', 'entreprises/logo');
        $image_fond = $this->uploaderImage('image_fond', 'entreprises/fond');

        $donnees = [
            'nom'         => $_POST['nom']         ?? '',
            'description' => $_POST['description'] ?? '',
            'image_logo'  => $image_logo,
            'image_fond'  => $image_fond,
        ];


        $model->creerEntreprise($donnees);

        header('Location: /public/index.php?page=entreprises');
        exit;
    }

    public function show() {
        $id    = $_GET['id'] ?? null;
        $model = new EntrepriseModele($this->pdo);
        $entreprise = $model->getEntrepriseById($id);
        require __DIR__ . '/../vues/entreprise_show_vue.php';
    }

    public function edit() {
        $id    = $_GET['id'] ?? null;
        $model = new EntrepriseModele($this->pdo);
        $entreprise = $model->getEntrepriseById($id);
        require __DIR__ . '/../vues/entreprise_form_vue.php';
    }

    public function update() {
        $id    = $_GET['id'] ?? null;
        $model = new EntrepriseModele($this->pdo);

        $donnees = [
            'nom'         => $_POST['nom']         ?? '',
            'description' => $_POST['description'] ?? '',
            'image_logo'  => $_POST['image_logo']  ?? '',
            'image_fond'  => $_POST['image_fond']  ?? '',
        ];

        $model->modifierEntreprise($id, $donnees);

        header('Location: /public/index.php?page=entreprises');
        exit;
    }

    public function delete() {
        $id    = $_GET['id'] ?? null;
        $model = new EntrepriseModele($this->pdo);
        $model->supprimerEntreprise($id);
        header('Location: /public/index.php?page=entreprises');
        exit;
    }

    private function uploaderImage($champ, $dossier) {
        if (!isset($_FILES[$champ]) || $_FILES[$champ]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $fichier   = $_FILES[$champ];
        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
        
        $extensions_ok = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($extension, $extensions_ok)) {
            return null;
        }

        $nom_fichier     = uniqid('img_', true) . '.' . $extension;
        $dossier_complet = 'C:/Users/Arthur/Documents/CESI/Web/Projet-Web/public/images/' . $dossier . '/';
        $destination     = $dossier_complet . $nom_fichier;

        if (move_uploaded_file($fichier['tmp_name'], $destination)) {
            return $nom_fichier;
        }

        return null;
    }


}
?>