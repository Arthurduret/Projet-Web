<?php
session_start();
require_once __DIR__ . '/../helper/csrf.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/public/images/jobeo/HeadLogoJobeo.png">
    <title>Jobeo | Connexion</title>

    <link rel="stylesheet" href="/public/css/style_global.css">
    <link rel="stylesheet" href="/public/css/form.css">
    <link rel="stylesheet" href="/public/css/header_footer.css">
</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="form-page">
        <div class="login-container">
            <h1>Connexion</h1>
            
            <form method="POST" action="/public/index.php?page=connexion&action=login">
                <?php echo csrfInput(); ?>
    
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required autocomplete="email">
                </div>
                
                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" required autocomplete="current-password">
                        <span id="boutonOeil">👁️</span>
                    </div>
                </div>
                
                <p>
                    <a href="/public/index.php?page=mot_de_passe_oublie" class="back-link">Mot de passe oublié ?</a>
                </p>

                <button type="submit">Se connecter</button>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?> 

    <script>
        const boutonOeil = document.querySelector('#boutonOeil');
        const inputPass = document.querySelector('#password');

        boutonOeil.addEventListener('click', function() {
            if (inputPass.type === 'password') {
                inputPass.type = 'text';
                this.textContent = '🙈'; 
            } else {
                inputPass.type = 'password';
                this.textContent = '👁️';
            }
        });
    </script>
</body>
</html>