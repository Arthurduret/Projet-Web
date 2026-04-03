<?php
class EvaluationModele {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // -----------------------------------------------
    // Vérifie si un utilisateur a déjà évalué (SFx5)
    // -----------------------------------------------
    public function aDejaEvalue(int $id_utilisateur, int $id_entreprise): bool {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM evaluation
            WHERE id_utilisateur = :id_utilisateur
            AND id_entreprise    = :id_entreprise
        ");
        $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id_entreprise'  => $id_entreprise
        ]);
        return $stmt->fetchColumn() > 0;
    }

    // -----------------------------------------------
    // Récupère l'évaluation d'un utilisateur (SFx5)
    // -----------------------------------------------
    public function getEvaluation(int $id_utilisateur, int $id_entreprise): array|false {
        $stmt = $this->pdo->prepare("
            SELECT * FROM evaluation
            WHERE id_utilisateur = :id_utilisateur
            AND id_entreprise    = :id_entreprise
        ");
        $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id_entreprise'  => $id_entreprise
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------
    // Ajoute une nouvelle évaluation (SFx5)
    // -----------------------------------------------
    public function ajouter(int $id_utilisateur, int $id_entreprise, int $note): void {
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

    // -----------------------------------------------
    // Modifie une évaluation existante (SFx5)
    // -----------------------------------------------
    public function modifier(int $id_utilisateur, int $id_entreprise, int $note): void {
        $stmt = $this->pdo->prepare("
            UPDATE evaluation
            SET note = :note
            WHERE id_utilisateur = :id_utilisateur
            AND id_entreprise    = :id_entreprise
        ");
        $stmt->execute([
            ':note'           => $note,
            ':id_entreprise'  => $id_entreprise,
            ':id_utilisateur' => $id_utilisateur
        ]);
    }

    // -----------------------------------------------
    // Ajoute ou modifie selon si déjà évalué (SFx5)
    // -----------------------------------------------
    public function sauvegarder(int $id_utilisateur, int $id_entreprise, int $note): void {
        if ($this->aDejaEvalue($id_utilisateur, $id_entreprise)) {
            $this->modifier($id_utilisateur, $id_entreprise, $note);
        } else {
            $this->ajouter($id_utilisateur, $id_entreprise, $note);
        }
    }
}
?>