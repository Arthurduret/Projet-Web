<?php
require_once __DIR__ . '/../db.php';

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
        $ctrl = new EntrepriseControleur($pdo);
        $ctrl->index();
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

    default:
        // Page inconnue → on redirige vers l'accueil
        header('Location: /index.php?page=accueil');
        exit;
}
?>