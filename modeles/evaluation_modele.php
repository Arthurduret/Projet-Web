<?php
class EvaluationModele {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    public function aDejaEvalue($id_utilisateur, $id_entreprise) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM evaluation
            WHERE id_utilisateur = :id_utilisateur
            AND id_entreprise = :id_entreprise
        ");
        $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id_entreprise'  => $id_entreprise
        ]);
        return $stmt->fetchColumn() > 0;
    }


    public function getEvaluation($id_utilisateur, $id_entreprise) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM evaluation
            WHERE id_utilisateur = :id_utilisateur
            AND id_entreprise = :id_entreprise
        ");
        $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id_entreprise'  => $id_entreprise
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ajouter($id_utilisateur, $id_entreprise, $note) {
        $stmt = $this->pdo->prepare("
            INSERT INTO evaluation (note, id_entreprise, id_utilisateur)
            VALUES (:note, :id_entreprise, :id_utilisateur)
        ");
        $stmt->execute([
            ':note'           => $note,
            ':id_entreprise'  => $id_entreprise,
            ':id_utilisateur' => $id_utilisateur
        ]);
    }

    public function modifier($id_utilisateur, $id_entreprise, $note) {
        $stmt = $this->pdo->prepare("
            UPDATE evaluation
            SET note = :note
            WHERE id_utilisateur = :id_utilisateur
            AND id_entreprise = :id_entreprise
        ");
        $stmt->execute([
            ':note'           => $note,
            ':id_entreprise'  => $id_entreprise,
            ':id_utilisateur' => $id_utilisateur
        ]);
    }

    public function sauvegarder($id_utilisateur, $id_entreprise, $note) {
        if ($this->aDejaEvalue($id_utilisateur, $id_entreprise)) {
            $this->modifier($id_utilisateur, $id_entreprise, $note);
        } else {
            $this->ajouter($id_utilisateur, $id_entreprise, $note);
        }
    }
}
?>