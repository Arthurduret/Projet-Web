<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/images/jobeo/HeadLogoJobeo.png">
    <title>Jobeo | Accueil</title>

    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/style_index.css">
    <link rel="stylesheet" href="/css/header_footer.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <section class="Hero">
            <div>
                <img src="/images/jobeo/ImageFondPageAccueil.png" alt="Image d'accueil">
            </div>
            <div class="content-wrapper">
                <form class="search-bar" method="GET" action="/index.php">
                    <input type="hidden" name="page" value="offres_emplois">
                    <div class="input-group">
                        <label for="ou">Lieu</label>
                        <input type="text" name="ou" id="ou" placeholder="Marseille 13001">
                    </div>
                    <div class="input-group">
                        <label for="quoi">Domaine</label>
                        <input type="text" name="quoi" id="quoi" placeholder="Data">
                    </div>
                    <button type="submit" class="btn-search">
                        <img src="/images/jobeo/LoupeLogo.png" alt="Rechercher">
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
                        <a href="/index.php?page=entreprises&action=show&id=<?php echo htmlspecialchars($entreprise['id_entreprise']); ?>">                            
                            <div class="image-fond">
                                <img src="/images/entreprises/fond/<?php echo htmlspecialchars($entreprise['image_fond']); ?>" 
                                     alt="Fond <?php echo htmlspecialchars($entreprise['nom']); ?>">
                            </div>

                            <div class="contenu-carte">
                                <div class="header_carte">
                                    <img src="<?php 
                                        $chemin = '/images/entreprises/logo/' . $entreprise['image_logo'];
                                        echo file_exists($_SERVER['DOCUMENT_ROOT'] . $chemin) 
                                            ? htmlspecialchars($chemin)
                                            : '/images/default_logo.png';?>"class="logo-mini" alt="Logo <?php echo htmlspecialchars($entreprise['nom']); ?>">
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
        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>