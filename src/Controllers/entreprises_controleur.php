<?php

require_once __DIR__ . '/../modeles/entreprises_modele.php';
require_once __DIR__ . '/../helper/validation.php';

class EntrepriseControleur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }    

    public function index() {
        $model = new EntrepriseModele($this->pdo);
        $nom   = $_GET['nom'] ?? '';
        $tri   = $_GET['tri'] ?? '';

        if (!empty($nom)) {
            $entreprises = $model->rechercherEntreprises($nom, $tri);
        } else {
            $entreprises = $model->getEntreprises($tri);
        }

        $nb_entreprises = count($entreprises);

        $meta_title       = "Jobeo | Nos Entreprises partenaires";
        $meta_description = "Découvrez les entreprises partenaires de Jobeo qui recrutent des stagiaires CESI en région PACA.";
        $meta_keywords    = "entreprises stage, partenaires CESI, recrutement stagiaire PACA, entreprise Marseille";

        require __DIR__ . '/../vues/entreprises_vue.php';
    }

    public function create() {

        require __DIR__ . '/../vues/entreprise_form_vue.php';
    }

    public function store() {

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


        $image_logo = $this->uploaderImage('image_logo', 'entreprises/logo');
        $image_fond = $this->uploaderImage('image_fond', 'entreprises/fond');

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


        unset($_SESSION['form_data']);
        header('Location: /index.php?page=entreprises');
        exit;
    }

    public function show() {
        $id    = $_GET['id'] ?? null;
        $model = new EntrepriseModele($this->pdo);
        $entreprise = $model->getEntrepriseById($id);

        $stmt = $this->pdo->prepare("
            SELECT ROUND(AVG(note), 1) AS moyenne, COUNT(*) AS nb_avis
            FROM evaluation 
            WHERE id_entreprise = :id
        ");
        $stmt->execute([':id' => $id]);
        $eval_data = $stmt->fetch(PDO::FETCH_ASSOC);
        $moyenne_eval = $eval_data['moyenne'] ?? null;
        $nb_avis      = $eval_data['nb_avis'] ?? 0;

        $ma_note = null;
        if (isset($_SESSION['user']) && 
            in_array($_SESSION['user']['role'], ['admin', 'pilote'])) {
            require_once __DIR__ . '/../modeles/evaluation_modele.php';
            $evalModele = new EvaluationModele($this->pdo);
            $evaluation = $evalModele->getEvaluation(
                $_SESSION['user']['id_utilisateur'], 
                $id
            );
            $ma_note = $evaluation['note'] ?? null;
        }

        $meta_title       = "Jobeo | " . htmlspecialchars($entreprise['nom']);
        $meta_description = "Découvrez " . htmlspecialchars($entreprise['nom']) . " — " . mb_substr(strip_tags($entreprise['description']), 0, 150) . "...";
        $meta_keywords    = "stage " . htmlspecialchars($entreprise['nom']) . ", entreprise partenaire CESI";
                
        require __DIR__ . '/../vues/fiche_entreprise_vue.php';
    }

    public function edit() {
        $this->verifierRole(['admin', 'pilote']);
        $id    = $_GET['id'] ?? null;
        $model = new EntrepriseModele($this->pdo);
        $entreprise = $model->getEntrepriseById($id);
        require __DIR__ . '/../vues/entreprise_form_vue.php';
    }

    public function update() {
        $this->verifierRole(['admin', 'pilote']);
        $id    = $_GET['id'] ?? null; 
        $model = new EntrepriseModele($this->pdo);

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
        $this->verifierRole(['admin', 'pilote']);
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
    
    private function verifierRole(array $rolesAutorises) {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], $rolesAutorises)) {
            header('Location: /index.php?page=auth&action=identifier');
            exit;
        }
    }

}
?>
