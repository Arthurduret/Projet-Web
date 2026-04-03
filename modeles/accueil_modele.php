<?php
class AccueilModele {

    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // -----------------------------------------------
    // Récupère 3 entreprises pour la page d'accueil
    // Ordre aléatoire pour varier l'affichage
    // -----------------------------------------------
    public function getEntreprisesAccueil(): array {
        $query = $this->pdo->query("
            SELECT 
                entreprise.*,
                COUNT(offre.id_offre) AS nb_offres
            FROM entreprise
            LEFT JOIN offre ON offre.id_entreprise = entreprise.id_entreprise
            GROUP BY entreprise.id_entreprise
            ORDER BY RAND()
            LIMIT 3
        ");

        return $query ? $query->fetchAll(PDO::FETCH_ASSOC) : [];
    }
}
?>