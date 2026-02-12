<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="images/HeadLogoJobeo.png">
    <title>Jobeo | S'identifier</title>

    <link rel="stylesheet" href="style_global.css">
    <link rel="stylesheet" href="/Style_connexion.CSS">
    <link rel="stylesheet" href="/header_footer.css">
</head>


<body>
        <?php include 'header.php'; ?> 

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

    <?php include 'footer.php'; ?> 



</body>


</html>