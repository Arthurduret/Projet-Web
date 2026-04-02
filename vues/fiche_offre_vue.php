<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | <?php echo htmlspecialchars($offre['titre']); ?></title>

    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/offre.css">
    <link rel="stylesheet" href="/css/header_footer.css">
</head>

<body>

    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="fiche-offre-main">

        <a href="/index.php?page=offres_emplois" class="btn-retour">← Retour aux offres</a>

        <section class="bloc-infos-full">

            <div class="fiche-header">
                <div class="fiche-logo">
                    <img src="/images/entreprises/logo/<?php echo htmlspecialchars($offre['image_logo']); ?>"
                        alt="Logo <?php echo htmlspecialchars($offre['nom_entreprise']); ?>">
                </div>

                <div>
                    <h1><?php echo htmlspecialchars($offre['titre']); ?></h1>
                    <p class="entreprise"><?php echo htmlspecialchars($offre['nom_entreprise']); ?></p>
                </div>
            </div>

            <div class="tags">
                <?php if (!empty($offre['localisation'])): ?>
                    <span>📍 <?php echo htmlspecialchars($offre['localisation']); ?></span>
                <?php endif; ?>
                <span>⏱ <?php echo htmlspecialchars($offre['duree']); ?> mois</span>
                <span>💶 <?php echo htmlspecialchars($offre['salaire']); ?> €/mois</span>
                <span>👥 <?= $nb_candidatures ?> candidature<?= $nb_candidatures > 1 ? 's' : '' ?></span>
            </div>

        </section>
        <div class="fiche-offre-container">

            <!-- COLONNE GAUCHE -->
            <div class="fiche-offre-gauche">

                <?php if (!empty($offre['competences'])): ?>
                <section class="bloc">
                    <h2>Compétences requises</h2>

                    <div class="competences">
                        <?php foreach (explode(', ', $offre['competences']) as $competence): ?>
                            <span class="tag-skill"><?php echo htmlspecialchars($competence); ?></span>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>

                <section class="bloc">
                    <h2>Description du poste</h2>

                    <p class="description">
                        <?php echo nl2br(htmlspecialchars($offre['description'])); ?>
                    </p>
                </section>

                <p class="date">
                    Publiée le 
                    <?php
                        $date = new DateTime($offre['date_offre']);
                        $mois = [
                            1 => 'janvier', 'février', 'mars', 'avril',
                            'mai', 'juin', 'juillet', 'août',
                            'septembre', 'octobre', 'novembre', 'décembre'
                        ];
                        echo $date->format('d') . ' ' . $mois[(int)$date->format('m')] . ' ' . $date->format('Y');
                    ?>
                </p>

            </div>

            <aside class="fiche-offre-droite">

                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'etudiant'): ?>
                    <a href="/index.php?page=candidature&action=create&id=<?= htmlspecialchars($offre['id_offre']) ?>"
                    class="btn-postuler">Postuler à cette offre</a>

                    <button class="btn-wishlist btn-coeur-fiche <?= $estFavori ? 'active' : '' ?>"
                            data-id="<?= htmlspecialchars($offre['id_offre']) ?>">
                        <?= $estFavori ? '❤️ Retirer des favoris' : '🤍 Ajouter aux favoris' ?>
                    </button>
                <?php endif; ?>

                <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'pilote'])): ?>
                    <a href="/index.php?page=offres_emplois&action=edit&id=<?= htmlspecialchars($offre['id_offre']) ?>"
                    class="btn-modifier">✏️ Modifier l'offre</a>
                <?php endif; ?>

                <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'pilote'])): ?>
                    <a href="/index.php?page=offres_emplois&action=delete&id=<?= htmlspecialchars($offre['id_offre']) ?>"
                    class="btn-supprimer"
                    onclick="return confirm('Supprimer cette offre ?')">🗑 Supprimer l'offre</a>
                <?php endif; ?>

                <div class="encart-entreprise">
                    <h3>À propos de l'entreprise</h3>
                    <p><?= htmlspecialchars($offre['nom_entreprise']) ?></p>
                    <a href="/index.php?page=entreprises&action=show&id=<?= htmlspecialchars($offre['id_entreprise']) ?>"
                    class="btn-voir">Voir la fiche entreprise →</a>
                </div>

            </aside>

        </div>

    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script>
    const btnCoeur = document.querySelector('.btn-coeur-fiche');
    if (btnCoeur) {
        btnCoeur.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch('/index.php?page=favoris&action=toggle&id=' + id, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.favori) {
                    this.textContent = '❤️ Retirer des favoris';
                    this.classList.add('active');
                } else {
                    this.textContent = '🤍 Ajouter aux favoris';
                    this.classList.remove('active');
                }
            });
        });
    }
    </script>
</body>
</html>
