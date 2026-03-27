<!-- Ce fichier a UN seul rôle : afficher le HTML -->
<!-- Il ne fait AUCUN calcul, AUCUNE requête BDD -->
<!-- Il utilise juste la variable $entreprises préparée par le contrôleur -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | Entreprises</title>
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/style_entreprises.css">
    <link rel="stylesheet" href="/css/header_footer.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <div class="header-entreprise">
            <h1>Explorez les entreprises qui recrutent</h1>
        </div>

        <div class="grille-entreprises">
            <?php foreach ($entreprises as $entreprise): ?>
                <article class="carte-entreprise">
                    <a href="/index.php?page=entreprises&action=show&id=<?php echo htmlspecialchars($entreprise['id_entreprise']); ?>">
                        
                        <div class="image-fond">
                            <!-- htmlspecialchars() protège contre les failles XSS -->
                            <img src="/images/entreprises/fond/<?= htmlspecialchars($entreprise['image_fond']) ?>" 
                                 alt="Fond <?php echo htmlspecialchars($entreprise['nom']); ?>">
                        </div>

                        <div class="contenu-carte">
                            <div class="header_carte">
                                <img src="/images/entreprises/logo/<?= htmlspecialchars($entreprise['image_logo']) ?>" 
                                     alt="Logo" class="logo-mini">
                                <h3 class="name-entreprise">
                                    <?php echo htmlspecialchars($entreprise['nom']); ?>
                                </h3>
                            </div>

                            <div class="footer-carte">
                                <span class="nb-jobs">
                                    <?php echo htmlspecialchars($entreprise['nb_offres']); ?> offres
                                </span>
                                <span class="btn-decouvrir">Découvrir</span>
                            </div>
                        </div>
                    </a>    
                </article>
            <?php endforeach; ?>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>