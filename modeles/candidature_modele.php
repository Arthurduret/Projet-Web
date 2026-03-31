<?php
class CandidatureModele {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

  public function creerCandidature(array $donnees): bool {
    $sql = "INSERT INTO candidature (id_offre, id_utilisateur, cv, lettre_motivation)
            VALUES (:id_offre, :id_utilisateur, :cv, :lettre_motivation)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($donnees); 
}

    public function getCandidaturesParUtilisateur(int $id_utilisateur): array {
        $sql = "SELECT c.*, o.titre, e.nom AS nom_entreprise
                FROM candidature c
                JOIN offre o          ON c.id_offre       = o.id_offre
                JOIN entreprise e     ON o.id_entreprise  = e.id_entreprise
                WHERE c.id_utilisateur = :id_utilisateur";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_utilisateur' => $id_utilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dejaCandidature(int $id_offre, int $id_utilisateur): bool {
        $sql  = "SELECT COUNT(*) FROM candidature 
                 WHERE id_offre = :id_offre AND id_utilisateur = :id_utilisateur";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_offre'       => $id_offre,
            'id_utilisateur' => $id_utilisateur,
        ]);
        return $stmt->fetchColumn() > 0;
    }
}
?>