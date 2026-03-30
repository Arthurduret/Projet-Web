<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Jobeo | Mon compte</title>
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/form.css">
    <link rel="stylesheet" href="/css/header_footer.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="form-page">
        <div class="login-container">

            <h1>Mon compte</h1>

            <div class="input-group">
                <label>Prénom</label>
                <input type="text" value="<?php echo htmlspecialchars($utilisateur['prenom']); ?>" disabled>
            </div>

            <div class="input-group">
                <label>Nom</label>
                <input type="text" value="<?php echo htmlspecialchars($utilisateur['nom']); ?>" disabled>
            </div>

            <div class="input-group">
                <label>Email</label>
                <input type="email" value="<?php echo htmlspecialchars($utilisateur['email']); ?>" disabled>
            </div>

            <div class="input-group">
                <label>Rôle</label>
                <input type="text" value="<?php echo htmlspecialchars($utilisateur['role']); ?>" disabled>
            </div>

            <a href="/index.php?page=auth&action=deconnexion" 
               style="display:block; text-align:center; margin-top:20px; color:#e53e3e; font-weight:600; text-decoration:none;">
                Se déconnecter
            </a>

        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>