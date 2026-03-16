<?php 
// Ce fichier a UN seul rôle : parler à la base de données

class EntrepriseModele {

    // On stocke la connexion PDO dans la classe
    private $pdo; 

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    public function creerEntreprise($donnees) {
        $stmt = $this->pdo->prepare("
            INSERT INTO entreprise (nom, description, image_logo, image_fond)
            VALUES (:nom, :description, :image_logo, :image_fond)
        ");
        $stmt->execute($donnees);
        return $this->pdo->lastInsertId();
    }

    public function getEntreprises() {
        $query = $this->pdo->query("
            SELECT entreprise.*, COUNT(offre.id_offre) AS nb_offres
            FROM entreprise
            LEFT JOIN offre ON offre.id_entreprise = entreprise.id_entreprise
            GROUP BY entreprise.id_entreprise
            ORDER BY entreprise.nom
        ");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>