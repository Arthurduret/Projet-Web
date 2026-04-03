<?php
require_once __DIR__ . '/../modeles/entreprises_modele.php';
require_once __DIR__ . '/../modeles/evaluation_modele.php';
require_once __DIR__ . '/../helper/validation.php';
require_once __DIR__ . '/../helper/csrf.php';

class EntrepriseControleur {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // -----------------------------------------------
    // LISTE — accessible à tous (SFx2)
    // -----------------------------------------------
    public function index(): void {
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

    // -----------------------------------------------
    // FICHE — accessible à tous (SFx2)
    // -----------------------------------------------
    public function show(): void {
        $id    = (int)($_GET['id'] ?? 0);

        if (!$id) {
            header('Location: /index.php?page=entreprises');
            exit;
        }

        $model      = new EntrepriseModele($this->pdo);
        $entreprise = $model->getEntrepriseById($id);

        if (!$entreprise) {
            header('Location: /index.php?page=entreprises');
            exit;
        }

        // Moyenne des évaluations
        $stmt = $this->pdo->prepare("
            SELECT ROUND(AVG(note), 1) AS moyenne, COUNT(*) AS nb_avis
            FROM evaluation
            WHERE id_entreprise = :id
        ");
        $stmt->execute([':id' => $id]);
        $eval_data    = $stmt->fetch(PDO::FETCH_ASSOC);
        $moyenne_eval = $eval_data['moyenne'] ?? null;
        $nb_avis      = $eval_data['nb_avis']  ?? 0;

        // Note de l'utilisateur connecté (admin/pilote uniquement — SFx5)
        $ma_note = null;
        if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'pilote'])) {
            $evalModele = new EvaluationModele($this->pdo);
            $evaluation = $evalModele->getEvaluation($_SESSION['user']['id_utilisateur'], $id);
            $ma_note    = $evaluation['note'] ?? null;
        }

        $meta_title       = "Jobeo | " . htmlspecialchars($entreprise['nom']);
        $meta_description = "Découvrez " . htmlspecialchars($entreprise['nom']) . " — " . mb_substr(strip_tags($entreprise['description']), 0, 150) . "...";
        $meta_keywords    = "stage " . htmlspecialchars($entreprise['nom']) . ", entreprise partenaire CESI";

        require __DIR__ . '/../vues/fiche_entreprise_vue.php';
    }

    // -----------------------------------------------
    // FORMULAIRE CRÉATION — admin + pilote (SFx3)
    // -----------------------------------------------
    public function create(): void {
        $this->verifierRole(['admin', 'pilote']);

        $meta_title       = "Jobeo | Créer une entreprise";
        $meta_description = "Ajoutez une nouvelle entreprise partenaire sur la plateforme Jobeo.";
        $meta_keywords    = "créer entreprise, ajouter partenaire, Jobeo";

        require __DIR__ . '/../vues/entreprise_form_vue.php';
    }

    // -----------------------------------------------
    // TRAITEMENT CRÉATION — admin + pilote (SFx3)
    // -----------------------------------------------
    public function store(): void {
        $this->verifierRole(['admin', 'pilote']);

        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalide.');
        }

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

        if (!$image_logo || !$image_fond) {
            Validation::erreur("Erreur lors de l'upload des images. Formats acceptés : jpg, jpeg, png, webp, gif.", '/index.php?page=entreprises&action=create');
        }

        $donnees = [
            'nom'         => trim($_POST['nom']         ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'email'       => trim($_POST['email']       ?? ''),
            'tel'         => trim($_POST['tel']         ?? ''),
            'image_logo'  => $image_logo,
            'image_fond'  => $image_fond,
        ];

        $model = new EntrepriseModele($this->pdo);
        $model->creerEntreprise($donnees);

        unset($_SESSION['form_data']);
        header('Location: /index.php?page=entreprises');
        exit;
    }

    // -----------------------------------------------
    // FORMULAIRE MODIFICATION — admin + pilote (SFx4)
    // -----------------------------------------------
    public function edit(): void {
        $this->verifierRole(['admin', 'pilote']);

        $id    = (int)($_GET['id'] ?? 0);

        if (!$id) {
            header('Location: /index.php?page=entreprises');
            exit;
        }

        $model      = new EntrepriseModele($this->pdo);
        $entreprise = $model->getEntrepriseById($id);

        if (!$entreprise) {
            header('Location: /index.php?page=entreprises');
            exit;
        }

        $meta_title       = "Jobeo | Modifier " . htmlspecialchars($entreprise['nom']);
        $meta_description = "Modifiez les informations de l'entreprise " . htmlspecialchars($entreprise['nom']) . " sur Jobeo.";
        $meta_keywords    = "modifier entreprise, Jobeo admin";

        require __DIR__ . '/../vues/entreprise_form_vue.php';
    }

    // -----------------------------------------------
    // TRAITEMENT MODIFICATION — admin + pilote (SFx4)
    // -----------------------------------------------
    public function update(): void {
        $this->verifierRole(['admin', 'pilote']);

        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalide.');
        }

        $id = (int)($_POST['id_entreprise'] ?? $_GET['id'] ?? 0);

        if (!$id) {
            header('Location: /index.php?page=entreprises');
            exit;
        }

        // Validations
        if (!Validation::requis($_POST['nom'] ?? '')) {
            Validation::erreur("Le nom est obligatoire.", '/index.php?page=entreprises&action=edit&id=' . $id);
        }
        if (!Validation::email($_POST['email'] ?? '')) {
            Validation::erreur("L'adresse email n'est pas valide.", '/index.php?page=entreprises&action=edit&id=' . $id);
        }
        if (!Validation::telephone($_POST['tel'] ?? '')) {
            Validation::erreur("Le téléphone doit contenir 10 chiffres et commencer par 0.", '/index.php?page=entreprises&action=edit&id=' . $id);
        }

        $model = new EntrepriseModele($this->pdo);

        $image_logo = !empty($_FILES['image_logo']['name'])
            ? $this->uploaderImage('image_logo', 'entreprises/logo')
            : $_POST['image_logo_actuel'] ?? null;

        $image_fond = !empty($_FILES['image_fond']['name'])
            ? $this->uploaderImage('image_fond', 'entreprises/fond')
            : $_POST['image_fond_actuel'] ?? null;

        $donnees = [
            'nom'         => trim($_POST['nom']         ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'email'       => trim($_POST['email']       ?? ''),
            'tel'         => trim($_POST['tel']         ?? ''),
            'image_logo'  => $image_logo,
            'image_fond'  => $image_fond,
        ];

        $model->modifierEntreprise($id, $donnees);

        header('Location: /index.php?page=entreprises&action=show&id=' . $id);
        exit;
    }

    // -----------------------------------------------
    // SUPPRESSION — admin + pilote (SFx6)
    // -----------------------------------------------
    public function delete(): void {
        $this->verifierRole(['admin', 'pilote']);

        $id    = (int)($_GET['id'] ?? 0);

        if (!$id) {
            header('Location: /index.php?page=entreprises');
            exit;
        }

        $model = new EntrepriseModele($this->pdo);
        $model->supprimerEntreprise($id);

        header('Location: /index.php?page=entreprises');
        exit;
    }

    // -----------------------------------------------
    // Upload sécurisé d'une image
    // -----------------------------------------------
    private function uploaderImage(string $champ, string $dossier): ?string {
        if (!isset($_FILES[$champ]) || $_FILES[$champ]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Limite 5MB
        if ($_FILES[$champ]['size'] > 5 * 1024 * 1024) {
            return null;
        }

        $fichier   = $_FILES[$champ];
        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));

        $extensions_ok = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($extension, $extensions_ok)) {
            return null;
        }

        $nom_fichier     = uniqid('img_', true) . '.' . $extension;
        $dossier_complet = dirname(__DIR__) . '/public/images/' . $dossier . '/';
        $destination     = $dossier_complet . $nom_fichier;

        if (!is_dir($dossier_complet)) {
            mkdir($dossier_complet, 0777, true);
        }

        if (move_uploaded_file($fichier['tmp_name'], $destination)) {
            return $nom_fichier;
        }

        return null;
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
}
?>