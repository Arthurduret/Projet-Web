<?php
class EntrepriseModele {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // -----------------------------------------------
    // Crée une entreprise (SFx3)
    // -----------------------------------------------
    public function creerEntreprise(array $donnees): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO entreprise (nom, description, email, tel, image_logo, image_fond)
            VALUES (:nom, :description, :email, :tel, :image_logo, :image_fond)
        ");
        $stmt->execute($donnees);
        return (int)$this->pdo->lastInsertId();
    }

    // -----------------------------------------------
    // Récupère toutes les entreprises avec stats (SFx2)
    // -----------------------------------------------
    public function getEntreprises(string $tri = ''): array {
        $order = $this->getOrderBy($tri);
        $query = $this->pdo->query("
            SELECT entreprise.*,
                COUNT(DISTINCT offre.id_offre)            AS nb_offres,
                COUNT(DISTINCT candidature.id_candidature) AS nb_candidatures,
                ROUND(AVG(evaluation.note), 1)            AS moyenne_eval
            FROM entreprise
            LEFT JOIN offre       ON offre.id_entreprise       = entreprise.id_entreprise
            LEFT JOIN candidature ON candidature.id_offre      = offre.id_offre
            LEFT JOIN evaluation  ON evaluation.id_entreprise  = entreprise.id_entreprise
            GROUP BY entreprise.id_entreprise
            ORDER BY $order
        ");
        return $query ? $query->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    // -----------------------------------------------
    // Récupère une entreprise par son id (SFx2)
    // -----------------------------------------------
    public function getEntrepriseById(int $id): array|false {
        $stmt = $this->pdo->prepare("
            SELECT entreprise.*, COUNT(offre.id_offre) AS nb_offres
            FROM entreprise
            LEFT JOIN offre ON offre.id_entreprise = entreprise.id_entreprise
            WHERE entreprise.id_entreprise = :id
            GROUP BY entreprise.id_entreprise
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------
    // Modifie une entreprise (SFx4)
    // -----------------------------------------------
    public function modifierEntreprise(int $id, array $donnees): void {
        $stmt = $this->pdo->prepare("
            UPDATE entreprise
            SET nom         = :nom,
                description = :description,
                email       = :email,
                tel         = :tel,
                image_logo  = :image_logo,
                image_fond  = :image_fond
            WHERE id_entreprise = :id
        ");
        $donnees[':id'] = $id;
        $stmt->execute($donnees);
    }

    // -----------------------------------------------
    // Supprime une entreprise et toutes ses dépendances (SFx6)
    // Ordre : compétences → candidatures → favoris → offres → évaluations → entreprise
    // -----------------------------------------------
    public function supprimerEntreprise(int $id): void {
        // Récupère toutes les offres liées
        $stmt = $this->pdo->prepare("SELECT id_offre FROM offre WHERE id_entreprise = :id");
        $stmt->execute([':id' => $id]);
        $offres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($offres as $offre) {
            $id_offre = $offre['id_offre'];

            // Supprime les compétences liées
            $stmt = $this->pdo->prepare("DELETE FROM Requerir WHERE id_offre = :id");
            $stmt->execute([':id' => $id_offre]);

            // Supprime les candidatures liées
            $stmt = $this->pdo->prepare("DELETE FROM candidature WHERE id_offre = :id");
            $stmt->execute([':id' => $id_offre]);

            // Supprime les favoris liés
            $stmt = $this->pdo->prepare("DELETE FROM liker WHERE id_offre = :id");
            $stmt->execute([':id' => $id_offre]);
        }

        // Supprime toutes les offres de l'entreprise
        $stmt = $this->pdo->prepare("DELETE FROM offre WHERE id_entreprise = :id");
        $stmt->execute([':id' => $id]);

        // Supprime les évaluations de l'entreprise
        $stmt = $this->pdo->prepare("DELETE FROM evaluation WHERE id_entreprise = :id");
        $stmt->execute([':id' => $id]);

        // Supprime l'entreprise elle-même
        $stmt = $this->pdo->prepare("DELETE FROM entreprise WHERE id_entreprise = :id");
        $stmt->execute([':id' => $id]);
    }

    // -----------------------------------------------
    // Recherche des entreprises par nom (SFx2)
    // -----------------------------------------------
    public function rechercherEntreprises(string $nom, string $tri = ''): array {
        $order = $this->getOrderBy($tri);
        $stmt  = $this->pdo->prepare("
            SELECT entreprise.*,
                COUNT(DISTINCT offre.id_offre)            AS nb_offres,
                COUNT(DISTINCT candidature.id_candidature) AS nb_candidatures,
                ROUND(AVG(evaluation.note), 1)            AS moyenne_eval
            FROM entreprise
            LEFT JOIN offre       ON offre.id_entreprise      = entreprise.id_entreprise
            LEFT JOIN candidature ON candidature.id_offre     = offre.id_offre
            LEFT JOIN evaluation  ON evaluation.id_entreprise = entreprise.id_entreprise
            WHERE entreprise.nom LIKE :nom
            GROUP BY entreprise.id_entreprise
            ORDER BY $order
        ");
        $stmt->execute([':nom' => '%' . $nom . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------
    // Retourne la clause ORDER BY selon le tri choisi
    // -----------------------------------------------
    private function getOrderBy(string $tri): string {
        return match($tri) {
            'nb_candidatures' => 'nb_candidatures DESC',
            'moyenne_eval'    => 'moyenne_eval DESC',
            'nb_offres'       => 'nb_offres DESC',
            default           => 'entreprise.nom ASC'
        };
    }
}
?>