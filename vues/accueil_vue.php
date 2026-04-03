<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title><?= htmlspecialchars($meta_title ?? 'Jobeo | Accueil') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Jobeo — Trouvez votre stage idéal parmi nos offres en région PACA.') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_keywords ?? 'stage, alternance, emploi, CESI, PACA, Marseille') ?>">
    <meta name="author" content="Web4All">
    <meta name="robots" content="index, follow">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($meta_title ?? 'Jobeo | Accueil') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($meta_description ?? 'Jobeo — Trouvez votre stage idéal parmi nos offres en région PACA.') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://jobeo.local/">
    <meta property="og:image" content="https://jobeo.local/images/jobeo/LogoJobeo.png">
    <meta property="og:site_name" content="Jobeo">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/jobeo/HeadLogoJobeo.png">

    <!-- CSS -->
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/style_index.css">
    <link rel="stylesheet" href="/css/header_footer.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>

        <!-- ===== HERO + BARRE DE RECHERCHE ===== -->
        <section class="Hero" aria-label="Recherche de stage">
            <div>
                <img src="/images/jobeo/ImageFondPageAccueil.png" 
                     alt="Plateforme de stages Jobeo — étudiants et entreprises en région PACA">
            </div>
            <div class="content-wrapper">
                <form class="search-bar" method="GET" action="/index.php" role="search">
                    <input type="hidden" name="page" value="offres_emplois">

                    <div class="input-group">
                        <label for="ou">Où ?</label>
                        <input type="text" name="ou" id="ou" 
                               placeholder="Marseille 13001"
                               aria-label="Localisation du stage">
                    </div>

                    <div class="input-group">
                        <label for="quoi">Quoi ?</label>
                        <input type="text" name="quoi" id="quoi" 
                               placeholder="Data, Développeur..."
                               aria-label="Domaine ou titre du stage">
                    </div>

                    <button type="submit" class="btn-search" aria-label="Lancer la recherche">
                        <img src="/images/jobeo/LoupeLogo.png" alt="Rechercher">
                    </button>
                </form>
            </div>
        </section>

        <!-- ===== NOS ENTREPRISES ===== -->
        <section aria-labelledby="titre-entreprises">
            <div class="header-entreprise">
                <h1 id="titre-entreprises">Nos Entreprises</h1>
            </div>

            <?php if (empty($entreprises_accueil)): ?>
                <p style="text-align:center; color:#888; margin:2rem 0;">
                    Aucune entreprise disponible pour le moment.
                </p>
            <?php else: ?>
                <div class="grille-entreprises">
                    <?php foreach ($entreprises_accueil as $entreprise): ?>
                        <article class="carte-entreprise    ">
                            <a href="/index.php?page=entreprises&action=show&id=<?= htmlspecialchars($entreprise['id_entreprise']) ?>"
                               aria-label="Découvrir <?= htmlspecialchars($entreprise['nom']) ?>">

                                <!-- Image de fond -->
                                <div class="image-fond">
                                    <img src="/images/entreprises/fond/<?= htmlspecialchars($entreprise['image_fond']) ?>"
                                         alt="Image de fond <?= htmlspecialchars($entreprise['nom']) ?>"
                                         loading="lazy">
                                </div>

                                <!-- Contenu de la carte -->
                                <div class="contenu-carte">
                                    <div class="header_carte">
                                        <?php
                                            $chemin = '/images/entreprises/logo/' . $entreprise['image_logo'];
                                            $src    = file_exists($_SERVER['DOCUMENT_ROOT'] . $chemin)
                                                ? htmlspecialchars($chemin)
                                                : '/images/jobeo/default_logo.png';
                                        ?>
                                        <img src="<?= $src ?>"
                                             class="logo-mini"
                                             alt="Logo <?= htmlspecialchars($entreprise['nom']) ?>"
                                             loading="lazy">
                                        <h3 class="name-entreprise">
                                            <?= htmlspecialchars($entreprise['nom']) ?>
                                        </h3>
                                    </div>

                                    <div class="footer-carte">
                                        <span class="nb-jobs">
                                            <?= htmlspecialchars($entreprise['nb_offres']) ?>
                                            offre<?= $entreprise['nb_offres'] > 1 ? 's' : '' ?>
                                        </span>
                                        <span class="btn-decouvrir">Découvrir</span>
                                    </div>
                                </div>

                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>