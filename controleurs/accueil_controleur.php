<?php
require_once __DIR__ . '/../modeles/accueil_modele.php';

class AccueilControleur {
    
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // 1. On crée le modèle
        $modele = new AccueilModele($this->pdo);

        // 2. On récupère les 3 entreprises
        $entreprises_accueil = $modele->getEntreprisesAccueil();

        // 3. On charge la vue
        require __DIR__ . '/../vues/accueil_vue.php';
    }
}
?>