<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/public/images/jobeo/HeadLogoJobeo.png">
    <title>Jobeo | Accueil</title>

    <link rel="stylesheet" href="/public/css/style_global.css">
    <link rel="stylesheet" href="/public/css/style_index.css">
    <link rel="stylesheet" href="/public/css/header_footer.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <section class="Hero">
            <div>
                <img src="/public/images/jobeo/ImageFondPageAccueil.png" alt="Image d'accueil">
            </div>
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
                        <img src="/public/images/jobeo/LoupeLogo.png" alt="Rechercher">
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
                        <a href="/index.php?page=fiche_entreprise&id=<?php echo htmlspecialchars($entreprise['id']); ?>">
                            
                            <div class="image-fond">
                                <img src="/public/images/entreprises/fond/<?php echo htmlspecialchars($entreprise['image_fond']); ?>" 
                                     alt="Fond <?php echo htmlspecialchars($entreprise['nom']); ?>">
                            </div>

                            <div class="contenu-carte">
                                <div class="header_carte">
                                    <img src="/public/images/entreprises/logo/<?php echo htmlspecialchars($entreprise['image_logo']); ?>" 
                                         class="logo-mini"
                                         alt="Logo <?php echo htmlspecialchars($entreprise['nom']); ?>">
                                    <h3 class="name-entreprise">
                                        <?php echo htmlspecialchars($entreprise['nom']); ?>
                                    </h3>
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
        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>