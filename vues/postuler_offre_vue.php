<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/images/jobeo/HeadLogoJobeo.png">
    <title>Jobeo | Postuler</title>

    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/form.css">
    <link rel="stylesheet" href="/css/header_footer.css">
</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="form-page">
        <div class="login-container">
            <a href="javascript:history.back()" class="back-link">← Retour</a>

            <h1>Postuler</h1>

            <?php if (!empty($erreur)) : ?>
                <div class="alert-erreur"><?= $erreur ?></div>
            <?php endif; ?>

            <?php if (!empty($succes)) : ?>
                <div class="alert-succes"><?= $succes ?></div>
            <?php endif; ?>

            <form action="/index.php?page=candidature&action=store&id=<?= (int)$id_offre ?>"method="POST" enctype="multipart/form-data">

                <!-- CV -->
                <div class="input-group">
                    <label>CV (image)</label>
                    <input type="file" name="cv" accept="image/*" required>
                </div>

                <!-- Lettre de motivation -->
                <div class="input-group">
                    <label>Lettre de motivation</label>
                    <input type="file" name="lettre_motivation" accept="image/*, .pdf, .doc, .docx, .txt" required>         
                </div>
                <button type="submit">Envoyer ma candidature</button>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?> 
</body>
</html>