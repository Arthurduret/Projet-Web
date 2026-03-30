<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | <? echo isset($entreprise) ? 'Modifier' : 'Créer' ?> une entreprise</title>
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/form.css">
    <link rel="stylesheet" href="/css/offre.css">
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

            <!-- Titre dynamique -->
            <h1><?= isset($entreprise) ? 'Modifier l\'entreprise' : 'Créer une entreprise' ?></h1>

            <!-- Action dynamique selon création ou modification -->
            <form method="POST" 
                  action="<?= isset($entreprise) 
                    ? '/index.php?page=entreprises&action=update&id=' . $entreprise['id_entreprise']
                    : '/index.php?page=entreprises&action=store' ?>" 
                  enctype="multipart/form-data"
                  novalidate>
                <?php echo csrfInput(); ?>
                
                <!-- NOM -->
                <div class="input-group">
                    <label for="nom">Nom de l'entreprise</label>
                    <input type="text"
                           name="nom"
                           id="nom"
                           value="<?= isset($entreprise) ? htmlspecialchars($entreprise['nom']) : Validation::oldValue('nom') ?>"
                           placeholder="Ex : Google">
                </div>

                <!-- DESCRIPTION -->
                <div class="input-group">
                    <label for="description">Description</label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              placeholder="Décrivez l'entreprise..."><?= isset($entreprise) ? htmlspecialchars($entreprise['description']) : Validation::oldValue('description') ?></textarea>
                </div>

                <!-- EMAIL -->
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="<?= isset($entreprise) ? htmlspecialchars($entreprise['email']) : Validation::oldValue('email') ?>"
                           placeholder="Ex : contact@google.fr">
                </div>

                <!-- TÉLÉPHONE -->
                <div class="input-group">
                    <label for="tel">Téléphone</label>
                    <input type="tel"
                           name="tel"
                           id="tel"
                           value="<?= isset($entreprise) ? htmlspecialchars($entreprise['tel']) : Validation::oldValue('tel') ?>"
                           pattern="^0[1-9][0-9]{8}$"
                           placeholder="Ex : 0123456789">
                </div>

                <!-- LOGO -->
                <div class="input-group">
                    <label for="image_logo">
                        Logo de l'entreprise <?= isset($entreprise) ? '(laisser vide pour garder l\'actuel)' : '' ?>
                    </label>
                    <?php if (isset($entreprise) && $entreprise['image_logo']): ?>
                        <img src="/images/entreprises/logo/<?= htmlspecialchars($entreprise['image_logo']) ?>"
                             alt="Logo actuel" style="height:60px; margin-bottom:0.5rem;">
                    <?php endif; ?>
                    <input type="file"
                           name="image_logo"
                           id="image_logo"
                           accept="image/*">
                </div>

                <!-- IMAGE FOND -->
                <div class="input-group">
                    <label for="image_fond">
                        Image de fond <?= isset($entreprise) ? '(laisser vide pour garder l\'actuelle)' : '' ?>
                    </label>
                    <?php if (isset($entreprise) && $entreprise['image_fond']): ?>
                        <img src="/images/entreprises/fond/<?= htmlspecialchars($entreprise['image_fond']) ?>"
                             alt="Fond actuel" style="height:60px; margin-bottom:0.5rem;">
                    <?php endif; ?>
                    <input type="file"
                           name="image_fond"
                           id="image_fond"
                           accept="image/*">
                </div>

                <?php if (isset($entreprise)): ?>
                    <input type="hidden" name="image_logo_actuel" value="<?= htmlspecialchars($entreprise['image_logo']) ?>">
                    <input type="hidden" name="image_fond_actuel" value="<?= htmlspecialchars($entreprise['image_fond']) ?>">
                <?php endif; ?>

                <!-- BOUTONS -->
                <div class="input-group">
                    <a href="/index.php?page=entreprises" class="btn-annuler">
                        Annuler
                    </a>
                    <button type="submit" class="btn-soumettre">
                        <?= isset($entreprise) ? 'Modifier l\'entreprise' : 'Créer l\'entreprise' ?>
                    </button>
                </div>

            </form>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>