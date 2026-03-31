<?php
require_once __DIR__ . '/../modeles/candidature_modele.php';

class CandidatureControleur {

    private $pdo;
    private $modele;

    public function __construct($pdo) {
        $this->pdo    = $pdo;
        $this->modele = new CandidatureModele($pdo);
    }

    // ── GET : affiche le formulaire ──────────────────────────────
    public function create(): void {
        $this->exigerEtudiant();

        $id_offre = (int)($_GET['id'] ?? 0);
        if (!$id_offre) {
            header('Location: /index.php?page=offres_emplois');
            exit;
        }

        $id_utilisateur = (int)$_SESSION['user']['id_utilisateur'];

        if ($this->modele->dejaCandidature($id_offre, $id_utilisateur)) {
            $erreur = "Vous avez déjà postulé à cette offre.";
            require __DIR__ . '/../vues/postuler_offre_vue.php';
            return;
        }

        $erreur = $succes = null;
        require __DIR__ . '/../vues/postuler_offre_vue.php';
    }

    // ── POST : traite le formulaire ──────────────────────────────
    public function store(): void {
        $this->exigerEtudiant();

        $id_offre       = (int)($_GET['id'] ?? 0);
        $id_utilisateur = (int)$_SESSION['user']['id_utilisateur'];

        if (!$id_offre) {
            header('Location: /index.php?page=offres_emplois');
            exit;
        }

        if ($this->modele->dejaCandidature($id_offre, $id_utilisateur)) {
            $erreur = "Vous avez déjà postulé à cette offre.";
            require __DIR__ . '/../vues/postuler_offre_vue.php';
            return;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/candidatures/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $cv     = $this->uploadFichier('cv',                $uploadDir);
        $lettre = $this->uploadFichier('lettre_motivation', $uploadDir);

        if (!$cv || !$lettre) {
            $erreur = "Erreur lors de l'envoi des fichiers. Formats acceptés : jpg, jpeg, png, gif, pdf.";
            require __DIR__ . '/../vues/postuler_offre_vue.php';
            return;
        }

        $donnees = [
            'id_offre'          => $id_offre,
            'id_utilisateur'    => $id_utilisateur,
            'cv'                => $cv,
            'lettre_motivation' => $lettre,
            'date_candidature'  => date('Y-m-d H:i:s'),
        ];

        if ($this->modele->creerCandidature($donnees)) {
            $succes = "Votre candidature a bien été envoyée ! 🎉";
        } else {
            $erreur = "Une erreur est survenue en base de données. Veuillez réessayer.";
        }

        require __DIR__ . '/../vues/postuler_offre_vue.php';
    }

    // ── Mes candidatures ─────────────────────────────────────────
    public function index(): void {
        $this->exigerEtudiant();

        $id_utilisateur = (int)$_SESSION['user']['id_utilisateur'];
        $candidatures   = $this->modele->getCandidaturesParUtilisateur($id_utilisateur);
        require __DIR__ . '/../vues/candidatures_vue.php';
    }

    // ── Helpers ──────────────────────────────────────────────────

    private function exigerEtudiant(): void {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
            header('Location: /index.php?page=auth&action=connexion');
            exit;
        }
    }

    private function uploadFichier(string $champ, string $dir): ?string {
        if (empty($_FILES[$champ]['name']) || $_FILES[$champ]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $ext       = strtolower(pathinfo($_FILES[$champ]['name'], PATHINFO_EXTENSION));
        $autorises = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];

        if (!in_array($ext, $autorises)) {
            return null;
        }

        $nomFichier = uniqid($champ . '_') . '.' . $ext;
        move_uploaded_file($_FILES[$champ]['tmp_name'], $dir . $nomFichier);

        return $nomFichier;
    }
}