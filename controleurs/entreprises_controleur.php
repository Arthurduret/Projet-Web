<?php
// Ce fichier a un seul rôle : faire le lien entre le modèle et la vue

// On importe le modèle pour pouvoir l'utiliser ici
require_once __DIR__ . '/../modeles/entreprises_modele.php';

class EntrepriseControleur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }    

    public function index() {
        // 1. On crée le modèle en lui passant la connexion BDD
        $model = new EntrepriseModele($this->pdo);

        // 2. On demande au modèle les données
        // $entreprises contiendra un tableau avec toutes les entreprises
        $entreprises = $model->getEntreprises();

        // 3. On charge la vue en lui transmettant les données
        // La vue pourra utiliser la variable $entreprises
        require __DIR__ . '/../vues/entreprises_vue.php';
    }
}

?>