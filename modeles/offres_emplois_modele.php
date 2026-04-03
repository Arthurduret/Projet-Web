<?php
class OffresModele {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // -----------------------------------------------
    // Récupère toutes les offres avec pagination (SFx7)
    // -----------------------------------------------
    public function getOffres(int $limite = 10, int $offset = 0): array {
        $stmt = $this->pdo->prepare("
            SELECT offre.*, entreprise.nom AS nom_entreprise, entreprise.image_logo,
                GROUP_CONCAT(competence.nom SEPARATOR ', ') AS competences
            FROM offre
            JOIN entreprise  ON offre.id_entreprise        = entreprise.id_entreprise
            LEFT JOIN Requerir   ON offre.id_offre         = Requerir.id_offre
            LEFT JOIN competence ON Requerir.id_competence = competence.id_competence
            GROUP BY offre.id_offre
            ORDER BY offre.date_offre DESC
            LIMIT :limite OFFSET :offset
        ");
        $stmt->bindValue(':limite',  $limite,  PDO::PARAM_INT);
        $stmt->bindValue(':offset',  $offset,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------
    // Recherche des offres par mots-clés (SFx7)
    // -----------------------------------------------
    public function rechercherOffres(string $quoi, string $ou, int $limite = 10, int $offset = 0): array {
        $sql    = "
            SELECT offre.*, entreprise.nom AS nom_entreprise, entreprise.image_logo,
                GROUP_CONCAT(competence.nom SEPARATOR ', ') AS competences
            FROM offre
            JOIN entreprise  ON offre.id_entreprise        = entreprise.id_entreprise
            LEFT JOIN Requerir   ON offre.id_offre         = Requerir.id_offre
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

        $stmt = $this->pdo->prepare($sql . " LIMIT :limite OFFSET :offset");
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------
    // Récupère une offre par son id (SFx7)
    // -----------------------------------------------
    public function getOffreById(int $id): array|false {
        $stmt = $this->pdo->prepare("
            SELECT offre.*, entreprise.nom AS nom_entreprise, entreprise.image_logo,
                   GROUP_CONCAT(competence.nom SEPARATOR ', ') AS competences
            FROM offre
            JOIN entreprise  ON offre.id_entreprise        = entreprise.id_entreprise
            LEFT JOIN Requerir   ON offre.id_offre         = Requerir.id_offre
            LEFT JOIN competence ON Requerir.id_competence = competence.id_competence
            WHERE offre.id_offre = :id
            GROUP BY offre.id_offre
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------
    // Récupère toutes les entreprises pour le select
    // -----------------------------------------------
    public function getEntreprises(): array {
        $query = $this->pdo->query("SELECT id_entreprise, nom FROM entreprise ORDER BY nom");
        return $query ? $query->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    // -----------------------------------------------
    // Récupère toutes les compétences pour le select
    // -----------------------------------------------
    public function getCompetences(): array {
        try {
            $query = $this->pdo->query("SELECT * FROM competence ORDER BY nom");
            return $query ? $query->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    // -----------------------------------------------
    // Récupère les compétences d'une offre
    // -----------------------------------------------
    public function getCompetencesParOffre(int $id_offre): array {
        $stmt = $this->pdo->prepare("
            SELECT id_competence FROM Requerir WHERE id_offre = :id
        ");
        $stmt->execute([':id' => $id_offre]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'id_competence');
    }

    // -----------------------------------------------
    // Crée une offre + ses compétences (SFx8)
    // -----------------------------------------------
    public function creerOffre(array $donnees, array $competences): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO offre (titre, description, salaire, duree, localisation, date_offre, id_entreprise)
            VALUES (:titre, :description, :salaire, :duree, :localisation, :date_offre, :id_entreprise)
        ");
        $stmt->execute($donnees);

        $id_offre = (int)$this->pdo->lastInsertId();
        $this->sauvegarderCompetences($id_offre, $competences);

        return $id_offre;
    }

    // -----------------------------------------------
    // Modifie une offre + ses compétences (SFx9)
    // -----------------------------------------------
    public function modifierOffre(int $id, array $donnees, array $competences): void {
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
        $donnees[':id_offre'] = $id;
        $stmt->execute($donnees);

        $this->sauvegarderCompetences($id, $competences, true);
    }

    // -----------------------------------------------
    // Supprime une offre et ses dépendances (SFx10)
    // -----------------------------------------------
    public function supprimerOffre(int $id): void {
        // Supprime les compétences liées
        $stmt = $this->pdo->prepare("DELETE FROM Requerir WHERE id_offre = :id");
        $stmt->execute([':id' => $id]);

        // Supprime les candidatures liées
        $stmt = $this->pdo->prepare("DELETE FROM candidature WHERE id_offre = :id");
        $stmt->execute([':id' => $id]);

        // Supprime les favoris liés
        $stmt = $this->pdo->prepare("DELETE FROM liker WHERE id_offre = :id");
        $stmt->execute([':id' => $id]);

        // Supprime l'offre elle-même
        $stmt = $this->pdo->prepare("DELETE FROM offre WHERE id_offre = :id");
        $stmt->execute([':id' => $id]);
    }

    // -----------------------------------------------
    // Sauvegarde les compétences d'une offre
    // -----------------------------------------------
    private function sauvegarderCompetences(int $id_offre, array $competences, bool $supprimer = false): void {
        if ($supprimer) {
            $stmt = $this->pdo->prepare("DELETE FROM Requerir WHERE id_offre = :id");
            $stmt->execute([':id' => $id_offre]);
        }

        if (empty($competences)) return;

        $stmt = $this->pdo->prepare("
            INSERT INTO Requerir (id_offre, id_competence)
            VALUES (:id_offre, :id_competence)
        ");
        foreach ($competences as $id_competence) {
            $stmt->execute([
                ':id_offre'      => $id_offre,
                ':id_competence' => (int)$id_competence
            ]);
        }
    }

    // -----------------------------------------------
    // Compte le total des offres (SFx27 pagination)
    // -----------------------------------------------
    public function compterOffres(): int {
        $query = $this->pdo->query("SELECT COUNT(*) FROM offre");
        return $query ? (int)$query->fetchColumn() : 0;
    }

    // -----------------------------------------------
    // Filtre les offres avec pagination (SFx7/SFx27)
    // -----------------------------------------------
    public function filtrerOffres(string $quoi, string $ou, array $filtres = [], int $limite = 10, int $offset = 0): array {
        [$sql, $params] = $this->construireRequete($quoi, $ou, $filtres);

        $sql .= $this->getOrderBy($filtres['f_tri'] ?? '');
        $sql .= " LIMIT :limite OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------
    // Compte les offres filtrées pour la pagination
    // -----------------------------------------------
    public function compterOffresFiltrees(string $quoi, string $ou, array $filtres = []): int {
        [$sql, $params] = $this->construireRequete($quoi, $ou, $filtres, false);

        $sql_count = "SELECT COUNT(*) FROM ($sql) AS sous_requete";

        $stmt = $this->pdo->prepare($sql_count);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    // -----------------------------------------------
    // Construit la requête SQL dynamiquement
    // -----------------------------------------------
    private function construireRequete(string $quoi, string $ou, array $filtres = [], bool $count = false): array {
        if ($count) {
            $sql = "SELECT COUNT(DISTINCT offre.id_offre) ";
        } else {
            $sql = "SELECT offre.*, entreprise.nom AS nom_entreprise, entreprise.image_logo,
                    GROUP_CONCAT(DISTINCT competence.nom SEPARATOR ', ') AS competences,
                    COUNT(DISTINCT candidature.id_candidature) AS nb_candidatures ";
        }

        $sql .= "
            FROM offre
            JOIN entreprise  ON offre.id_entreprise        = entreprise.id_entreprise
            LEFT JOIN Requerir   ON offre.id_offre         = Requerir.id_offre
            LEFT JOIN competence ON Requerir.id_competence = competence.id_competence
            LEFT JOIN candidature ON candidature.id_offre  = offre.id_offre
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
        if (!empty($filtres['f_entreprise'])) {
            $sql .= " AND entreprise.nom LIKE :f_entreprise";
            $params[':f_entreprise'] = '%' . $filtres['f_entreprise'] . '%';
        }
        if (!empty($filtres['f_titre'])) {
            $sql .= " AND offre.titre LIKE :f_titre";
            $params[':f_titre'] = '%' . $filtres['f_titre'] . '%';
        }
        if (!empty($filtres['f_competence'])) {
            $sql .= " AND competence.nom LIKE :f_competence";
            $params[':f_competence'] = '%' . $filtres['f_competence'] . '%';
        }
        if (!empty($filtres['f_salaire_min'])) {
            $sql .= " AND offre.salaire >= :f_salaire_min";
            $params[':f_salaire_min'] = (float)$filtres['f_salaire_min'];
        }
        if (!empty($filtres['f_salaire_max'])) {
            $sql .= " AND offre.salaire <= :f_salaire_max";
            $params[':f_salaire_max'] = (float)$filtres['f_salaire_max'];
        }
        if (!empty($filtres['f_date'])) {
            $sql .= " AND offre.date_offre >= :f_date";
            $params[':f_date'] = $filtres['f_date'];
        }

        $sql .= " GROUP BY offre.id_offre";

        if (!empty($filtres['f_candidatures_min'])) {
            $sql .= " HAVING COUNT(DISTINCT candidature.id_candidature) >= :f_candidatures_min";
            $params[':f_candidatures_min'] = (int)$filtres['f_candidatures_min'];
        }

        return [$sql, $params];
    }

    // -----------------------------------------------
    // Retourne la clause ORDER BY selon le tri choisi
    // -----------------------------------------------
    private function getOrderBy(string $tri): string {
        return match($tri) {
            'date_asc'          => ' ORDER BY offre.date_offre ASC',
            'salaire_desc'      => ' ORDER BY offre.salaire DESC',
            'salaire_asc'       => ' ORDER BY offre.salaire ASC',
            'candidatures_desc' => ' ORDER BY nb_candidatures DESC',
            default             => ' ORDER BY offre.date_offre DESC',
        };
    }
}
?>