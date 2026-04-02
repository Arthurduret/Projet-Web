<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | Modifier un étudiant</title>
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

            <?php if (!empty($erreur)): ?>
                <p style="color:red; text-align:center; margin-bottom:1rem;">
                    <?= htmlspecialchars($erreur) ?>
                </p>
            <?php endif; ?>

            <form action="/index.php?page=etudiants&action=update" method="POST">
                <?php echo csrfInput(); ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($etudiant['id_utilisateur']) ?>">

                <div class="double-input">
                    <div class="input-group">
                        <label for="nom">Nom</label>
                        <input type="text"
                               name="nom"
                               id="nom"
                               value="<?= htmlspecialchars($etudiant['nom']) ?>"
                               required>
                    </div>
                    <div class="input-group">
                        <label for="prenom">Prénom</label>
                        <input type="text"
                               name="prenom"
                               id="prenom"
                               value="<?= htmlspecialchars($etudiant['prenom']) ?>"
                               required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="<?= htmlspecialchars($etudiant['email']) ?>"
                           required>
                </div>

                <button type="submit">Enregistrer</button>
            </form>

        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>