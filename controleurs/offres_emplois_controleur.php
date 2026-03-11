<?php
require_once __DIR__ . '/../modeles/offres_emplois_modele.php';

class OffresControleur {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        $modele = new OffresModele($this->pdo);

        // Récupère les valeurs de la barre de recherche
        // Si rien n'est tapé, la variable sera vide ""
        $quoi = $_GET['quoi'] ?? '';
        $ou   = $_GET['ou']   ?? '';

        // Si une recherche est faite on filtre, sinon on prend tout
        if (!empty($quoi) || !empty($ou)) {
            $offres = $modele->rechercherOffres($quoi, $ou);
        } else {
            $offres = $modele->getOffres();
        }

        // Nombre total d'offres trouvées
        $nb_offres = count($offres);

        require __DIR__ . '/../vues/offres_emplois_vue.php';
    }
}
?>