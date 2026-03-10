<!-- Ce fichier a UN seul rôle : afficher le HTML -->
<!-- Il ne fait AUCUN calcul, AUCUNE requête BDD -->
<!-- Il utilise juste la variable $entreprises préparée par le contrôleur -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | Entreprises</title>
    <link rel="stylesheet" href="/style_global.css">
    <link rel="stylesheet" href="/style_nos_entreprises.css">
    <link rel="stylesheet" href="/header_footer.css">
</head>
<body>
    <?php include './header.php'; ?>

    <main>
        <div class="header-entreprise">
            <h1>Explorez les entreprises qui recrutent</h1>
        </div>

        <div class="grille-entreprises">
            <?php foreach ($entreprises as $entreprise): ?>
                <article class="carte-entreprise">
                    <a href="/entreprises/fiche.php?id=<?php echo htmlspecialchars($entreprise['id']); ?>">
                        
                        <div class="image-fond">
                            <!-- htmlspecialchars() protège contre les failles XSS -->
                            <img src="/images/<?php echo htmlspecialchars($entreprise['image_fond']); ?>" 
                                 alt="Fond <?php echo htmlspecialchars($entreprise['nom']); ?>">
                        </div>

                        <div class="contenu-carte">
                            <div class="header_carte">
                                <img src="/images/<?php echo htmlspecialchars($entreprise['image_logo']); ?>" 
                                     alt="Logo" class="logo-mini">
                                <h3 class="name-entreprise">
                                    <?php echo htmlspecialchars($entreprise['nom']); ?>
                                </h3>
                            </div>

                            <div class="infos-entreprise">
                                <span><?php echo htmlspecialchars($entreprise['secteur']); ?></span>
                                <span><?php echo htmlspecialchars($entreprise['taille']); ?></span>
                            </div>

                            <div class="footer-carte">
                                <span class="nb-jobs">
                                    <?php echo htmlspecialchars($entreprise['nb_jobs']); ?> jobs
                                </span>
                                <span class="btn-decouvrir">Découvrir</span>
                            </div>
                        </div>
                    </a>    
                </article>
            <?php endforeach; ?>
        </div>
    </main>

    <?php include './footer.php'; ?>
</body>
</html>