<?php
require_once __DIR__ . '/../modeles/candidatures_modele.php';

class CandidatureControleur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Afficher la liste des candidatures (Mes Candidatures)
    public function index() {
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: index.php?page=auth');
            exit;
        }
        $candidatures = get_candidatures_by_user($_SESSION['id_utilisateur'], $this->pdo);
        require_once __DIR__ . '/../vues/candidatures_vue.php';
    }

    // Action de postuler (Enregistrer en BDD)
    public function postuler() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // On récupère les données du formulaire
            $id_offre = $_POST['id_offre'];
            $id_user  = $_SESSION['id_utilisateur'];
            $cv       = $_POST['cv']; // Idéalement un chemin vers un fichier uploadé
            $motivation = $_POST['lettre_motivation'];

            $sql = "INSERT INTO candidature (cv, lettre_motivation, id_utilisateur, id_offre) 
                    VALUES (:cv, :motivation, :id_user, :id_offre)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'cv'         => $cv,
                'motivation' => $motivation,
                'id_user'    => $id_user,
                'id_offre'   => $id_offre
            ]);

            header('Location: index.php?page=candidatures&action=index');
        }
    }
}