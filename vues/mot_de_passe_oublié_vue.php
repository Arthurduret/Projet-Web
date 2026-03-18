<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/public/images/jobeo/HeadLogoJobeo.png">
    <title>Jobeo | Mot de passe oublié</title>
    
    <link rel="stylesheet" href="/public/css/form.css">
    <link rel="stylesheet" href="/public/css/style_global.css">
    <link rel="stylesheet" href="/public/css/header_footer.css">
</head>
<body>

    <?php include __DIR__ . '/partials/header.php'; ?>
        <main class="form-page">
            <div class="login-container">
                <a href="/public/index.php?page=connexion" class="back-link">← Retour</a>

                <h1>Réinitialiser</h1>
                <p style="text-align:center; color:#666; margin-bottom:25px; font-size:14px;">
                    Entrez votre adresse email pour recevoir un lien de réinitialisation.
                </p>

            
        
                <form method="POST" action="/public/index.php?page=mot_de_passe_oublie&action=send">
                    <?php echo csrfInput(); ?>

                        <div class="input-group">
                            <label for="email">Email</label>
                            <input type="email"
                                name="email"
                                id="email"
                                placeholder="prenom.nom@gmail.com"
                                required
                                autocomplete="email">
                        </div>

                    <button type="submit">Envoyer</button>
                </form>
            </div>    
        </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

</body>
</html>