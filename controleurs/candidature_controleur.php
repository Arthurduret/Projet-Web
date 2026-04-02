<?php
require_once __DIR__ . '/../modeles/candidature_modele.php';

class CandidatureControleur {

    private $pdo;
    private $modele;

    public function __construct($pdo) {
        $this->pdo    = $pdo;
        $this->modele = new CandidatureModele($pdo);
    }

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

        require __DIR__ . '/../vues/postuler_offre_vue.php';
    }

    public function store(): void {
        $this->exigerEtudiant();
        $id_offre = (int)($_GET['id'] ?? 0);
        $id_utilisateur = (int)$_SESSION['user']['id_utilisateur'];

        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/candidatures/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $cv     = $this->uploadFichier('cv', $uploadDir);
        $lettre = $this->uploadFichier('lettre_motivation', $uploadDir);

        if (!$cv || !$lettre) {
            $erreur = "Erreur : formats acceptés PDF, Word, Images, TXT.";
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
            $erreur = "Erreur lors de l'enregistrement.";
            require __DIR__ . '/../vues/postuler_offre_vue.php';
        }
    }

    public function index(): void {
        $this->exigerEtudiant();
        $id_utilisateur = (int)$_SESSION['user']['id_utilisateur'];
        $candidatures = $this->modele->getCandidaturesParUtilisateur($id_utilisateur);
        require __DIR__ . '/../vues/candidatures_vue.php';
    }
    
    public function candidaturesPilote(): void {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['pilote', 'admin'])) {
            header('Location: /index.php?page=auth&action=connexion');
            exit;
        }

        $candidatures = $this->modele->getCandidaturesPilote();
        require __DIR__ . '/../vues/candidatures_pilote_vue.php';
    }

    private function exigerEtudiant(): void {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
            header('Location: /index.php?page=auth&action=connexion');
            exit;
        }
    }

    private function uploadFichier(string $nomChamp, string $destination): ?string {
        if (empty($_FILES[$nomChamp]['name']) || $_FILES[$nomChamp]['error'] !== UPLOAD_ERR_OK) {
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