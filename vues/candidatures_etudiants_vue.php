<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | Candidatures Étudiants</title>
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/offres_emplois.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>

        <section class="recherche-offres">
            <form action="/index.php" method="GET">
                <input type="hidden" name="page" value="candidatures_etudiants">
                <div class="search-bar">
                    <div class="input-group">
                        <label for="search">Rechercher</label>
                        <input type="text"
                               name="search"
                               id="search"
                               placeholder="Nom, prénom, offre..."
                               value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                    <button type="submit" class="btn-search">
                        <img src="/images/jobeo/LoupeLogo.png" alt="Rechercher">
                    </button>
                </div>
            </form>
        </section>

        <section class="liste-offres">

            <p class="nb-resultats">
                <strong><?= $total ?? 0 ?></strong> candidature<?= ($total ?? 0) > 1 ? 's' : '' ?> trouvée<?= ($total ?? 0) > 1 ? 's' : '' ?>
            </p>

            <?php if (empty($candidatures)): ?>
                <p class="aucun-resultat">Aucune candidature trouvée.</p>
            <?php else: ?>
                <?php foreach ($candidatures as $c): ?>
                    <article class="carte-offre">

                        <div class="offre-image">
                            <img src="/images/jobeo/logo_profil.png" alt="Avatar">
                        </div>

                        <div class="offre-contenu">
                            <h2 class="offre-titre">
                                <?= htmlspecialchars($c['prenom']) ?>
                                <?= htmlspecialchars($c['nom']) ?>
                            </h2>
                            <p class="offre-entreprise"><?= htmlspecialchars($c['email']) ?></p>

                            <div class="offre-tags">
                                <span>💼 <?= htmlspecialchars($c['titre_offre']) ?></span>
                                <span>🏢 <?= htmlspecialchars($c['nom_entreprise']) ?></span>
                            </div>

                            <?php if (!empty($c['lettre_motivation'])): ?>
                                <p class="offre-date">
                                    📄 Lettre : <?= htmlspecialchars($c['lettre_motivation']) ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="offre-action">
                            <a href="/index.php?page=offres_emplois&action=show&id=<?= $c['id_offre'] ?? '' ?>"
                               class="btn-voir">Voir l'offre</a>
                        </div>

                    </article>
                <?php endforeach; ?>
            <?php endif; ?>

        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>