
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | <?php echo htmlspecialchars($offre['titre']); ?></title>

    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/form.css">
    <link rel="stylesheet" href="/css/header_footer.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="form-main">
        <div class="form-container">

            <!-- Message d'erreur -->
            <?php if (isset($_SESSION['erreur'])): ?>
                <div class="alert-erreur">
                    ⚠️ <?php echo htmlspecialchars($_SESSION['erreur']); ?>
                </div>
                <?php unset($_SESSION['erreur']); ?>
            <?php endif; ?>

            <h1>Créer une entreprise</h1>

            <form method="POST" 
                  action="/index.php?page=entreprises&action=store" 
                  enctype="multipart/form-data"
                  novalidate>
                <?php echo csrfInput(); ?>
                
                <!-- NOM -->
                <div class="form-group">
                    <label for="nom">Nom de l'entreprise *</label>
                    <input type="text"
                           name="nom"
                           id="nom"
                           value="<?= Validation::oldValue('nom') ?>"
                           placeholder="Ex : Google">
                </div>

                <!-- DESCRIPTION -->
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              placeholder="Décrivez l'entreprise..."><?= Validation::oldValue('description') ?></textarea>
                </div>

                <!-- EMAIL -->
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="<?= Validation::oldValue('email') ?>"
                           placeholder="Ex : contact@google.fr">
                </div>

                <!-- TÉLÉPHONE -->
                <div class="form-group">
                    <label for="tel">Téléphone *</label>
                    <input type="tel"
                           name="tel"
                           id="tel"
                           value="<?= Validation::oldValue('tel') ?>"
                           pattern="^0[1-9][0-9]{8}$"
                           placeholder="Ex : 0123456789"
                           title="Format attendu : 10 chiffres, commence par 0">
                </div>

                <!-- LOGO -->
                <div class="form-group">
                    <label for="image_logo">Logo de l'entreprise *</label>
                    <input type="file"
                           name="image_logo"
                           id="image_logo"
                           accept="image/*">
                </div>

                <!-- IMAGE FOND -->
                <div class="form-group">
                    <label for="image_fond">Image de fond *</label>
                    <input type="file"
                           name="image_fond"
                           id="image_fond"
                           accept="image/*">
                </div>

                <!-- BOUTONS -->
                <div class="form-boutons">
                    <a href="/index.php?page=entreprises" class="btn-annuler">
                        Annuler
                    </a>
                    <button type="submit" class="btn-soumettre">
                        Créer l'entreprise
                    </button>
                </div>

            </form>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>