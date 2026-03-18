<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helper/csrf.php';

// Récupère la page demandée dans l'URL, 'accueil' par défaut
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
            case 'create': $ctrl->create(); break;
            case 'store':  $ctrl->store();  break;
            default:       $ctrl->index();  break;
        }
        break;

    case 'offres_emplois':
        require_once __DIR__ . '/../controleurs/offres_emplois_controleur.php';
        $ctrl   = new OffresControleur($pdo);
        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'index':   $ctrl->index();  break;
            case 'show':    $ctrl->show();   break;
            case 'create':  $ctrl->create(); break;
            case 'store':   $ctrl->store();  break;
            case 'edit':    $ctrl->edit();   break;
            case 'update':  $ctrl->update(); break;
            case 'delete':  $ctrl->delete(); break;
            default:        $ctrl->index();  break;
        }
        break;

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

    case 'identifier':
        require __DIR__ . '/../vues/identifier_vue.php';
        break;    

    default:
        // Page inconnue → on redirige vers l'accueil
        header('Location: /public/index.php?page=accueil');
        exit;
}
?>