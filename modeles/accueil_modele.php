<?php
class AccueilModele {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupère seulement 3 entreprises pour la page d'accueil
    public function getEntreprisesAccueil() {
        $query = $this->pdo->query("SELECT * FROM entreprises LIMIT 3");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>