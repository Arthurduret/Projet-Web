<?php 


class EntrepriseModele {


    private $pdo; 

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    public function creerEntreprise($donnees) {
        $stmt = $this->pdo->prepare("
            INSERT INTO entreprise (nom, description, email, tel, image_logo, image_fond)
            VALUES (:nom, :description, :email, :tel, :image_logo, :image_fond)
        ");
        $stmt->execute($donnees);
        return $this->pdo->lastInsertId();
    }

    public function getEntreprises($tri = '') {
        $order = $this->getOrderBy($tri);
        $query = $this->pdo->query("
            SELECT entreprise.*,
                COUNT(DISTINCT offre.id_offre) AS nb_offres,
                COUNT(DISTINCT candidature.id_candidature) AS nb_candidatures,
                ROUND(AVG(evaluation.note), 1) AS moyenne_eval
            FROM entreprise
            LEFT JOIN offre ON offre.id_entreprise = entreprise.id_entreprise
            LEFT JOIN candidature ON candidature.id_offre = offre.id_offre
            LEFT JOIN evaluation ON evaluation.id_entreprise = entreprise.id_entreprise
            GROUP BY entreprise.id_entreprise
            ORDER BY $order
        ");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEntrepriseById($id) {
        $stmt = $this->pdo->prepare("
            SELECT entreprise.*, COUNT(offre.id_offre) AS nb_offres
            FROM entreprise
            LEFT JOIN offre ON offre.id_entreprise = entreprise.id_entreprise
            WHERE entreprise.id_entreprise = :id
            GROUP BY entreprise.id_entreprise
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function modifierEntreprise($id, $donnees) {
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
        $donnees['id'] = $id;
        $stmt->execute($donnees);
    }

    public function supprimerEntreprise($id) {
        $stmt = $this->pdo->prepare("SELECT id_offre FROM offre WHERE id_entreprise = :id");
        $stmt->execute([':id' => $id]);
        $offres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($offres as $offre) {
            $id_offre = $offre['id_offre'];

            $stmt = $this->pdo->prepare("DELETE FROM Requerir WHERE id_offre = :id");
            $stmt->execute([':id' => $id_offre]);

            $stmt = $this->pdo->prepare("DELETE FROM candidature WHERE id_offre = :id");
            $stmt->execute([':id' => $id_offre]);

            $stmt = $this->pdo->prepare("DELETE FROM liker WHERE id_offre = :id");
            $stmt->execute([':id' => $id_offre]);
        }

        $stmt = $this->pdo->prepare("DELETE FROM offre WHERE id_entreprise = :id");
        $stmt->execute([':id' => $id]);

        $stmt = $this->pdo->prepare("DELETE FROM evaluation WHERE id_entreprise = :id");
        $stmt->execute([':id' => $id]);

        $stmt = $this->pdo->prepare("DELETE FROM entreprise WHERE id_entreprise = :id");
        $stmt->execute([':id' => $id]);
    } 

    public function rechercherEntreprises($nom, $tri = '') {
        $order = $this->getOrderBy($tri);
        $stmt = $this->pdo->prepare("
            SELECT entreprise.*,
                COUNT(DISTINCT offre.id_offre) AS nb_offres,
                COUNT(DISTINCT candidature.id_candidature) AS nb_candidatures,
                ROUND(AVG(evaluation.note), 1) AS moyenne_eval
            FROM entreprise
            LEFT JOIN offre ON offre.id_entreprise = entreprise.id_entreprise
            LEFT JOIN candidature ON candidature.id_offre = offre.id_offre
            LEFT JOIN evaluation ON evaluation.id_entreprise = entreprise.id_entreprise
            WHERE entreprise.nom LIKE :nom
            GROUP BY entreprise.id_entreprise
            ORDER BY $order
        ");
        $stmt->execute([':nom' => '%' . $nom . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getOrderBy($tri) {
        return match($tri) {
            'nb_candidatures' => 'nb_candidatures DESC',
            'moyenne_eval'    => 'moyenne_eval DESC',
            'nb_offres'       => 'nb_offres DESC',
            default           => 'entreprise.nom ASC'
        };
    }
}
?>