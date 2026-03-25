<?php
class OffresModele {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Toutes les offres (déjà existant)
    public function getOffres() {
        $query = $this->pdo->query("
            SELECT offre.*, entreprise.nom AS nom_entreprise, entreprise.image_logo,
                   GROUP_CONCAT(competence.nom SEPARATOR ', ') AS competences
            FROM offre
            JOIN entreprise ON offre.id_entreprise = entreprise.id_entreprise
            LEFT JOIN Requerir ON offre.id_offre = Requerir.id_offre
            LEFT JOIN competence ON Requerir.id_competence = competence.id_competence
            GROUP BY offre.id_offre
            ORDER BY offre.date_offre DESC
        ");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Recherche (déjà existant)
    public function rechercherOffres($quoi, $ou) {
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

        $sql .= " GROUP BY offre.id_offre ORDER BY offre.date_offre DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------
    // UNE SEULE offre par id (pour la fiche et l'édition)
    // -----------------------------------------------
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

    // -----------------------------------------------
    // TOUTES les entreprises (pour le menu déroulant du formulaire)
    // -----------------------------------------------
    public function getEntreprises() {
        $query = $this->pdo->query("SELECT id_entreprise, nom FROM entreprise ORDER BY nom");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------
    // TOUTES les compétences (pour les cases à cocher)
    // -----------------------------------------------
    public function getCompetences() {
        try {
            $query = $this->pdo->query("SELECT * FROM competence ORDER BY nom");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            return [];
        }
    }

    // -----------------------------------------------
    // Compétences d'UNE offre (pour pré-cocher à l'édition)
    // -----------------------------------------------
    public function getCompetencesParOffre($id_offre) {
        $stmt = $this->pdo->prepare("
            SELECT id_competence FROM Requerir WHERE id_offre = :id
        ");
        $stmt->execute([':id' => $id_offre]);
        // Retourne un tableau simple d'ids : [1, 3, 5]
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'id_competence');
    }

    // -----------------------------------------------
    // CRÉER une offre + ses compétences
    // -----------------------------------------------
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

    // -----------------------------------------------
    // MODIFIER une offre + ses compétences
    // -----------------------------------------------
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

    // -----------------------------------------------
    // SUPPRIMER une offre
    // -----------------------------------------------
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

    // -----------------------------------------------
    // MÉTHODE PRIVÉE — sauvegarde les compétences d'une offre
    // $supprimer = true lors d'une modification
    // -----------------------------------------------
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
}
?>