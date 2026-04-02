<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | Mes Candidatures</title>
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/offres_emplois.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <section class="recherche-offres">
            <form action="/index.php" method="GET">
                <input type="hidden" name="page" value="candidature">
                <div class="search-bar">
                    <div class="input-group">
                        <label for="quoi">Quoi ?</label>
                        <input type="text" name="quoi" id="quoi" placeholder="Titre postulé..." value="<?= htmlspecialchars($quoi ?? ''); ?>">
                    </div>
                    <div class="separateur"></div>
                    <div class="input-group">
                        <label for="ou">Où ?</label>
                        <input type="text" name="ou" id="ou" placeholder="Ville..." value="<?= htmlspecialchars($ou ?? ''); ?>">
                    </div>
                    <button type="submit" class="btn-search">
                        <img src="/images/jobeo/LoupeLogo.png" alt="Rechercher">
                    </button>
                </div>
            </form>
        </section>

        <section class="liste-offres">
            <h1 class="nb-resultats">Mes candidatures envoyées</h1>

            <?php if (empty($candidatures)): ?>
                <p class="aucun-resultat">Vous n'avez pas encore postulé. <a href="/index.php?page=offres_emplois">Voir les offres.</a></p>
            <?php else: ?>

                <?php foreach ($candidatures as $c): ?>
                    <article class="carte-offre">

                        <div class="offre-contenu">
                            <h2 class="offre-titre"><?= htmlspecialchars($c['titre']); ?></h2>
                            <p class="offre-entreprise"><?= htmlspecialchars($c['nom_entreprise']); ?></p>

                            <div class="candidature-fichiers">
                                <?php if (!empty($c['cv'])): ?>
                                    <div class="file-badge-wrapper">
                                        <a href="/uploads/candidatures/<?= trim($c['cv']) ?>?t=<?= time() ?>" 
                                           download="<?= trim($c['cv']) ?>" 
                                           class="tag-candidature">
                                            📄 Télécharger mon CV
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($c['lettre_motivation'])): ?>
                                    <div class="file-badge-wrapper">
                                        <a href="/uploads/candidatures/<?= trim($c['lettre_motivation']) ?>?t=<?= time() ?>" 
                                           download="<?= trim($c['lettre_motivation']) ?>" 
                                           class="tag-candidature">
                                            ✉️ Télécharger ma lettre
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="offre-action">
                            <a href="/index.php?page=offres_emplois&action=show&id=<?= $c['id_offre']; ?>" class="btn-voir">Voir l'offre</a>
                            <span class="status-envoye">✔️ Envoyée</span>
                        </div>

                    </article>
                <?php endforeach; ?>

            <?php endif; ?>
        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>