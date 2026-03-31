<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | Mes Étudiants</title>
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/offres_emplois.css">
    <link rel="stylesheet" href="/css/etudiants.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>

        <!-- ===== BARRE DE RECHERCHE ===== -->
        <section class="recherche-offres">
            <form action="/index.php" method="GET">
                <input type="hidden" name="page" value="etudiants">
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

        <!-- ===== LISTE DES ÉTUDIANTS ===== -->
        <section class="liste-offres">

            <p class="nb-resultats">
                <strong><?= $total ?? 0 ?></strong> étudiant<?= ($total ?? 0) > 1 ? 's' : '' ?> trouvé<?= ($total ?? 0) > 1 ? 's' : '' ?>
            </p>

            <?php if (empty($etudiants)): ?>
                <p class="aucun-resultat">Aucun étudiant trouvé.</p>

            <?php else: ?>
                <?php foreach ($etudiants as $etudiant): ?>
                    <article class="carte-offre">

                        <!-- AVATAR -->
                        <div class="offre-image">
                            <img src="/images/jobeo/logo_profil.png" alt="Avatar">
                        </div>

                        <!-- CONTENU -->
                        <div class="offre-contenu">
                            <h2 class="offre-titre">
                                <?= htmlspecialchars($etudiant['prenom']) ?>
                                <?= htmlspecialchars($etudiant['nom']) ?>
                            </h2>
                            <p class="offre-entreprise"><?= htmlspecialchars($etudiant['email']) ?></p>

                            <div class="offre-tags">
                                <span>🎓 Étudiant</span>
                            </div>
                        </div>

                        <!-- ACTIONS -->
                        <div class="offre-action" style="display:flex; flex-direction:column; gap:0.5rem;">
                            <a href="/index.php?page=etudiants&action=edit&id=<?= $etudiant['id_utilisateur'] ?>"
                               class="btn-voir">Modifier</a>
                            <a href="/index.php?page=etudiants&action=delete&id=<?= $etudiant['id_utilisateur'] ?>"
                               class="btn-supprimer"
                               onclick="return confirm('Supprimer cet étudiant ?')">Supprimer</a>
                        </div>

                    </article>
                <?php endforeach; ?>
            <?php endif; ?>

        </section>

    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>