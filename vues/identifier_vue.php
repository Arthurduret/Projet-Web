<?php

session_start();
require_once __DIR__ . '/../helper/csrf.php';

if (isset($_POST['email'])) {
    
$email = $_POST['email'];

$pdo = new PDO('mysql:host=localhost;port=3307;dbname=jobeo;charset=utf8', 'root', '');

// Requête 1 : est-ce que le pseudo existe ?
$stmt1 = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
$stmt1->bindValue(':email', $email);
$stmt1->execute();


$utilisateur1 = $stmt1->fetch();

if ($utilisateur1 !== false) {
    header('Location: connexion_vue.php');
    exit();
} else {
    header('Location: inscription_particulier_vue.php');
    exit();
}

}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/public/images/jobeo/HeadLogoJobeo.png">
    <title>Jobeo | S'identifier</title>

    <link rel="stylesheet" href="/public/css/style_global.css">
    <link rel="stylesheet" href="/public/css/style_connexion.CSS">
    <link rel="stylesheet" href="/public/css/header_footer.css">
</head>


<body>
    <?php include __DIR__ . '/partials/header.php'; ?> 

    <main>
        <div class="login-container">

            <h1>S'identifier</h1>
            
            <form action="" method="POST">
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email">
                </div>
                <button type="submit">Se connecter</button>
            </form>

        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>