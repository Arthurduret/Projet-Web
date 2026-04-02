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
        <div class="liste-offres">
            <h1>🧡 Mes offres favorites</h1>

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

                        <button class="btn-coeur active" data-id="<?= htmlspecialchars($offre['id_offre']) ?>">
                            🧡
                        </button>


                        <div class="offre-image">
                            <img src="/images/entreprises/logo/<?= htmlspecialchars($offre['image_logo']) ?>"
                                 alt="Logo <?= htmlspecialchars($offre['nom_entreprise']) ?>">
                        </div>

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

                        <div class="offre-action">
                            <a href="/index.php?page=offres_emplois&action=show&id=<?= htmlspecialchars($offre['id_offre']) ?>"
                               class="btn-voir">Voir l'offre</a>

                            <a href="/index.php?page=candidature&action=create&id=<?= htmlspecialchars($offre['id_offre']) ?>"
                                class="btn-postuler" style="margin-top: 0.5rem;">Postuler</a>
                        </div>

                    </article>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script>
    document.querySelectorAll('.btn-coeur').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const carte = this.closest('.carte-offre');

            fetch('/index.php?page=favoris&action=toggle&id=' + id, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (!data.favori) {
                    carte.style.transition = 'opacity 0.3s ease';
                    carte.style.opacity = '0';
                    setTimeout(() => carte.remove(), 300);
                }
            });
        });
    });
    </script>
</body>
</html>