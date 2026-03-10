<?php
// nos_entreprises.php
// C'est CE fichier que tu ouvres dans le navigateur
// Il démarre toute la chaîne MVC

// 1. On branche la BDD
require_once 'db.php';

// 2. On charge le contrôleur
require_once 'controleurs/entreprises_controleur.php';

// 3. On crée le contrôleur en lui passant la connexion
$controller = new EntrepriseControleur($pdo);

// 4. On démarre la page — c'est ici que tout se lance
$controller->index();
?>