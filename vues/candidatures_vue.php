<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title><?= htmlspecialchars($meta_title ?? 'Jobeo | Mes Candidatures') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Suivez l\'état de vos candidatures aux offres de stage sur Jobeo.') ?>">
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

        <!-- ===== EN-TÊTE ===== -->
        <section class="liste-offres" aria-labelledby="titre-candidatures">

            <h1 id="titre-candidatures" class="nb-resultats">
                Mes candidatures envoyées
                <span style="font-size:1rem; font-weight:400; color:#888;">
                    (<?= count($candidatures) ?> au total)
                </span>
            </h1>

            <?php if (empty($candidatures)): ?>
                <p class="aucun-resultat">
                    Vous n'avez pas encore postulé.
                    <a href="/index.php?page=offres_emplois">Voir les offres →</a>
                </p>

            <?php else: ?>
                <?php foreach ($candidatures as $c): ?>
                    <article class="carte-offre">

                        <!-- CONTENU -->
                        <div class="offre-contenu">
                            <h2 class="offre-titre">
                                <?= htmlspecialchars($c['titre']) ?>
                            </h2>
                            <p class="offre-entreprise">
                                <?= htmlspecialchars($c['nom_entreprise']) ?>
                            </p>

                            <!-- Documents téléchargeables -->
                            <div class="offre-competences" style="margin-top:0.5rem;">
                                <?php if (!empty($c['cv'])): ?>
                                    <a href="/uploads/candidatures/<?= htmlspecialchars(trim($c['cv'])) ?>"
                                       download="<?= htmlspecialchars(trim($c['cv'])) ?>"
                                       class="tag-competence"
                                       aria-label="Télécharger mon CV">
                                        📄 Mon CV
                                    </a>
                                <?php endif; ?>

                                <?php if (!empty($c['lettre_motivation'])): ?>
                                    <a href="/uploads/candidatures/<?= htmlspecialchars(trim($c['lettre_motivation'])) ?>"
                                       download="<?= htmlspecialchars(trim($c['lettre_motivation'])) ?>"
                                       class="tag-competence"
                                       aria-label="Télécharger ma lettre de motivation">
                                        ✉️ Ma lettre de motivation
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- ACTIONS -->
                        <div class="offre-action">
                            <a href="/index.php?page=offres_emplois&action=show&id=<?= htmlspecialchars((string)$c['id_offre']) ?>"
                               class="btn-voir">
                                Voir l'offre
                            </a>
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