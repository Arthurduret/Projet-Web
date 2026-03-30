<!-- Page : pour la wishlist, ajouter/retirer une offre des favoris. -->

<?php
require_once __DIR__ . '/../modeles/favoris_modele.php';

class FavorisControleur {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // -----------------------------------------------
    // Vérifie que l'utilisateur est connecté en tant qu'étudiant
    // -----------------------------------------------
    private function verifierEtudiant() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
            header('Location: /index.php?page=auth&action=connexion');
            exit;
        }
    }

    // -----------------------------------------------
    // PAGE FAVORIS — liste des offres sauvegardées
    // URL : ?page=favoris
    // -----------------------------------------------
    public function index() {
        $this->verifierEtudiant();

        $modele  = new FavorisModele($this->pdo);
        $favoris = $modele->getFavoris($_SESSION['id_utilisateur']);
        $nb_favoris = count($favoris);

        require __DIR__ . '/../vues/favoris_vue.php';
    }

    // -----------------------------------------------
    // TOGGLE FAVORI — ajoute ou retire selon l'état
    // URL : ?page=favoris&action=toggle&id=3 (POST)
    // -----------------------------------------------
    public function toggle() {
        $this->verifierEtudiant();

        $id_offre       = $_GET['id'] ?? null;
        $id_utilisateur = $_SESSION['id_utilisateur'];

        $modele = new FavorisModele($this->pdo);

        if ($modele->estFavori($id_utilisateur, $id_offre)) {
            $modele->retirer($id_utilisateur, $id_offre);
            $estFavori = false;
        } else {
            $modele->ajouter($id_utilisateur, $id_offre);
            $estFavori = true;
        }

        // Si requête AJAX → retourne JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['favori' => $estFavori]);
            exit;
        }

        // Sinon → redirection classique
        $retour = $_SERVER['HTTP_REFERER'] ?? '/index.php?page=favoris';
        header('Location: ' . $retour);
        exit;
    }
}
?>