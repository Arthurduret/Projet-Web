<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/images/jobeo/HeadLogoJobeo.png">
    <title>Jobeo | Inscription</title>

    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/form.css">
    
    

</head>

<body>
    
    <?php include __DIR__ . '/partials/header.php'; ?> 

    <main class="form-page">
        <div class="login-container">

            <a href="/index.php?page=accueil" class="back-link">← Retour</a>

            <h1>S'inscrire</h1>
            
            <div class="switch_buttons">
                <a class="switch_btn active">Étudiant</a>
            </div>

            <form method="POST"
                  action="/index.php?page=inscription&action=store"
                  enctype="multipart/form-data">
                <?php echo csrfInput(); ?>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email"
                           name="email"
                           id="email"
                           placeholder="prenom.nom@gmail.com"
                           required
                           autocomplete="email">
                </div>

                <div class="double-input">
                    <div class="input-group">
                        <label for="nom">Nom</label>
                        <input type="text"
                               name="nom"
                               id="nom"
                               placeholder="Votre nom"
                               required>
                    </div>

                    <div class="input-group">
                        <label for="prenom">Prénom</label>
                        <input type="text"
                               name="prenom"
                               id="prenom"
                               placeholder="Votre prénom"
                               required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="cv">CV (PDF)</label>
                    <input type="file"
                           name="cv"
                           id="cv"
                           accept=".pdf">
                </div>

                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password"
                               name="password"
                               id="password"
                               placeholder="8 caractères minimum"
                               minlength="8"
                               required
                               autocomplete="new-password">
                        <span class="password-toggle" id="boutonOeil">👁️</span>
                    </div>
                </div>

                <div class="input-group">
                    <label for="password_confirm">Confirmer le mot de passe</label>
                    <input type="password"
                           name="password_confirm"
                           id="password_confirm"
                           placeholder="Retapez votre mot de passe"
                           minlength="8"
                           required
                           autocomplete="new-password">
                </div>




                <div class="checkbox-group">
                    <input type="checkbox" name="accepte_cgu" id="accepte_cgu" required>
                    <label for="accepte_cgu">
                        J'accepte les <a href="/index.php?page=cgu">conditions générales d'utilisation</a>
                    </label>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="accepte_confidentialite" id="accepte_confidentialite" required>
                    <label for="accepte_confidentialite">
                        J'accepte la <a href="/index.php?page=mentions_legales">politique de confidentialité</a>
                    </label>
                </div>

                <button type="submit">S'inscrire</button>
            </form>
        </div>
    </main>
            
    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script>
        const boutonOeil = document.querySelector('#boutonOeil');
        const inputPass  = document.querySelector('#password');

        boutonOeil.addEventListener('click', function () {
            if (inputPass.type === 'password') {
                inputPass.type   = 'text';
                this.textContent = '🙈';
            } else {
                inputPass.type   = 'password';
                this.textContent = '👁️';
            }
        });
    </script>
</body>
</html>