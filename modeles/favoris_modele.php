<?php
class FavorisModele {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // -----------------------------------------------
    // Récupère toutes les offres favorites (SFx23)
    // -----------------------------------------------
    public function getFavoris(int $id_utilisateur): array {
        $stmt = $this->pdo->prepare("
            SELECT offre.*, entreprise.nom AS nom_entreprise, entreprise.image_logo,
                   GROUP_CONCAT(competence.nom SEPARATOR ', ') AS competences
            FROM liker
            JOIN offre       ON liker.id_offre             = offre.id_offre
            JOIN entreprise  ON offre.id_entreprise        = entreprise.id_entreprise
            LEFT JOIN Requerir   ON offre.id_offre         = Requerir.id_offre
            LEFT JOIN competence ON Requerir.id_competence = competence.id_competence
            WHERE liker.id_utilisateur = :id
            GROUP BY offre.id_offre
            ORDER BY offre.date_offre DESC
        ");
        $stmt->execute([':id' => $id_utilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------
    // Vérifie si une offre est en favori (SFx23)
    // -----------------------------------------------
    public function estFavori(int $id_utilisateur, int $id_offre): bool {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM liker
            WHERE id_utilisateur = :id_utilisateur
            AND id_offre         = :id_offre
        ");
        $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id_offre'       => $id_offre
        ]);
        return $stmt->fetchColumn() > 0;
    }

    // -----------------------------------------------
    // Ajoute une offre aux favoris (SFx24)
    // -----------------------------------------------
    public function ajouter(int $id_utilisateur, int $id_offre): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO liker (id_utilisateur, id_offre)
            VALUES (:id_utilisateur, :id_offre)
        ");
        $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id_offre'       => $id_offre
        ]);
    }

    // -----------------------------------------------
    // Retire une offre des favoris (SFx25)
    // -----------------------------------------------
    public function retirer(int $id_utilisateur, int $id_offre): void {
        $stmt = $this->pdo->prepare("
            DELETE FROM liker
            WHERE id_utilisateur = :id_utilisateur
            AND id_offre         = :id_offre
        ");
        $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id_offre'       => $id_offre
        ]);
    }
}
?>