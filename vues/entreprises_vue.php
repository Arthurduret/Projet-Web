<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title><?= htmlspecialchars($meta_title ?? 'Jobeo | Nos Entreprises partenaires') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Découvrez les entreprises partenaires de Jobeo qui recrutent des stagiaires CESI en région PACA.') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_keywords ?? 'entreprises stage, partenaires CESI, recrutement stagiaire PACA') ?>">
    <meta name="author" content="Web4All">
    <meta name="robots" content="index, follow">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/jobeo/HeadLogoJobeo.png">

    <!-- CSS -->
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/style_entreprises.css">
    <link rel="stylesheet" href="/css/header_footer.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>

        <!-- ===== EN-TÊTE ===== -->
        <div class="header-entreprise">
            <h1>Explorez les entreprises qui recrutent</h1>
        </div>

        <!-- ===== RECHERCHE + TRI ===== -->
        <form action="/index.php" method="GET" class="recherche-entreprises" role="search">
            <input type="hidden" name="page" value="entreprises">
            <div class="search-entreprise">
                <input type="text"
                       name="nom"
                       placeholder="Rechercher une entreprise..."
                       value="<?= htmlspecialchars($nom ?? '') ?>"
                       aria-label="Rechercher une entreprise par nom">

                <select name="tri" aria-label="Trier les entreprises">
                    <option value="">Trier par...</option>
                    <option value="nb_candidatures" <?= ($tri ?? '') === 'nb_candidatures' ? 'selected' : '' ?>>
                        Nombre de candidatures
                    </option>
                    <option value="moyenne_eval" <?= ($tri ?? '') === 'moyenne_eval' ? 'selected' : '' ?>>
                        Note moyenne
                    </option>
                    <option value="nb_offres" <?= ($tri ?? '') === 'nb_offres' ? 'selected' : '' ?>>
                        Nombre d'offres
                    </option>
                </select>

                <button type="submit" aria-label="Lancer la recherche">🔍 Rechercher</button>
            </div>
        </form>

        <!-- ===== NOMBRE DE RÉSULTATS ===== -->
        <div class="header-entreprise">
            <p class="nb-resultats-entreprises">
                <strong><?= (int)$nb_entreprises ?></strong>
                entreprise<?= $nb_entreprises > 1 ? 's' : '' ?>
                trouvée<?= $nb_entreprises > 1 ? 's' : '' ?>
            </p>
        </div>

        <!-- ===== LISTE ===== -->
        <?php if ($nb_entreprises === 0): ?>
            <p class="aucun-resultat-entreprise">Aucune entreprise ne correspond à votre recherche.</p>

        <?php else: ?>
            <div class="grille-entreprises">
                <?php foreach ($entreprises as $entreprise): ?>
                    <article class="carte-entreprise">

                        <a href="/index.php?page=entreprises&action=show&id=<?= htmlspecialchars($entreprise['id_entreprise']) ?>"
                           aria-label="Découvrir <?= htmlspecialchars($entreprise['nom']) ?>">

                            <!-- Image de fond -->
                            <div class="image-fond">
                                <img src="/images/entreprises/fond/<?= htmlspecialchars($entreprise['image_fond']) ?>"
                                     alt="Image de fond <?= htmlspecialchars($entreprise['nom']) ?>"
                                     loading="lazy">
                            </div>

                            <!-- Contenu carte -->
                            <div class="contenu-carte">
                                <div class="header_carte">
                                    <img src="/images/entreprises/logo/<?= htmlspecialchars($entreprise['image_logo']) ?>"
                                         alt="Logo <?= htmlspecialchars($entreprise['nom']) ?>"
                                         class="logo-mini"
                                         loading="lazy">
                                    <h3 class="name-entreprise">
                                        <?= htmlspecialchars($entreprise['nom']) ?>
                                    </h3>
                                </div>

                                <div class="footer-carte">
                                    <span class="nb-jobs">
                                        <?= (int)$entreprise['nb_offres'] ?>
                                        offre<?= $entreprise['nb_offres'] > 1 ? 's' : '' ?>
                                    </span>
                                    <span class="btn-decouvrir">Découvrir</span>
                                </div>
                            </div>
                        </a>

                        <!-- Détails déroulants -->
                        <div class="carte-details">
                            <button class="btn-toggle-details"
                                    onclick="toggleDetails(this)"
                                    aria-expanded="false">
                                ▼ Voir les détails
                            </button>
                            <div class="details-contenu" style="display:none;">
                                <ul>
                                    <li><strong>Nom :</strong> <?= htmlspecialchars($entreprise['nom']) ?></li>
                                    <li><strong>Description :</strong> <?= htmlspecialchars($entreprise['description'] ?? 'Non renseignée') ?></li>
                                    <li><strong>Email :</strong> <?= htmlspecialchars($entreprise['email'] ?? 'Non renseigné') ?></li>
                                    <li><strong>Téléphone :</strong> <?= htmlspecialchars($entreprise['tel'] ?? 'Non renseigné') ?></li>
                                    <li><strong>Candidatures :</strong> <?= (int)($entreprise['nb_candidatures'] ?? 0) ?></li>
                                    <li><strong>Note moyenne :</strong>
                                        <?= $entreprise['moyenne_eval']
                                            ? htmlspecialchars($entreprise['moyenne_eval']) . ' / 5 ⭐'
                                            : 'Pas encore évaluée' ?>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script>
    function toggleDetails(btn) {
        const details = btn.nextElementSibling;
        const isOpen  = details.style.display === 'none';
        details.style.display = isOpen ? 'block' : 'none';
        btn.textContent       = isOpen ? '▲ Masquer les détails' : '▼ Voir les détails';
        btn.setAttribute('aria-expanded', String(isOpen));
    }
    </script>
</body>
</html>