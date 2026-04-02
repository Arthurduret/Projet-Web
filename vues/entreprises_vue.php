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

        <form action="/index.php" method="GET" class="recherche-entreprises">
            <input type="hidden" name="page" value="entreprises">
            <div class="search-entreprise">
                <input type="text"
                    name="nom"
                    placeholder="Rechercher une entreprise..."
                    value="<?= htmlspecialchars($nom ?? '') ?>">

                <select name="tri">
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

                <button type="submit">🔍 Rechercher</button>
            </div>
        </form>

        <div class="header-entreprise">
            <p class="nb-resultats-entreprises">
                <strong><?= $nb_entreprises ?></strong> entreprise<?= $nb_entreprises > 1 ? 's' : '' ?> trouvée<?= $nb_entreprises > 1 ? 's' : '' ?>
            </p>
        </div>

        <?php if ($nb_entreprises === 0): ?>
            <p class="aucun-resultat-entreprise">Aucune entreprise ne correspond à votre recherche.</p>
        <?php else: ?>
            <div class="grille-entreprises">
                <?php foreach ($entreprises as $entreprise): ?>
                    <article class="carte-entreprise">
                        <a href="/index.php?page=entreprises&action=show&id=<?= htmlspecialchars($entreprise['id_entreprise']) ?>">
                            <div class="image-fond">
                                <img src="/images/entreprises/fond/<?= htmlspecialchars($entreprise['image_fond']) ?>"
                                     alt="Fond <?= htmlspecialchars($entreprise['nom']) ?>">
                            </div>

                            <div class="contenu-carte">
                                <div class="header_carte">
                                    <img src="/images/entreprises/logo/<?= htmlspecialchars($entreprise['image_logo']) ?>"
                                         alt="Logo" class="logo-mini">
                                    <h3 class="name-entreprise">
                                        <?= htmlspecialchars($entreprise['nom']) ?>
                                    </h3>
                                </div>

                                <div class="footer-carte">
                                    <span class="nb-jobs">
                                        <?= htmlspecialchars($entreprise['nb_offres']) ?> offres
                                    </span>
                                    <span class="btn-decouvrir">Découvrir</span>
                                </div>
                            </div>
                        </a>

                        <div class="carte-details">
                            <button class="btn-toggle-details" onclick="toggleDetails(this)">
                                ▼ Voir les détails
                            </button>
                            <div class="details-contenu" style="display:none;">
                                <ul>
                                    <li><strong>Nom :</strong> <?= htmlspecialchars($entreprise['nom']) ?></li>
                                    <li><strong>Description :</strong> <?= htmlspecialchars($entreprise['description'] ?? 'Non renseignée') ?></li>
                                    <li><strong>Email :</strong> <?= htmlspecialchars($entreprise['email'] ?? 'Non renseigné') ?></li>
                                    <li><strong>Téléphone :</strong> <?= htmlspecialchars($entreprise['tel'] ?? 'Non renseigné') ?></li>
                                    <li><strong>Candidatures :</strong> <?= $entreprise['nb_candidatures'] ?? 0 ?></li>
                                    <li><strong>Note moyenne :</strong> 
                                        <?= $entreprise['moyenne_eval'] ? $entreprise['moyenne_eval'] . ' / 5 ⭐' : 'Pas encore évaluée' ?>
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
        if (details.style.display === 'none') {
            details.style.display = 'block';
            btn.textContent = '▲ Masquer les détails';
        } else {
            details.style.display = 'none';
            btn.textContent = '▼ Voir les détails';
        }
    }
    </script>
</body>
</html>