<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title><?= htmlspecialchars($meta_title ?? 'Jobeo | ' . (isset($entreprise) ? 'Modifier' : 'Créer') . ' une entreprise') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Gérez les entreprises partenaires sur la plateforme Jobeo.') ?>">
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
        <div class="login-container" style="max-width:700px;">

            <a href="/index.php?page=entreprises" class="back-link">← Retour aux entreprises</a>

            <h1><?= isset($entreprise) ? 'Modifier l\'entreprise' : 'Créer une entreprise' ?></h1>

            <!-- Message d'erreur -->
            <?php if (!empty($_SESSION['erreur'])): ?>
                <p class="erreur-message" role="alert">
                    <?= htmlspecialchars($_SESSION['erreur']) ?>
                    <?php unset($_SESSION['erreur']); ?>
                </p>
            <?php endif; ?>

            <form class="form-entreprise"
                  action="/index.php?page=entreprises&action=<?= isset($entreprise) ? 'update' : 'store' ?>"
                  method="POST"
                  enctype="multipart/form-data"
                  novalidate>

                <?= csrfInput() ?>

                <?php if (isset($entreprise)): ?>
                    <input type="hidden" name="id_entreprise" value="<?= htmlspecialchars($entreprise['id_entreprise']) ?>">
                    <input type="hidden" name="image_logo_actuel" value="<?= htmlspecialchars($entreprise['image_logo'] ?? '') ?>">
                    <input type="hidden" name="image_fond_actuel" value="<?= htmlspecialchars($entreprise['image_fond'] ?? '') ?>">
                <?php endif; ?>

                <!-- Nom -->
                <div class="input-group">
                    <label for="nom">Nom</label>
                    <input id="nom"
                           type="text"
                           name="nom"
                           maxlength="50"
                           placeholder="Nom de l'entreprise"
                           value="<?= htmlspecialchars($entreprise['nom'] ?? Validation::oldValue('nom')) ?>"
                           required>
                </div>

                <!-- Description -->
                <div class="input-group input-group--commentaire">
                    <label for="commentaire">Description de l'entreprise</label>
                    <textarea id="commentaire"
                              name="description"
                              placeholder="Faites une description de votre entreprise (taille, domaine d'expertise...)"
                              rows="5"
                              maxlength="500"
                              required><?= htmlspecialchars($entreprise['description'] ?? Validation::oldValue('description')) ?></textarea>
                </div>

                <!-- Email -->
                <div class="input-group">
                    <label for="email">Email</label>
                    <input id="email"
                           type="email"
                           name="email"
                           placeholder="contact@exemple.com"
                           value="<?= htmlspecialchars($entreprise['email'] ?? Validation::oldValue('email')) ?>"
                           required
                           autocomplete="email">
                </div>

                <!-- Téléphone -->
                <div class="input-group">
                    <label for="tel">Numéro de téléphone</label>
                    <input id="tel"
                           type="tel"
                           name="tel"
                           placeholder="0612345678"
                           value="<?= htmlspecialchars($entreprise['tel'] ?? Validation::oldValue('tel')) ?>"
                           pattern="^0[1-9][0-9]{8}$"
                           required>
                </div>

                <!-- Image de fond -->
                <div class="input-group">
                    <label for="image_fond">Image de fond</label>
                    <?php if (isset($entreprise) && !empty($entreprise['image_fond'])): ?>
                        <img src="/images/entreprises/fond/<?= htmlspecialchars($entreprise['image_fond']) ?>"
                             alt="Image de fond actuelle"
                             style="max-height:80px; margin-bottom:0.5rem; border-radius:8px; display:block;">
                        <small>Laissez vide pour conserver l'image actuelle</small>
                    <?php endif; ?>
                    <input id="image_fond"
                           type="file"
                           name="image_fond"
                           accept="image/*"
                           <?= !isset($entreprise) ? 'required' : '' ?>>
                </div>

                <!-- Logo -->
                <div class="input-group">
                    <label for="image_logo">Logo de l'entreprise</label>
                    <?php if (isset($entreprise) && !empty($entreprise['image_logo'])): ?>
                        <img src="/images/entreprises/logo/<?= htmlspecialchars($entreprise['image_logo']) ?>"
                             alt="Logo actuel"
                             style="max-height:60px; margin-bottom:0.5rem; border-radius:8px; display:block;">
                        <small>Laissez vide pour conserver le logo actuel</small>
                    <?php endif; ?>
                    <input id="image_logo"
                           type="file"
                           name="image_logo"
                           accept="image/*"
                           <?= !isset($entreprise) ? 'required' : '' ?>>
                </div>

                <button type="submit">
                    <?= isset($entreprise) ? 'Enregistrer les modifications' : 'Créer l\'entreprise' ?>
                </button>

            </form>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script>
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

        const description = document.querySelector('#commentaire');
        if (!description.value.trim()) {
            description.setCustomValidity('La description est obligatoire.');
            valid = false;
        } else {
            description.setCustomValidity('');
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

        const tel = document.querySelector('#tel');
        if (!tel.value.trim()) {
            tel.setCustomValidity('Le téléphone est obligatoire.');
            valid = false;
        } else if (!/^0[1-9][0-9]{8}$/.test(tel.value.replace(/\s/g, ''))) {
            tel.setCustomValidity('Format invalide — 10 chiffres commençant par 0.');
            valid = false;
        } else {
            tel.setCustomValidity('');
        }

        if (!valid) {
            e.preventDefault();
            form.reportValidity();
        }
    });

    document.querySelectorAll('input, textarea').forEach(function(el) {
        el.addEventListener('input', function() {
            this.setCustomValidity('');
        });
    });
    </script>
</body>
</html>