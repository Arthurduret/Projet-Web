<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Jobeo | Modifier un pilote</title>
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/form.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="form-page">
        <div class="login-container">

            <a href="/index.php?page=pilotes" class="back-link">← Retour</a>
            <h1>Modifier le pilote</h1>

            <form action="/index.php?page=pilotes&action=update" method="POST">
                <?php echo csrfInput(); ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($pilote['id_utilisateur']) ?>">

                <div class="double-input">
                    <div class="input-group">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" id="nom"
                               value="<?= htmlspecialchars($pilote['nom']) ?>" required>
                    </div>
                    <div class="input-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" name="prenom" id="prenom"
                               value="<?= htmlspecialchars($pilote['prenom']) ?>" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email"
                           value="<?= htmlspecialchars($pilote['email']) ?>" required>
                </div>

                <button type="submit">Enregistrer</button>
            </form>

        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>