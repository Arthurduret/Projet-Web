<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title><?= htmlspecialchars($meta_title ?? 'Jobeo | Connexion') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Connectez-vous à votre espace Jobeo pour accéder aux offres de stage.') ?>">
    <meta name="robots" content="noindex, nofollow">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/jobeo/HeadLogoJobeo.png">

    <!-- CSS -->
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/form.css">
    <link rel="stylesheet" href="/css/header_footer.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="form-page">
        <div class="login-container">

            <h1>Connexion</h1>

            <!-- Message d'erreur -->
            <?php if (!empty($erreur)): ?>
                <p class="erreur-message" role="alert">
                    <?= htmlspecialchars($erreur) ?>
                </p>
            <?php endif; ?>

            <form method="POST"
                  action="/index.php?page=auth&action=login"
                  novalidate>

                <?= csrfInput() ?>

                <!-- Email -->
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="<?= htmlspecialchars($email ?? '') ?>"
                           required
                           autocomplete="email"
                           aria-required="true">
                </div>

                <!-- Mot de passe -->
                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password"
                               name="password"
                               id="password"
                               required
                               autocomplete="current-password"
                               aria-required="true"
                               minlength="8">
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
        });

        boutonOeil.addEventListener('click', function() {
            if (inputPass.type === 'password') {
                inputPass.type = 'text';
                this.textContent = '🙈';
            } else {
                inputPass.type = 'password';
                this.textContent = '👁️';
            }
        });

        // ===== VALIDATION JS CÔTÉ CLIENT =====
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            let valid = true;

            if (!email_lowercase.value.trim()) {
                email_lowercase.setCustomValidity("L'email est obligatoire.");
                valid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email_lowercase.value)) {
                email_lowercase.setCustomValidity("Veuillez saisir un email valide.");
                valid = false;
            } else {
                email_lowercase.setCustomValidity('');
            }

            if (!inputPass.value) {
                inputPass.setCustomValidity("Le mot de passe est obligatoire.");
                valid = false;
            } else if (inputPass.value.length < 8) {
                inputPass.setCustomValidity("Le mot de passe doit contenir au moins 8 caractères.");
                valid = false;
            } else {
                inputPass.setCustomValidity('');
            }

            if (!valid) {
                e.preventDefault();
                form.reportValidity();
            }
        });
    </script>
</body>
</html>