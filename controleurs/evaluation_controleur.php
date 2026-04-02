<?php
require_once __DIR__ . '/../modeles/evaluation_modele.php';

class EvaluationControleur {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    private function verifierRole() {
        if (!isset($_SESSION['user']) || 
            !in_array($_SESSION['user']['role'], ['admin', 'pilote'])) {
            header('Content-Type: application/json');
            echo json_encode(['succes' => false, 'message' => 'Non autorisé']);
            exit;
        }
    }


    public function noter() {
        $this->verifierRole();

        $id_entreprise  = $_POST['id_entreprise'] ?? null;
        $note           = (int)($_POST['note'] ?? 0);
        $id_utilisateur = $_SESSION['user']['id_utilisateur'];

        if ($note < 1 || $note > 5) {
            header('Content-Type: application/json');
            echo json_encode(['succes' => false, 'message' => 'Note invalide']);
            exit;
        }

        $modele = new EvaluationModele($this->pdo);
        $modele->sauvegarder($id_utilisateur, $id_entreprise, $note);

        header('Content-Type: application/json');
        echo json_encode([
            'succes'  => true,
            'message' => 'Évaluation enregistrée !'
        ]);
        exit;
    }
}
?>