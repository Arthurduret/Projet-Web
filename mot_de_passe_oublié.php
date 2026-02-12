<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | Mot de passe oublié</title>
    <link rel="stylesheet" href="Style_connexion.CSS">
    <link rel="stylesheet" href="style_global.css">
    <link rel="stylesheet" href="header_footer.css">
</head>
<body>

    <?php include 'header.php'; ?> 
        <main>
            <div class="login-container">
            <a href="Connexion.php" class="back-link">← Retour</a>

            <div class ="Réinitialiser">
            <p>RÉINITIALISER</p>
            </div>

            
        
        <form>
            <div class="input-group">
                <label>Email</label>
                <input type="email">
            </div>
             <button type="submit">Envoyer</button>
        </form>
        </main>

    <?php include 'footer.php'; ?> 

</body>
</html>