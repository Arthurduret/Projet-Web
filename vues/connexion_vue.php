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
                        value="<?php echo htmlspecialchars($email ?? ''); ?>"
                        required autocomplete="email">
                </div>
                
                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" required autocomplete="current-password">
                        <span id="boutonOeil" class="password-toggle">👁️</span>
                    </div>
                </div>
                
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?> 

    <script>
        const boutonOeil = document.querySelector('#boutonOeil');
        const inputPass = document.querySelector('#password');
        const email_lowercase = document.querySelector('#email');

        email_lowercase.addEventListener('input', function() {
            email_lowercase.value = email_lowercase.value.toLowerCase();
        })

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

