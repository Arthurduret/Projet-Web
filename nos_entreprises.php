<?php 
require_once 'db.php'; 
// On récupère toutes les entreprises
$query = $pdo->query("SELECT * FROM entreprises");
$entreprises = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style_global.css">
    <link rel="stylesheet" href="/style_nos_entreprises.css">
    <link rel="stylesheet" href="/header_footer.css">
    <title>Document</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="header-entreprise">
            <h1>Explorez les entreprises qui recrutent</h1>
        </div>

        <div class="grille-entreprises">
            <?php foreach ($entreprises as $entreprise): ?>
                <article class="carte-entreprise">
                    <a href="/entreprises/fiche.php?id=<?php echo $entreprise['id']; ?>">
                        
                        <div class="image-fond">
                            <img src="/images/<?php echo $entreprise['image_fond']; ?>" alt="Fond <?php echo $entreprise['nom']; ?>">
                        </div>

                        <div class="contenu-carte">
                            <div class="header_carte">
                                <img src="/images/<?php echo $entreprise['image_logo']; ?>" alt="Logo" class="logo-mini">
                                <h3 class="name-entreprise"><?php echo $entreprise['nom']; ?></h3>
                            </div>

                            <div class="infos-entreprise">
                                <span><?php echo $entreprise['secteur']; ?></span>
                                <span><?php echo $entreprise['taille']; ?></span>
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
    </main>
    <?php include 'footer.php'; ?>
    
</body>
</html>