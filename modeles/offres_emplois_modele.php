<?php
class OffresModele {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Toutes les offres
    public function getOffres($limite = 10, $offset = 0) {
        $query = $this->pdo->query("
            SELECT offre.*, entreprise.nom AS nom_entreprise, entreprise.image_logo,
                GROUP_CONCAT(competence.nom SEPARATOR ', ') AS competences
            FROM offre
            JOIN entreprise ON offre.id_entreprise = entreprise.id_entreprise
            LEFT JOIN Requerir ON offre.id_offre = Requerir.id_offre
            LEFT JOIN competence ON Requerir.id_competence = competence.id_competence
            GROUP BY offre.id_offre
            ORDER BY offre.date_offre DESC
            LIMIT $limite OFFSET $offset
        ");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Recherche
    public function rechercherOffres($quoi, $ou, $limite = 10, $offset = 0) {
        $sql = "
            SELECT offre.*, entreprise.nom AS nom_entreprise, entreprise.image_logo,
                GROUP_CONCAT(competence.nom SEPARATOR ', ') AS competences
            FROM offre
            JOIN entreprise ON offre.id_entreprise = entreprise.id_entreprise
            LEFT JOIN Requerir ON offre.id_offre = Requerir.id_offre
            LEFT JOIN competence ON Requerir.id_competence = competence.id_competence
            WHERE 1=1
        ";
        $params = [];

        if (!empty($quoi)) {
            $sql .= " AND (offre.titre LIKE :quoi OR offre.description LIKE :quoi
                    OR entreprise.nom LIKE :quoi OR competence.nom LIKE :quoi)";
            $params[':quoi'] = '%' . $quoi . '%';
        }
        if (!empty($ou)) {
            $sql .= " AND offre.localisation LIKE :ou";
            $params[':ou'] = '%' . $ou . '%';
        }

        $sql .= " GROUP BY offre.id_offre ORDER BY offre.date_offre DESC LIMIT $limite OFFSET $offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Une seule offre par id
    public function getOffreById($id) {
        $stmt = $this->pdo->prepare("
            SELECT offre.*, entreprise.nom AS nom_entreprise, entreprise.image_logo,
                   GROUP_CONCAT(competence.nom SEPARATOR ', ') AS competences
            FROM offre
            JOIN entreprise ON offre.id_entreprise = entreprise.id_entreprise
            LEFT JOIN Requerir ON offre.id_offre = Requerir.id_offre
            LEFT JOIN competence ON Requerir.id_competence = competence.id_competence
            WHERE offre.id_offre = :id
            GROUP BY offre.id_offre
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
        // fetch() au lieu de fetchAll() → retourne UNE seule ligne
    }

    // Toutes les entreprises
    public function getEntreprises() {
        $query = $this->pdo->query("SELECT id_entreprise, nom FROM entreprise ORDER BY nom");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Toutes les compétences
    public function getCompetences() {
        try {
            $query = $this->pdo->query("SELECT * FROM competence ORDER BY nom");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            return [];
        }
    }

    // Compétences d'une offre
    public function getCompetencesParOffre($id_offre) {
        $stmt = $this->pdo->prepare("
            SELECT id_competence FROM Requerir WHERE id_offre = :id
        ");
        $stmt->execute([':id' => $id_offre]);
        // Retourne un tableau simple d'ids : [1, 3, 5]
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'id_competence');
    }

    // Créer une offre + ses compétences
    public function creerOffre($donnees, $competences) {
        // 1. Insère l'offre
        $stmt = $this->pdo->prepare("
            INSERT INTO offre (titre, description, salaire, duree, localisation, date_offre, id_entreprise)
            VALUES (:titre, :description, :salaire, :duree, :localisation, :date_offre, :id_entreprise)
        ");
        $stmt->execute($donnees);

        // 2. Récupère l'id de l'offre qu'on vient d'insérer
        $id_offre = $this->pdo->lastInsertId();

        // 3. Insère les compétences dans la table Requerir
        $this->sauvegarderCompetences($id_offre, $competences);

        return $id_offre;
    }

    // Modifier une offre + ses compétences
    public function modifierOffre($id, $donnees, $competences) {
        // 1. Met à jour l'offre
        $stmt = $this->pdo->prepare("
            UPDATE offre SET
                titre         = :titre,
                description   = :description,
                salaire       = :salaire,
                duree         = :duree,
                localisation  = :localisation,
                date_offre    = :date_offre,
                id_entreprise = :id_entreprise
            WHERE id_offre = :id_offre
        ");
        $donnees['id_offre'] = $id;
        $stmt->execute($donnees);

        // 2. Supprime les anciennes compétences
        // puis réinsère les nouvelles (plus simple que de comparer)
        $this->sauvegarderCompetences($id, $competences, true);
    }

    // Supprimer une offre
    public function supprimerOffre($id) {
        // 1. Supprime d'abord les compétences liées (clé étrangère)
        $stmt = $this->pdo->prepare("DELETE FROM Requerir WHERE id_offre = :id");
        $stmt->execute([':id' => $id]);

        // 2. Supprime les candidatures liées
        $stmt = $this->pdo->prepare("DELETE FROM candidature WHERE id_offre = :id");
        $stmt->execute([':id' => $id]);

        // 3. Supprime l'offre elle-même
        $stmt = $this->pdo->prepare("DELETE FROM offre WHERE id_offre = :id");
        $stmt->execute([':id' => $id]);
    }

    // Sauvegarde les compétences d'une offre
    private function sauvegarderCompetences($id_offre, $competences, $supprimer = false) {
        if ($supprimer) {
            $stmt = $this->pdo->prepare("DELETE FROM Requerir WHERE id_offre = :id");
            $stmt->execute([':id' => $id_offre]);
        }

        // Insère chaque compétence cochée
        $stmt = $this->pdo->prepare("INSERT INTO Requerir (id_offre, id_competence) VALUES (:id_offre, :id_competence)");
        foreach ($competences as $id_competence) {
            $stmt->execute([
                ':id_offre'      => $id_offre,
                ':id_competence' => $id_competence
            ]);
        }
    }

    // Compte le total des offres pour la pagination
    public function compterOffres() {
        $query = $this->pdo->query("SELECT COUNT(*) FROM offre");
        return $query->fetchColumn();
    }

    // Compte le total des offres filtrées
    public function compterOffresFiltrees($quoi, $ou) {
        $sql = "
            SELECT COUNT(DISTINCT offre.id_offre)
            FROM offre
            JOIN entreprise ON offre.id_entreprise = entreprise.id_entreprise
            LEFT JOIN Requerir ON offre.id_offre = Requerir.id_offre
            LEFT JOIN competence ON Requerir.id_competence = competence.id_competence
            WHERE 1=1
        ";
        $params = [];

        if (!empty($quoi)) {
            $sql .= " AND (offre.titre LIKE :quoi OR offre.description LIKE :quoi
                    OR entreprise.nom LIKE :quoi OR competence.nom LIKE :quoi)";
            $params[':quoi'] = '%' . $quoi . '%';
        }
        if (!empty($ou)) {
            $sql .= " AND offre.localisation LIKE :ou";
            $params[':ou'] = '%' . $ou . '%';
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
}
?>