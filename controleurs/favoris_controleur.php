<?php
require_once __DIR__ . '/../modeles/favoris_modele.php';

class FavorisControleur {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // -----------------------------------------------
    // Vérifie que l'utilisateur est étudiant connecté
    // -----------------------------------------------
    private function verifierEtudiant(): void {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
            header('Location: /index.php?page=auth&action=connexion');
            exit;
        }
    }

    // -----------------------------------------------
    // LISTE DES FAVORIS — étudiant seulement (SFx23)
    // -----------------------------------------------
    public function index(): void {
        $this->verifierEtudiant();

        $modele     = new FavorisModele($this->pdo);
        $favoris    = $modele->getFavoris((int)$_SESSION['user']['id_utilisateur']);
        $nb_favoris = count($favoris);

        $meta_title       = "Jobeo | Mes Favoris";
        $meta_description = "Retrouvez toutes les offres de stage que vous avez ajoutées à votre wishlist.";
        $meta_keywords    = "favoris, wishlist stage, offres sauvegardées";

        require __DIR__ . '/../vues/favoris_vue.php';
    }

    // -----------------------------------------------
    // TOGGLE FAVORI — ajoute ou retire (SFx24/SFx25)
    // Supporte AJAX et redirection classique
    // -----------------------------------------------
    public function toggle(): void {
        $this->verifierEtudiant();

        $id_offre       = (int)($_GET['id'] ?? 0);
        $id_utilisateur = (int)$_SESSION['user']['id_utilisateur'];

        // Validation id offre
        if (!$id_offre) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['succes' => false, 'message' => 'Offre invalide']);
                exit;
            }
            header('Location: /index.php?page=offres_emplois');
            exit;
        }

        $modele = new FavorisModele($this->pdo);

        if ($modele->estFavori($id_utilisateur, $id_offre)) {
            $modele->retirer($id_utilisateur, $id_offre);
            $estFavori = false;
        } else {
            $modele->ajouter($id_utilisateur, $id_offre);
            $estFavori = true;
        }

        // Réponse AJAX
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['favori' => $estFavori]);
            exit;
        }

        // Redirection classique
        $retour = $_SERVER['HTTP_REFERER'] ?? '/index.php?page=favoris';
        header('Location: ' . $retour);
        exit;
    }
}
?>