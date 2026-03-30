<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helper/csrf.php';

$page = $_GET['page'] ?? 'accueil';

switch ($page) {

    case 'accueil':
        require_once __DIR__ . '/../controleurs/accueil_controleur.php';
        $ctrl = new AccueilControleur($pdo);
        $ctrl->index();
        break;

    case 'entreprises':
        require_once __DIR__ . '/../controleurs/entreprises_controleur.php';
        $ctrl   = new EntrepriseControleur($pdo);
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'index':  $ctrl->index();  break;
            case 'show':   $ctrl->show();   break; 
            case 'create': $ctrl->create(); break;
            case 'store':  $ctrl->store();  break;
            case 'edit':   $ctrl->edit();   break;
            case 'update': $ctrl->update(); break;
            case 'delete': $ctrl->delete(); break;
            default:       $ctrl->index();  break;
        }
        break;

    case 'offres_emplois':
        require_once __DIR__ . '/../controleurs/offres_emplois_controleur.php';
        $ctrl   = new OffresControleur($pdo);
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'index':  $ctrl->index();  break;
            case 'show':   $ctrl->show();   break;
            case 'create': $ctrl->create(); break;
            case 'store':  $ctrl->store();  break;
            case 'edit':   $ctrl->edit();   break;
            case 'update': $ctrl->update(); break;
            case 'delete': $ctrl->delete(); break;
            default:       $ctrl->index();  break;
        }
        break;

    case 'auth':
        require_once __DIR__ . '/../controleurs/auth_controleur.php';
        $ctrl   = new AuthControleur($pdo);
        $action = $_GET['action'] ?? 'connexion';
        switch ($action) {
            case 'identifier':  $ctrl->identifier();  break;
            case 'check':       $ctrl->check();       break;
            case 'connexion':   $ctrl->connexion();   break;
            case 'login':       $ctrl->login();       break; 
            case 'inscription': $ctrl->inscription(); break;
            case 'register':    $ctrl->register();    break; 
            case 'deconnexion': $ctrl->deconnexion(); break;
            default:            $ctrl->connexion();   break;
        }
        break;

    // FAVORIS (wishlist étudiant)
    case 'favoris':
        require_once __DIR__ . '/../controleurs/favoris_controleur.php';
        $ctrl   = new FavorisControleur($pdo);
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'index':    $ctrl->index();    break;
            case 'ajouter':  $ctrl->ajouter();  break; // POST
            case 'retirer':  $ctrl->retirer();  break; // POST
            default:         $ctrl->index();    break;
        }
        break;

    // ──────────────────────────────────────────
    // CANDIDATURES (postuler à une offre)
    // ──────────────────────────────────────────
    // case 'candidatures':
    //     require_once __DIR__ . '/../controleurs/candidature_controleur.php';
    //     $ctrl   = new CandidatureControleur($pdo);
    //     $action = $_GET['action'] ?? 'index';
    //     switch ($action) {
    //         case 'index':    $ctrl->index();    break; // mes candidatures
    //         case 'postuler': $ctrl->postuler(); break; // POST formulaire
    //         default:         $ctrl->index();    break;
    //     }
    //     break;

    // ──────────────────────────────────────────
    // ADMIN / PILOTE
    // ──────────────────────────────────────────
    // case 'admin':
    //     require_once __DIR__ . '/../controleurs/admin_controleur.php';
    //     $ctrl   = new AdminControleur($pdo);
    //     $action = $_GET['action'] ?? 'dashboard';
    //     switch ($action) {
    //         case 'dashboard':         $ctrl->dashboard();        break;
    //         case 'utilisateurs':      $ctrl->utilisateurs();     break;
    //         case 'creer_utilisateur': $ctrl->creerUtilisateur(); break;
    //         case 'suppr_utilisateur': $ctrl->supprimerUtilisateur(); break;
    //         default:                  $ctrl->dashboard();        break;
    //     }
    //     break;

    // ──────────────────────────────────────────
    // PAGES STATIQUES
    // ──────────────────────────────────────────
    case 'a_propos':
        require_once __DIR__ . '/../vues/a_propos_vue.php';
        break;

    case 'mentions_legales':
        require_once __DIR__ . '/../vues/pages_footer/mentions_legales.php';
        break;

    case 'cgu':
        require_once __DIR__ . '/../vues/pages_footer/cgu.php';
        break;

    case 'cookies':
        require_once __DIR__ . '/../vues/pages_footer/cookies.php';
        break;

    case 'entreprise_form':
        require_once __DIR__ . '/../vues/entreprise_form_vue.php';
        break;

    default:
        header('Location: /public/public/index.php?page=accueil');
        exit;
}
?>