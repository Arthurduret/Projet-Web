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

    // case 'offres':
    //     require_once '../controleurs/offres_controleur.php';
    //     $ctrl = new OffreControleur($pdo);
    //     $ctrl->index();
    //     break;

    // case 'connexion':
    //     require_once '../controleurs/connexion_controleur.php';
    //     $ctrl = new ConnexionControleur($pdo);
    //     $ctrl->index();
    //     break;

    default:
        // Page inconnue → on redirige vers l'accueil
        header('Location: /index.php?page=accueil');
        exit;
}
?>