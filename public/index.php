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
            // case 'identifier':  $ctrl->identifier();  break;
            // case 'check':       $ctrl->check();       break;
            case 'connexion':   $ctrl->connexion();   break;
            case 'login':       $ctrl->login();       break; 
            case 'inscription': $ctrl->inscription(); break;
            case 'register':    $ctrl->register();    break; 
            case 'deconnexion': $ctrl->deconnexion(); break;
            default:            $ctrl->connexion();   break;
        }
        break;


        case 'mon_compte':
        require_once __DIR__ . '/../controleurs/auth_controleur.php';
        $ctrl = new AuthControleur($pdo);
        $ctrl->monCompte();
        break;


    case 'favoris':
        require_once __DIR__ . '/../controleurs/favoris_controleur.php';
        $ctrl   = new FavorisControleur($pdo);
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'index':    $ctrl->index();    break;
            case 'toggle':   $ctrl->toggle();   break; 
            default:         $ctrl->index();    break;
        }
        break;
        
    case 'candidature':
    require_once __DIR__ . '/../controleurs/candidature_controleur.php';
    $ctrl   = new CandidatureControleur($pdo);
    $action = $_GET['action'] ?? 'create';
    switch ($action) {
        case 'create': $ctrl->create(); break;
        case 'store':  $ctrl->store();  break;
        case 'index':     $ctrl->index();            break;
        case 'pilote':    $ctrl->candidaturesPilote(); break;
        default:       $ctrl->create(); break;
    }
    break;

    case 'etudiants':
    require_once __DIR__ . '/../controleurs/etudiants_controleur.php';
    $ctrl   = new EtudiantsControleur($pdo);
    $action = $_GET['action'] ?? 'index';
    switch ($action) {
        case 'index':  $ctrl->index();  break;
        case 'edit':   $ctrl->edit();   break;
        case 'update': $ctrl->update(); break;
        case 'delete': $ctrl->delete(); break;
        default:       $ctrl->index();  break;
    }
    break;

    case 'pilotes':
    require_once __DIR__ . '/../controleurs/pilotes_controleur.php';
    $ctrl   = new PilotesControleur($pdo);
    $action = $_GET['action'] ?? 'index';
    switch ($action) {
        case 'index':  $ctrl->index();  break;
        case 'edit':   $ctrl->edit();   break;
        case 'update': $ctrl->update(); break;
        case 'delete': $ctrl->delete(); break;
        default:       $ctrl->index();  break;
    }
    break;

    case 'evaluation':
        require_once __DIR__ . '/../controleurs/evaluation_controleur.php';
        $ctrl = new EvaluationControleur($pdo);
        $ctrl->noter();
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

    default:
        header('Location: /index.php?page=accueil');
        exit;
}
?>