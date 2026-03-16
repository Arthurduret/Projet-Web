<?php
class AccueilModele {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupère seulement 3 entreprises pour la page d'accueil
    public function getEntreprisesAccueil() {
        $query = $this->pdo->query("
            SELECT 
                entreprise.*,
                COUNT(offre.id_offre) AS nb_offres
            FROM entreprise
            LEFT JOIN offre ON offre.id_entreprise = entreprise.id_entreprise
            GROUP BY entreprise.id_entreprise
            LIMIT 3
        ");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>