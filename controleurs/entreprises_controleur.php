<?php
// Ce fichier a un seul rôle : faire le lien entre le modèle et la vue

// On importe le modèle pour pouvoir l'utiliser ici
require_once __DIR__ . '/../modeles/entreprises_modele.php';
require_once __DIR__ . '/../helper/validation.php';

class EntrepriseControleur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }    

    public function index() {
        $model       = new EntrepriseModele($this->pdo);
        $model       = new EntrepriseModele($this->pdo);
        $entreprises = $model->getEntreprises();
        require __DIR__ . '/../vues/entreprises_vue.php';
    }

    public function create() {
        // $this->verifierRole(['admin', 'pilote']); // TODO : décommenter quand auth en place
        require __DIR__ . '/../vues/entreprise_form_vue.php';
    }

    public function store() {
        // Sauvegarde les données pour les restituer en cas d'erreur
        $_SESSION['form_data'] = $_POST;

        // Validations
        if (!Validation::requis($_POST['nom'] ?? '')) {
            Validation::erreur("Le nom est obligatoire.", '/index.php?page=entreprises&action=create');
        }

        if (!Validation::requis($_POST['description'] ?? '')) {
            Validation::erreur("La description est obligatoire.", '/index.php?page=entreprises&action=create');
        }

        if (!Validation::email($_POST['email'] ?? '')) {
            Validation::erreur("L'adresse email n'est pas valide.", '/index.php?page=entreprises&action=create');
        }

        if (!Validation::telephone($_POST['tel'] ?? '')) {
            Validation::erreur("Le téléphone doit contenir 10 chiffres et commencer par 0.", '/index.php?page=entreprises&action=create');
        }

        if (empty($_FILES['image_logo']['name'])) {
            Validation::erreur("Le logo est obligatoire.", '/index.php?page=entreprises&action=create');
        }

        if (empty($_FILES['image_fond']['name'])) {
            Validation::erreur("L'image de fond est obligatoire.", '/index.php?page=entreprises&action=create');
        }

        // Upload des images
        $image_logo = $this->uploaderImage('image_logo', 'entreprises/logo');
        $image_fond = $this->uploaderImage('image_fond', 'entreprises/fond');

        // Insertion en BDD
        $donnees = [
            'nom'         => $_POST['nom']         ?? '',
            'description' => $_POST['description'] ?? '',
            'email'       => $_POST['email']       ?? '',
            'tel'         => $_POST['tel']         ?? '',
            'image_logo'  => $image_logo,
            'image_fond'  => $image_fond,
        ];

        $model = new EntrepriseModele($this->pdo);
        $model->creerEntreprise($donnees);

        // Succès → on vide la session et on redirige
        unset($_SESSION['form_data']);
        header('Location: /index.php?page=entreprises');
        exit;
    }

    public function show() {
        $id    = $_GET['id'] ?? null;
        $model = new EntrepriseModele($this->pdo);
        $entreprise = $model->getEntrepriseById($id);
        require __DIR__ . '/../vues/fiche_entreprise_vue.php';
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

        // Upload seulement si une nouvelle image est envoyée
        $image_logo = !empty($_FILES['image_logo']['name']) 
            ? $this->uploaderImage('image_logo', 'entreprises/logo')
            : $_POST['image_logo_actuel'] ?? null;

        $image_fond = !empty($_FILES['image_fond']['name'])
            ? $this->uploaderImage('image_fond', 'entreprises/fond')
            : $_POST['image_fond_actuel'] ?? null;

        $donnees = [
            'nom'         => $_POST['nom']         ?? '',
            'description' => $_POST['description'] ?? '',
            'email'       => $_POST['email']       ?? '',
            'tel'         => $_POST['tel']         ?? '',
            'image_logo'  => $image_logo,
            'image_fond'  => $image_fond,
        ];

        $model->modifierEntreprise($id, $donnees);

        header('Location: /index.php?page=entreprises&action=show&id=' . $id);
        exit;
    }

    public function delete() {
        $id    = $_GET['id'] ?? null;
        $model = new EntrepriseModele($this->pdo);
        $model->supprimerEntreprise($id);
        header('Location: /index.php?page=entreprises');
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