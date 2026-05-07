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

        $meta_title       = "Jobeo | Accueil — Trouvez votre stage";
        $meta_description = "Jobeo, la plateforme de recherche de stages pour étudiants CESI. Découvrez les offres de stage en région PACA.";
        $meta_keywords    = "stage CESI, offres de stage PACA, recherche stage étudiant, alternance Marseille";
        
        // 3. On charge la vue
        require __DIR__ . '/../vues/accueil_vue.php';
    }
}
?>
