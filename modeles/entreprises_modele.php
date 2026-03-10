<?php 
// Ce fichier a UN seul rôle : parler à la base de données

class EntrepriseModele {

    // On stocke la connexion PDO dans la classe
    private $pdo; 

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    public function getEntreprises() {
        // $query contient un résultat "brut", PHP ne sait pas encore quoi en faire
        $query = $this->pdo->query("SELECT * FROM entreprises");
        // fetchAll() transforme ce résultat en tableau PHP utilisable
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>