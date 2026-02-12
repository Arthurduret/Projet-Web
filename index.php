<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="images/HeadLogoJobeo.png">
    <title>Jobeo | Accueil</title>

    <link rel="stylesheet" href="style_global.css">
    <link rel="stylesheet" href="style_index.css">
    <link rel="stylesheet" href="header_footer.css">
</head>
<body>
    <?php include 'header.php'; ?> 
    <main>
        <section class="Hero">
            <div><img src="images/ImageFondPageAccueil.png" alt="Image d'accueil"></div>
            <div class="content-wrapper">
                <form class="search-bar" action="">
                    <div class="input-group">
                        <label for="lieu">Lieux</label>
                        <input type="text" name="location" id="lieu" placeholder="Marseille 13001">
                    </div>
                    <div class="input-group">
                        <label for="domaine">Domaine</label>
                        <input type="text" name="skill" id="domaine" placeholder="Data">
                    </div>
                    <button type="submit" class="btn-search">
                        <img src="images/LoupeLogo.png" alt="Rechercher">
                    </button>
                </form>
            </div>

        </section>



    </main>
    <?php include 'footer.php'; ?>
</body>
</html>