<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo</title>
    <link rel="icon" type="image/png" href="images/Gemini_Generated_Image_fa3fcbfa3fcbfa3f-removebg-preview (1).png">
    <link rel="stylesheet" href="Style_connexion.CSS">
    <link rel="stylesheet" href="header_footer.css">
</head>

<body>
    <?php include 'header.php'; ?> 

    <main>

        <div class="login-container">
            <a href="index.html" class="back-link">← Retour</a>
            <h1>Connexion</h1>
            
            <form>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email">
                </div>
                
                <div class="input-group">
                    <label>Mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password">
                    </div>
                </div>
                
                <p>
                <a href="mot_de_passe_oublié.html" class="back-link">Mot de passe oublié ?</a>
                </p>
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?> 

</body>
</html>
