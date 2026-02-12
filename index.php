<?php 
require_once 'db.php'; 

// On sélectionne TOUT, mais on s'arrête à 3 résultats
$query = $pdo->query("SELECT * FROM entreprises LIMIT 3");
$entreprises_accueil = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | Accueil</title>
    <link rel="icon" type="image/png" href="images/LogoJobeo.png">
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
        <section>
            <div class="header-entreprise">
                <h1>Nos Entreprises</h1>
            </div>
            <div class="grille-entreprises">
                <?php foreach ($entreprises_accueil as $entreprise): ?>
                    <article class="carte-entreprise">
                        <a href="/entreprises/fiche.php?id=<?php echo $entreprise['id']; ?>">
                            <div class="image-fond">
                                <img src="/images/<?php echo $entreprise['image_fond']; ?>" alt="Fond">
                            </div>
                            <div class="contenu-carte">
                                <div class="header_carte">
                                    <img src="/images/<?php echo $entreprise['image_logo']; ?>" class="logo-mini">
                                    <h3 class="name-entreprise"><?php echo $entreprise['nom']; ?></h3>
                                </div>
                                <div class="footer-carte">
                                    <span class="nb-jobs"><?php echo $entreprise['nb_jobs']; ?> jobs</span>
                                    <span class="btn-decouvrir">Découvrir</span>
                                </div>
                            </div>
                        </a>    
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>