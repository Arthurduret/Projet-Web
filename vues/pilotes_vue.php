<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | Les Pilotes</title>
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/offres_emplois.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <section class="recherche-offres">
            <form action="/index.php" method="GET">
                <input type="hidden" name="page" value="pilotes">
                <div class="search-bar">
                    <div class="input-group">
                        <label for="search">Rechercher</label>
                        <input type="text"
                               name="search"
                               id="search"
                               placeholder="Nom, prénom, email..."
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
                <strong><?= $total ?? 0 ?></strong> pilote<?= ($total ?? 0) > 1 ? 's' : '' ?> trouvé<?= ($total ?? 0) > 1 ? 's' : '' ?>
            </p>

            <?php if (empty($pilotes)): ?>
                <p class="aucun-resultat">Aucun pilote trouvé.</p>
            <?php else: ?>
                <?php foreach ($pilotes as $pilote): ?>
                    <article class="carte-offre">

                        <div class="offre-image">
                            <img src="/images/jobeo/logo_profil.png" alt="Avatar">
                        </div>

                        <div class="offre-contenu">
                            <h2 class="offre-titre">
                                <?= htmlspecialchars($pilote['prenom']) ?>
                                <?= htmlspecialchars($pilote['nom']) ?>
                            </h2>
                            <p class="offre-entreprise"><?= htmlspecialchars($pilote['email']) ?></p>
                            <div class="offre-tags">
                                <span>🎓 Pilote</span>
                            </div>
                        </div>

                        <div class="offre-action" style="display:flex; flex-direction:column; gap:0.5rem;">
                            <a href="/index.php?page=pilotes&action=edit&id=<?= $pilote['id_utilisateur'] ?>"
                               class="btn-voir">Modifier</a>
                            <a href="/index.php?page=pilotes&action=delete&id=<?= $pilote['id_utilisateur'] ?>"
                               class="btn-supprimer"
                               onclick="return confirm('Supprimer ce pilote ?')">Supprimer</a>
                        </div>

                    </article>
                <?php endforeach; ?>
            <?php endif; ?>

        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>