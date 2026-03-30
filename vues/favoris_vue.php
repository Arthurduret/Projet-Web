<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | Mes Favoris</title>
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/offres_emplois.css">
    <link rel="stylesheet" href="/css/header_footer.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <!-- EN-TÊTE -->
        <div class="liste-offres">
            <h1>❤️ Mes offres favorites</h1>

            <?php if (empty($favoris)): ?>
                <p class="aucun-resultat">
                    Vous n'avez pas encore d'offres en favoris.<br>
                    <a href="/index.php?page=offres_emplois">Parcourir les offres →</a>
                </p>

            <?php else: ?>
                <p class="nb-resultats">
                    <strong><?= $nb_favoris ?></strong> offre<?= $nb_favoris > 1 ? 's' : '' ?> en favori
                </p>

                <?php foreach ($favoris as $offre): ?>
                    <article class="carte-offre">

                        <!-- LOGO -->
                        <div class="offre-image">
                            <img src="/images/entreprises/logo/<?= htmlspecialchars($offre['image_logo']) ?>"
                                 alt="Logo <?= htmlspecialchars($offre['nom_entreprise']) ?>">
                        </div>

                        <!-- CONTENU -->
                        <div class="offre-contenu">
                            <h2 class="offre-titre"><?= htmlspecialchars($offre['titre']) ?></h2>
                            <p class="offre-entreprise"><?= htmlspecialchars($offre['nom_entreprise']) ?></p>

                            <div class="offre-tags">
                                <?php if (!empty($offre['localisation'])): ?>
                                    <span>📍 <?= htmlspecialchars($offre['localisation']) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($offre['duree'])): ?>
                                    <span>⏱ <?= htmlspecialchars($offre['duree']) ?> mois</span>
                                <?php endif; ?>
                                <?php if (!empty($offre['salaire'])): ?>
                                    <span>💶 <?= htmlspecialchars($offre['salaire']) ?> €/mois</span>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($offre['competences'])): ?>
                                <div class="offre-competences">
                                    <?php foreach (explode(', ', $offre['competences']) as $comp): ?>
                                        <span class="tag-competence"><?= htmlspecialchars($comp) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- ACTIONS -->
                        <div class="offre-action">
                            <a href="/index.php?page=offres_emplois&action=show&id=<?= htmlspecialchars($offre['id_offre']) ?>"
                               class="btn-voir">Voir l'offre</a>

                            <a href="/index.php?page=favoris&action=toggle&id=<?= htmlspecialchars($offre['id_offre']) ?>"
                               class="btn-retirer-favori"
                               onclick="return confirm('Retirer cette offre des favoris ?')">
                               ⭐ Retirer
                            </a>
                        </div>

                    </article>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>