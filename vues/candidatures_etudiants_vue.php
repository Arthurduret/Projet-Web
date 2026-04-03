<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title><?= htmlspecialchars($meta_title ?? 'Jobeo | Candidatures Étudiants') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Consultez les candidatures de vos étudiants aux offres de stage sur Jobeo.') ?>">
    <meta name="robots" content="noindex, nofollow">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/jobeo/HeadLogoJobeo.png">

    <!-- CSS -->
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/offres_emplois.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>

        <!-- ===== BARRE DE RECHERCHE ===== -->
        <section class="recherche-offres" aria-label="Recherche de candidatures">
            <form action="/index.php" method="GET" role="search">
                <input type="hidden" name="page" value="candidature">
                <input type="hidden" name="action" value="pilote">
                <div class="search-bar">
                    <div class="input-group">
                        <label for="search">Rechercher</label>
                        <input type="text"
                               name="search"
                               id="search"
                               placeholder="Nom, prénom, offre..."
                               value="<?= htmlspecialchars($search ?? '') ?>"
                               aria-label="Rechercher une candidature">
                    </div>
                    <button type="submit" class="btn-search" aria-label="Lancer la recherche">
                        <img src="/images/jobeo/LoupeLogo.png" alt="Rechercher">
                    </button>
                </div>
            </form>
        </section>

        <!-- ===== LISTE DES CANDIDATURES ===== -->
        <section class="liste-offres" aria-labelledby="titre-candidatures">

            <h1 id="titre-candidatures" class="nb-resultats">
                <strong><?= (int)($total ?? 0) ?></strong>
                candidature<?= ($total ?? 0) > 1 ? 's' : '' ?>
                trouvée<?= ($total ?? 0) > 1 ? 's' : '' ?>
            </h1>

            <?php if (empty($candidatures)): ?>
                <p class="aucun-resultat">Aucune candidature trouvée.</p>

            <?php else: ?>
                <?php foreach ($candidatures as $c): ?>
                    <article class="carte-offre">

                        <!-- Avatar étudiant -->
                        <div class="offre-image">
                            <img src="/images/jobeo/logo_profil.png"
                                 alt="Avatar de <?= htmlspecialchars($c['prenom']) ?> <?= htmlspecialchars($c['nom']) ?>">
                        </div>

                        <!-- Infos candidature -->
                        <div class="offre-contenu">
                            <h2 class="offre-titre">
                                <?= htmlspecialchars($c['prenom']) ?>
                                <?= htmlspecialchars($c['nom']) ?>
                            </h2>

                            <p class="offre-entreprise">
                                <?= htmlspecialchars($c['email']) ?>
                            </p>

                            <div class="offre-tags">
                                <span>💼 <?= htmlspecialchars($c['titre_offre']) ?></span>
                                <span>🏢 <?= htmlspecialchars($c['nom_entreprise']) ?></span>
                            </div>

                            <!-- Documents -->
                            <div class="offre-competences" style="margin-top:0.5rem;">
                                <?php if (!empty($c['cv'])): ?>
                                    <a href="/uploads/candidatures/<?= htmlspecialchars($c['cv']) ?>"
                                       target="_blank"
                                       class="tag-competence"
                                       aria-label="Télécharger le CV de <?= htmlspecialchars($c['prenom']) ?>">
                                        📄 CV
                                    </a>
                                <?php endif; ?>

                                <?php if (!empty($c['lettre_motivation'])): ?>
                                    <a href="/uploads/candidatures/<?= htmlspecialchars($c['lettre_motivation']) ?>"
                                       target="_blank"
                                       class="tag-competence"
                                       aria-label="Télécharger la lettre de motivation de <?= htmlspecialchars($c['prenom']) ?>">
                                        📝 Lettre de motivation
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="offre-action">
                            <a href="/index.php?page=offres_emplois&action=show&id=<?= htmlspecialchars((string)($c['id_offre'] ?? '')) ?>"
                               class="btn-voir">
                                Voir l'offre
                            </a>
                        </div>

                    </article>
                <?php endforeach; ?>
            <?php endif; ?>

        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>