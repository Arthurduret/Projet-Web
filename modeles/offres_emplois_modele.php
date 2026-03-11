<?php
class OffresModele {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupère toutes les offres avec le nom et logo de l'entreprise
    public function getOffres() {
        $query = $this->pdo->query("
            SELECT 
                offres.*,
                entreprises.nom AS nom_entreprise,
                entreprises.image_logo
            FROM offres
            JOIN entreprises ON offres.id_entreprise = entreprises.id
            ORDER BY offres.date_offre DESC
        ");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Recherche des offres selon un mot clé et/ou une localisation
    public function rechercherOffres($quoi, $ou) {
        $sql = "
            SELECT 
                offres.*,
                entreprises.nom AS nom_entreprise,
                entreprises.image_logo
            FROM offres
            JOIN entreprises ON offres.id_entreprise = entreprises.id
            WHERE 1=1
        ";

        $params = [];

        // Si l'utilisateur a tapé quelque chose dans "Quoi"
        if (!empty($quoi)) {
            $sql .= " AND (offres.titre LIKE :quoi 
                      OR offres.competences LIKE :quoi 
                      OR offres.description LIKE :quoi)";
            $params[':quoi'] = '%' . $quoi . '%';
            // % = n'importe quels caractères avant/après le mot
        }

        // Si l'utilisateur a tapé quelque chose dans "Où"
        if (!empty($ou)) {
            $sql .= " AND offres.localisation LIKE :ou";
            $params[':ou'] = '%' . $ou . '%';
        }

        $sql .= " ORDER BY offres.date_offre DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>