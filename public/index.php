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

    case 'mentions_legales':
        require_once __DIR__ . '/../controleurs/mentions_legales_controleur.php';
        break;

    case 'cgu':
        require_once __DIR__ . '/../controleurs/cgu_controleur.php';
        break;

    case 'cookies':
        require_once __DIR__ . '/../controleurs/cookies_controleur.php';
        break;

    default:
        // Page inconnue → on redirige vers l'accueil
        header('Location: /index.php?page=accueil');
        exit;
}
?>