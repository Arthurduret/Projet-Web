
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | <?php echo htmlspecialchars($offre['titre']); ?></title>

    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/offre.css">
    <link rel="stylesheet" href="/css/header_footer.css">
</head>

<body>

<?php include __DIR__ . '/partials/header.php'; ?>

    <main class="form-main">
        <div class="form-container">

            <h1>Créer une entreprise</h1>

            <form method="POST" action="/index.php?page=entreprises&action=store" enctype="multipart/form-data">
                <?php echo csrfInput(); ?>
                
                <!-- NOM -->
                <div class="form-group">
                    <label for="nom">Nom de l'entreprise *</label>
                    <input type="text"
                           name="nom"
                           id="nom"
                           required
                           placeholder="Ex : Google">
                </div>

                <!-- DESCRIPTION -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description"
                            id="description"
                            rows="4"
                            placeholder="Décrivez l'entreprise..."></textarea>
                </div>

                <!-- LOGO -->
                <div class="form-group">
                    <label for="image_logo">Logo de l'entreprise</label>
                    <input type="file"
                        name="image_logo"
                        id="image_logo"
                        accept="image/*">
                </div>

                <!-- IMAGE FOND -->
                <div class="form-group">
                    <label for="image_fond">Image de fond</label>
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

        </div>

</main>

<?php include __DIR__ . '/partials/footer.php'; ?>

</body>
</html><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | Créer une entreprise</title>
    <link rel="stylesheet" href="/public/css/style_global.css">
    <link rel="stylesheet" href="/public/css/offre.css">
    <link rel="stylesheet" href="/public/css/header_footer.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="form-main">
        <div class="form-container">

            <h1>Créer une entreprise</h1>

            <form method="POST" action="/public/index.php?page=entreprises&action=store">

                <!-- NOM -->
                <div class="form-group">
                    <label for="nom">Nom de l'entreprise *</label>
                    <input type="text"
                           name="nom"
                           id="nom"
                           required
                           placeholder="Ex : Google">
                </div>

                <!-- DESCRIPTION -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              placeholder="Décrivez l'entreprise..."></textarea>
                </div>

                <!-- LOGO -->
                <div class="form-group">
                    <label for="image_logo">Nom du fichier logo</label>
                    <input type="text"
                           name="image_logo"
                           id="image_logo"
                           placeholder="Ex : google_logo.png">
                </div>

                <!-- IMAGE FOND -->
                <div class="form-group">
                    <label for="image_fond">Nom du fichier image de fond</label>
                    <input type="text"
                           name="image_fond"
                           id="image_fond"
                           placeholder="Ex : google_fond.jpg">
                </div>

                <!-- BOUTONS -->
                <div class="form-boutons">
                    <a href="/public/index.php?page=entreprises" class="btn-annuler">
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
