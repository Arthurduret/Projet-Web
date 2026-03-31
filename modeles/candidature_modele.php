<?php
class CandidatureModele {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function creerCandidature(array $donnees): bool {
        $sql = "INSERT INTO candidatures (id_offre, id_etudiant, cv, lettre_motivation, date_candidature)
                VALUES (:id_offre, :id_etudiant, :cv, :lettre_motivation, :date_candidature)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($donnees);
    }

    public function getCandidaturesParEtudiant(int $id_etudiant): array {
        $sql = "SELECT c.*, o.titre, e.nom_entreprise
                FROM candidatures c
                JOIN offres_emplois o ON c.id_offre = o.id_offre
                JOIN entreprises e ON o.id_entreprise = e.id_entreprise
                WHERE c.id_etudiant = :id_etudiant
                ORDER BY c.date_candidature DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_etudiant' => $id_etudiant]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dejaCandidature(int $id_offre, int $id_etudiant): bool {
        $sql  = "SELECT COUNT(*) FROM candidatures 
                 WHERE id_offre = :id_offre AND id_etudiant = :id_etudiant";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_offre' => $id_offre, 'id_etudiant' => $id_etudiant]);
        return $stmt->fetchColumn() > 0;
    }
}
?>