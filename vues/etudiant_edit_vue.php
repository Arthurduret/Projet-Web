<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title><?= htmlspecialchars($meta_title ?? 'Jobeo | Modifier un étudiant') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Modifier les informations d\'un compte étudiant sur Jobeo.') ?>">
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

            <a href="/index.php?page=etudiants" class="back-link">← Retour</a>

            <h1>Modifier l'étudiant</h1>

            <!-- Message d'erreur -->
            <?php if (!empty($erreur)): ?>
                <p class="erreur-message" role="alert">
                    <?= htmlspecialchars($erreur) ?>
                </p>
            <?php endif; ?>

            <!-- Message erreur session -->
            <?php if (!empty($_GET['erreur'])): ?>
                <p class="erreur-message" role="alert">
                    <?php
                    $msgs = [
                        'champs_obligatoires' => 'Tous les champs sont obligatoires.',
                        'email_invalide'      => 'L\'adresse email n\'est pas valide.',
                    ];
                    echo htmlspecialchars($msgs[$_GET['erreur']] ?? 'Une erreur est survenue.');
                    ?>
                </p>
            <?php endif; ?>

            <form action="/index.php?page=etudiants&action=update"
                  method="POST"
                  novalidate>

                <?= csrfInput() ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($etudiant['id_utilisateur']) ?>">

                <!-- Nom + Prénom -->
                <div class="double-input">
                    <div class="input-group">
                        <label for="nom">Nom</label>
                        <input type="text"
                               name="nom"
                               id="nom"
                               value="<?= htmlspecialchars($etudiant['nom']) ?>"
                               required
                               aria-required="true"
                               maxlength="50">
                    </div>
                    <div class="input-group">
                        <label for="prenom">Prénom</label>
                        <input type="text"
                               name="prenom"
                               id="prenom"
                               value="<?= htmlspecialchars($etudiant['prenom']) ?>"
                               required
                               aria-required="true"
                               maxlength="50">
                    </div>
                </div>

                <!-- Email -->
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="<?= htmlspecialchars($etudiant['email']) ?>"
                           required
                           aria-required="true"
                           autocomplete="email">
                </div>

                <button type="submit">Enregistrer</button>

            </form>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script>
    // ===== VALIDATION JS CÔTÉ CLIENT =====
    const form = document.querySelector('form');

    form.addEventListener('submit', function(e) {
        let valid = true;

        const nom = document.querySelector('#nom');
        if (!nom.value.trim()) {
            nom.setCustomValidity('Le nom est obligatoire.');
            valid = false;
        } else {
            nom.setCustomValidity('');
        }

        const prenom = document.querySelector('#prenom');
        if (!prenom.value.trim()) {
            prenom.setCustomValidity('Le prénom est obligatoire.');
            valid = false;
        } else {
            prenom.setCustomValidity('');
        }

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