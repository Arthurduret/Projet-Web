<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/images/jobeo/HeadLogoJobeo.png">
    <title>Jobeo | Connexion</title>

    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/form.css">
    <link rel="stylesheet" href="/css/header_footer.css">
</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="form-page">
        <div class="login-container">
            <h1>Connexion</h1>
            
            <form method="POST" action="/index.php?page=auth&action=login">
                <?php echo csrfInput(); ?>
    
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" 
                        value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>"
                        required autocomplete="email">
                </div>
                
                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" required autocomplete="current-password">
                        <span id="boutonOeil">👁️</span>
                    </div>
                </div>
                
                <p>
                    <a href="/index.php?page=mot_de_passe_oublie" class="back-link">Mot de passe oublié ?</a>
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