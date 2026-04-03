<?php
// ===============================================
// Point d'entrée unique — Routeur principal
// ===============================================

// Affichage des erreurs en développement
// ⚠️ À désactiver en production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Session sécurisée
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helper/csrf.php';

// Récupère la page demandée — accueil par défaut
$page = $_GET['page'] ?? 'accueil';

// Whitelist des pages autorisées — sécurité contre les inclusions arbitraires
$pagesAutorisees = [
    'accueil', 'entreprises', 'offres_emplois', 'auth', 'mon_compte',
    'favoris', 'candidature', 'etudiants', 'pilotes', 'evaluation',
    'a_propos', 'mentions_legales', 'cgu', 'cookies'
];

if (!in_array($page, $pagesAutorisees)) {
    header('Location: /index.php?page=accueil');
    exit;
}

switch ($page) {

    // -----------------------------------------------
    // ACCUEIL — public
    // -----------------------------------------------
    case 'accueil':
        require_once __DIR__ . '/../controleurs/accueil_controleur.php';
        $ctrl = new AccueilControleur($pdo);
        $ctrl->index();
        break;

    // -----------------------------------------------
    // ENTREPRISES — public en lecture, admin/pilote en écriture
    // -----------------------------------------------
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

    // -----------------------------------------------
    // OFFRES — public en lecture, admin/pilote en écriture
    // -----------------------------------------------
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

    // -----------------------------------------------
    // AUTH — connexion, inscription, déconnexion
    // -----------------------------------------------
    case 'auth':
        require_once __DIR__ . '/../controleurs/auth_controleur.php';
        $ctrl   = new AuthControleur($pdo);
        $action = $_GET['action'] ?? 'connexion';
        switch ($action) {
            case 'connexion':   $ctrl->connexion();   break;
            case 'login':       $ctrl->login();       break;
            case 'inscription': $ctrl->inscription(); break;
            case 'register':    $ctrl->register();    break;
            case 'deconnexion': $ctrl->deconnexion(); break;
            default:            $ctrl->connexion();   break;
        }
        break;

    // -----------------------------------------------
    // MON COMPTE — utilisateur connecté
    // -----------------------------------------------
    case 'mon_compte':
        require_once __DIR__ . '/../controleurs/auth_controleur.php';
        $ctrl = new AuthControleur($pdo);
        $ctrl->monCompte();
        break;

    // -----------------------------------------------
    // FAVORIS — étudiant seulement (SFx23-25)
    // -----------------------------------------------
    case 'favoris':
        require_once __DIR__ . '/../controleurs/favoris_controleur.php';
        $ctrl   = new FavorisControleur($pdo);
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'index':  $ctrl->index();  break;
            case 'toggle': $ctrl->toggle(); break;
            default:       $ctrl->index();  break;
        }
        break;

    // -----------------------------------------------
    // CANDIDATURES — étudiant + pilote (SFx20-22)
    // -----------------------------------------------
    case 'candidature':
        require_once __DIR__ . '/../controleurs/candidature_controleur.php';
        $ctrl   = new CandidatureControleur($pdo);
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'create': $ctrl->create();              break;
            case 'store':  $ctrl->store();               break;
            case 'index':  $ctrl->index();               break;
            case 'pilote': $ctrl->candidaturesPilote();  break;
            default:       $ctrl->index();               break;
        }
        break;

    // -----------------------------------------------
    // ÉTUDIANTS — pilote + admin (SFx16-19)
    // -----------------------------------------------
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

    // -----------------------------------------------
    // PILOTES — admin seulement (SFx12-15)
    // -----------------------------------------------
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

    // -----------------------------------------------
    // ÉVALUATION — admin + pilote (SFx5) — AJAX
    // -----------------------------------------------
    case 'evaluation':
        require_once __DIR__ . '/../controleurs/evaluation_controleur.php';
        $ctrl = new EvaluationControleur($pdo);
        $ctrl->noter();
        break;

    // -----------------------------------------------
    // PAGES STATIQUES — publiques
    // -----------------------------------------------
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

    // -----------------------------------------------
    // PAGE INCONNUE — redirection accueil
    // -----------------------------------------------
    default:
        header('Location: /index.php?page=accueil');
        exit;
}
?>