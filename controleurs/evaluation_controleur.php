<?php
require_once __DIR__ . '/../modeles/evaluation_modele.php';

class EvaluationControleur {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // -----------------------------------------------
    // Vérifie que l'utilisateur est admin ou pilote (SFx5)
    // Retourne du JSON car appelé en AJAX
    // -----------------------------------------------
    private function verifierRole(): void {
        if (!isset($_SESSION['user']) ||
            !in_array($_SESSION['user']['role'], ['admin', 'pilote'])) {
            header('Content-Type: application/json');
            echo json_encode(['succes' => false, 'message' => 'Non autorisé']);
            exit;
        }
    }

    // -----------------------------------------------
    // NOTER UNE ENTREPRISE — admin + pilote (SFx5)
    // Appelé en AJAX POST
    // -----------------------------------------------
    public function noter(): void {
        $this->verifierRole();

        // Vérification requête AJAX uniquement
        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) ||
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['succes' => false, 'message' => 'Requête invalide']);
            exit;
        }

        $id_entreprise  = (int)($_POST['id_entreprise'] ?? 0);
        $note           = (int)($_POST['note']          ?? 0);
        $id_utilisateur = (int)$_SESSION['user']['id_utilisateur'];

        // Validation id entreprise
        if (!$id_entreprise) {
            header('Content-Type: application/json');
            echo json_encode(['succes' => false, 'message' => 'Entreprise invalide']);
            exit;
        }

        // Validation note entre 1 et 5
        if ($note < 1 || $note > 5) {
            header('Content-Type: application/json');
            echo json_encode(['succes' => false, 'message' => 'Note invalide — doit être entre 1 et 5']);
            exit;
        }

        $modele = new EvaluationModele($this->pdo);
        $modele->sauvegarder($id_utilisateur, $id_entreprise, $note);

        // Récupère la nouvelle moyenne pour la retourner au frontend
        $stmt = $this->pdo->prepare("
            SELECT ROUND(AVG(note), 1) AS moyenne, COUNT(*) AS nb_avis
            FROM evaluation
            WHERE id_entreprise = :id
        ");
        $stmt->execute([':id' => $id_entreprise]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode([
            'succes'  => true,
            'message' => 'Évaluation enregistrée !',
            'moyenne' => $result['moyenne'] ?? null,
            'nb_avis' => $result['nb_avis'] ?? 0,
            'ma_note' => $note
        ]);
        exit;
    }
}
?>