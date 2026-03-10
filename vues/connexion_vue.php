<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/public/images/jobeo/HeadLogoJobeo.png">
    <title>Jobeo | Connexion</title>

    <link rel="stylesheet" href="/public/css/style_global.css">
    <link rel="stylesheet" href="/public/css/style_connexion.CSS">
    <link rel="stylesheet" href="/public/css/header_footer.css">
</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <div class="login-container">
            <h1>Connexion</h1>
            
            <form>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email">
                </div>
                
                <div class="input-group">
                    <label>Mot de passe</label>
                    <div class="password-wrapper" style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="monInputPassword" style="width: 100%;">
                        <span id="boutonOeil" style="position: absolute; right: 10px; cursor: pointer; user-select: none;">
                            👁️
                        </span>
                    </div>
                </div>
                
                <p>
                    <a href="/mot_de_passe_oublié.php" class="back-link">Mot de passe oublié ?</a>
                </p>

                <button type="submit">Se connecter</button>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?> 

    <script>
        const boutonOeil = document.querySelector('#boutonOeil');
        const inputPass = document.querySelector('#monInputPassword');

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