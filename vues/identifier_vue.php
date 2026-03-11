<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/public/images/jobeo/HeadLogoJobeo.png">
    <title>Jobeo | S'identifier</title>

    <link rel="stylesheet" href="/public/css/style_global.css">
    <link rel="stylesheet" href="/public/css/style_connexion.CSS">
    <link rel="stylesheet" href="/public/css/header_footer.css">
</head>


<body>
    <?php include __DIR__ . '/partials/header.php'; ?> 

    <main>
        <div class="login-container">
            <h1>S'identifier</h1>
            
            <form>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email">
                </div>

                
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>



</body>


</html>