<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title><?= htmlspecialchars($meta_title ?? 'Jobeo | Créer un compte') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Créez un compte sur la plateforme Jobeo.') ?>">
    <meta name="robots" content="noindex, nofollow">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/jobeo/HeadLogoJobeo.png">

    <!-- CSS -->
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/form.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="form-page">
        <div class="login-container">

            <a href="/index.php?page=accueil" class="back-link">← Retour</a>

            <h1>Créer un compte</h1>

            <!-- Message d'erreur -->
            <?php if (!empty($erreur)): ?>
                <p class="erreur-message" role="alert">
                    <?= htmlspecialchars($erreur) ?>
                </p>
            <?php endif; ?>

            <!-- Switcher rôle -->
            <div class="switch_buttons">
                <?php if ($_SESSION['user']['role'] === 'pilote'): ?>
                    <a class="switch_btn <?= ($role ?? 'etudiant') === 'etudiant' ? 'active' : '' ?>"
                       href="/index.php?page=auth&action=inscription&role=etudiant&email=<?= urlencode($email ?? '') ?>">
                       Étudiant
                    </a>

                <?php elseif ($_SESSION['user']['role'] === 'admin'): ?>
                    <a class="switch_btn <?= ($role ?? 'etudiant') === 'etudiant' ? 'active' : '' ?>"
                       href="/index.php?page=auth&action=inscription&role=etudiant&email=<?= urlencode($email ?? '') ?>">
                       Étudiant
                    </a>
                    <a class="switch_btn <?= ($role ?? 'etudiant') === 'pilote' ? 'active' : '' ?>"
                       href="/index.php?page=auth&action=inscription&role=pilote&email=<?= urlencode($email ?? '') ?>">
                       Pilote
                    </a>
                <?php endif; ?>
            </div>

            <form action="/index.php?page=auth&action=register"
                  method="POST"
                  novalidate>

                <?= csrfInput() ?>
                <input type="hidden" name="role" value="<?= htmlspecialchars($role ?? 'etudiant') ?>">

                <!-- Email -->
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="<?= htmlspecialchars($email ?? '') ?>"
                           placeholder="prenom.nom@gmail.com"
                           required
                           aria-required="true"
                           autocomplete="email">
                </div>

                <!-- Nom + Prénom -->
                <div class="double-input">
                    <div class="input-group">
                        <label for="nom">Nom</label>
                        <input type="text"
                               name="nom"
                               id="nom"
                               placeholder="Votre nom"
                               required
                               aria-required="true"
                               maxlength="50">
                    </div>
                    <div class="input-group">
                        <label for="prenom">Prénom</label>
                        <input type="text"
                               name="prenom"
                               id="prenom"
                               placeholder="Votre prénom"
                               required
                               aria-required="true"
                               maxlength="50">
                    </div>
                </div>

                <!-- Mot de passe -->
                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password"
                               name="password"
                               id="password"
                               placeholder="8 caractères minimum"
                               minlength="8"
                               required
                               aria-required="true"
                               autocomplete="new-password">
                        <span class="password-toggle" id="boutonOeil">👁️</span>
                    </div>
                </div>

                <!-- Confirmation mot de passe -->
                <div class="input-group">
                    <label for="password_confirm">Confirmer le mot de passe</label>
                    <input type="password"
                           name="password_confirm"
                           id="password_confirm"
                           placeholder="Retapez votre mot de passe"
                           minlength="8"
                           required
                           aria-required="true"
                           autocomplete="new-password">
                </div>

                <!-- CGU -->
                <div class="checkbox-group">
                    <input type="checkbox" name="accepte_cgu" id="accepte_cgu" required>
                    <label for="accepte_cgu">
                        J'accepte les <a href="/index.php?page=cgu">conditions générales d'utilisation</a>
                    </label>
                </div>

                <!-- Confidentialité -->
                <div class="checkbox-group">
                    <input type="checkbox" name="accepte_confidentialite" id="accepte_confidentialite" required>
                    <label for="accepte_confidentialite">
                        J'accepte la <a href="/index.php?page=mentions_legales">politique de confidentialité</a>
                    </label>
                </div>

                <button type="submit">Créer le compte</button>

            </form>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script>
    const boutonOeil      = document.querySelector('#boutonOeil');
    const inputPass       = document.querySelector('#password');
    const inputPass2      = document.querySelector('#password_confirm');
    const email_lowercase = document.querySelector('#email');

    // Email en minuscules
    email_lowercase.addEventListener('input', function() {
        this.value = this.value.toLowerCase();
    });

    // Toggle mot de passe
    boutonOeil.addEventListener('click', function() {
        if (inputPass.type === 'password') {
            inputPass.type   = 'text';
            inputPass2.type  = 'text';
            this.textContent = '🙈';
        } else {
            inputPass.type   = 'password';
            inputPass2.type  = 'password';
            this.textContent = '👁️';
        }
    });

    // ===== VALIDATION JS CÔTÉ CLIENT =====
    const form = document.querySelector('form');

    form.addEventListener('submit', function(e) {
        let valid = true;

        // Email
        const email = document.querySelector('#email');
        if (!email.value.trim()) {
            email.setCustomValidity("L'email est obligatoire.");
            valid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
            email.setCustomValidity("Veuillez saisir un email valide.");
            valid = false;
        } else {
            email.setCustomValidity('');
        }

        // Nom
        const nom = document.querySelector('#nom');
        if (!nom.value.trim()) {
            nom.setCustomValidity('Le nom est obligatoire.');
            valid = false;
        } else {
            nom.setCustomValidity('');
        }

        // Prénom
        const prenom = document.querySelector('#prenom');
        if (!prenom.value.trim()) {
            prenom.setCustomValidity('Le prénom est obligatoire.');
            valid = false;
        } else {
            prenom.setCustomValidity('');
        }

        // Mot de passe
        if (inputPass.value.length < 8) {
            inputPass.setCustomValidity('Le mot de passe doit contenir au moins 8 caractères.');
            valid = false;
        } else {
            inputPass.setCustomValidity('');
        }

        // Confirmation mot de passe
        if (inputPass2.value !== inputPass.value) {
            inputPass2.setCustomValidity('Les mots de passe ne correspondent pas.');
            valid = false;
        } else {
            inputPass2.setCustomValidity('');
        }

        if (!valid) {
            e.preventDefault();
            form.reportValidity();
        }
    });

    // Reset setCustomValidity à la saisie
    document.querySelectorAll('input').forEach(function(el) {
        el.addEventListener('input', function() {
            this.setCustomValidity('');
        });
    });
    </script>
</body>
</html>