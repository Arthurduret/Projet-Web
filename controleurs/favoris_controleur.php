<?php
require_once __DIR__ . '/../modeles/favoris_modele.php';

class FavorisControleur {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    private function verifierEtudiant() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
            header('Location: /index.php?page=auth&action=identifier');
            exit;
        }
    }


    public function index() {
        $this->verifierEtudiant();

        $modele     = new FavorisModele($this->pdo);
        $favoris    = $modele->getFavoris($_SESSION['user']['id_utilisateur']);
        $nb_favoris = count($favoris);

        $meta_title       = "Jobeo | Mes Favoris";
        $meta_description = "Retrouvez toutes les offres de stage que vous avez ajoutées à votre wishlist.";
        $meta_keywords    = "favoris, wishlist stage, offres sauvegardées";

        require __DIR__ . '/../vues/favoris_vue.php';
    }


    public function toggle() {
        $this->verifierEtudiant();

        $id_offre       = $_GET['id'] ?? null;
        $id_utilisateur = $_SESSION['user']['id_utilisateur'];

        $modele = new FavorisModele($this->pdo);

        if ($modele->estFavori($id_utilisateur, $id_offre)) {
            $modele->retirer($id_utilisateur, $id_offre);
            $estFavori = false;
        } else {
            $modele->ajouter($id_utilisateur, $id_offre);
            $estFavori = true;
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['favori' => $estFavori]);
            exit;
        }

        $retour = $_SERVER['HTTP_REFERER'] ?? '/index.php?page=favoris';
        header('Location: ' . $retour);
        exit;
    }
}
?>