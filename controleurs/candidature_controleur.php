<?php
require_once __DIR__ . '/../modeles/candidature_modele.php';
require_once __DIR__ . '/../helper/csrf.php';

class CandidatureControleur {

    private $pdo;
    private $modele;

    public function __construct($pdo) {
        $this->pdo    = $pdo;
        $this->modele = new CandidatureModele($pdo);
    }

    // -----------------------------------------------
    // FORMULAIRE CANDIDATURE — étudiant seulement (SFx20)
    // -----------------------------------------------
    public function create(): void {
        $this->exigerEtudiant();

        $id_offre = (int)($_GET['id'] ?? 0);

        if (!$id_offre) {
            header('Location: /index.php?page=offres_emplois');
            exit;
        }

        $id_utilisateur = (int)$_SESSION['user']['id_utilisateur'];
        $erreur = $succes = null;

        if ($this->modele->dejaCandidature($id_offre, $id_utilisateur)) {
            $erreur = "Vous avez déjà postulé à cette offre.";
        }

        $meta_title       = "Jobeo | Postuler à une offre";
        $meta_description = "Déposez votre candidature avec votre CV et lettre de motivation sur Jobeo.";
        $meta_keywords    = "postuler stage, candidature, CV, lettre motivation";

        require __DIR__ . '/../vues/postuler_offre_vue.php';
    }

    // -----------------------------------------------
    // TRAITEMENT CANDIDATURE — étudiant seulement (SFx20)
    // -----------------------------------------------
    public function store(): void {
        $this->exigerEtudiant();

        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalide.');
        }

        $id_offre       = (int)($_GET['id'] ?? 0);
        $id_utilisateur = (int)$_SESSION['user']['id_utilisateur'];

        if (!$id_offre) {
            header('Location: /index.php?page=offres_emplois');
            exit;
        }

        // Vérification double candidature côté serveur
        if ($this->modele->dejaCandidature($id_offre, $id_utilisateur)) {
            $erreur = "Vous avez déjà postulé à cette offre.";
            require __DIR__ . '/../vues/postuler_offre_vue.php';
            return;
        }

        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/candidatures/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $cv     = $this->uploadFichier('cv', $uploadDir);
        $lettre = $this->uploadFichier('lettre_motivation', $uploadDir);

        if (!$cv || !$lettre) {
            $erreur = "Erreur : formats acceptés PDF, Word, Images, TXT. Taille max 5MB.";
            require __DIR__ . '/../vues/postuler_offre_vue.php';
            return;
        }

        $donnees = [
            'id_offre'          => $id_offre,
            'id_utilisateur'    => $id_utilisateur,
            'cv'                => $cv,
            'lettre_motivation' => $lettre
        ];

        if ($this->modele->creerCandidature($donnees)) {
            header('Location: /index.php?page=candidature&action=index');
            exit;
        } else {
            $erreur = "Erreur lors de l'enregistrement de votre candidature.";
            require __DIR__ . '/../vues/postuler_offre_vue.php';
        }
    }

    // -----------------------------------------------
    // MES CANDIDATURES — étudiant seulement (SFx21)
    // -----------------------------------------------
    public function index(): void {
        $this->exigerEtudiant();

        $id_utilisateur = (int)$_SESSION['user']['id_utilisateur'];
        $candidatures   = $this->modele->getCandidaturesParUtilisateur($id_utilisateur);

        $meta_title       = "Jobeo | Mes Candidatures";
        $meta_description = "Suivez l'état de vos candidatures aux offres de stage sur Jobeo.";
        $meta_keywords    = "candidatures, postuler stage, suivi candidature";

        require __DIR__ . '/../vues/candidatures_vue.php';
    }

    // -----------------------------------------------
    // CANDIDATURES DES ÉTUDIANTS — pilote + admin (SFx22)
    // -----------------------------------------------
    public function candidaturesPilote(): void {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['pilote', 'admin'])) {
            header('Location: /index.php?page=auth&action=connexion');
            exit;
        }

        $candidatures = $this->modele->getCandidaturesPilote();

        $meta_title       = "Jobeo | Candidatures de mes étudiants";
        $meta_description = "Consultez les candidatures de vos étudiants aux offres de stage sur Jobeo.";
        $meta_keywords    = "candidatures étudiants, pilote, suivi stage";

        require __DIR__ . '/../vues/candidatures_pilote_vue.php';
    }

    // -----------------------------------------------
    // Vérifie que l'utilisateur est étudiant connecté
    // -----------------------------------------------
    private function exigerEtudiant(): void {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
            header('Location: /index.php?page=auth&action=connexion');
            exit;
        }
    }

    // -----------------------------------------------
    // Upload sécurisé d'un fichier
    // -----------------------------------------------
    private function uploadFichier(string $nomChamp, string $destination): ?string {
        if (empty($_FILES[$nomChamp]['name']) || $_FILES[$nomChamp]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Limite 5MB
        if ($_FILES[$nomChamp]['size'] > 5 * 1024 * 1024) {
            return null;
        }

        $extension = strtolower(pathinfo($_FILES[$nomChamp]['name'], PATHINFO_EXTENSION));
        $autorises = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'txt', 'odt'];

        if (!in_array($extension, $autorises)) {
            return null;
        }

        $nouveauNom = $nomChamp . '_' . uniqid() . '.' . $extension;

        if (move_uploaded_file($_FILES[$nomChamp]['tmp_name'], $destination . $nouveauNom)) {
            return $nouveauNom;
        }

        return null;
    }
}
?>