<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title><?= htmlspecialchars($meta_title ?? 'Jobeo | ' . $offre['titre']) ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Stage ' . $offre['titre'] . ' chez ' . $offre['nom_entreprise'] . ' sur Jobeo.') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_keywords ?? 'stage ' . $offre['titre'] . ', ' . $offre['nom_entreprise']) ?>">
    <meta name="robots" content="index, follow">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/jobeo/HeadLogoJobeo.png">

    <!-- CSS -->
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/offre.css">
    <link rel="stylesheet" href="/css/header_footer.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="fiche-offre-main">

        <!-- Retour -->
        <a href="/index.php?page=offres_emplois" class="btn-retour">← Retour aux offres</a>

        <!-- ===== BLOC INFOS ===== -->
        <section class="bloc-infos-full" aria-label="Informations sur l'offre">

            <div class="fiche-header">
                <div class="fiche-logo">
                    <img src="/images/entreprises/logo/<?= htmlspecialchars($offre['image_logo']) ?>"
                         alt="Logo <?= htmlspecialchars($offre['nom_entreprise']) ?>"
                         loading="lazy">
                </div>
                <div>
                    <h1><?= htmlspecialchars($offre['titre']) ?></h1>
                    <p class="entreprise"><?= htmlspecialchars($offre['nom_entreprise']) ?></p>
                </div>
            </div>

            <div class="tags">
                <?php if (!empty($offre['localisation'])): ?>
                    <span>📍 <?= htmlspecialchars($offre['localisation']) ?></span>
                <?php endif; ?>
                <span>⏱ <?= htmlspecialchars($offre['duree']) ?> mois</span>
                <span>💶 <?= htmlspecialchars($offre['salaire']) ?> €/mois</span>
                <span>👥 <?= (int)$nb_candidatures ?> candidature<?= $nb_candidatures > 1 ? 's' : '' ?></span>
            </div>

        </section>

        <!-- ===== LAYOUT BAS ===== -->
        <div class="fiche-offre-container">

            <!-- COLONNE GAUCHE -->
            <div class="fiche-offre-gauche">

                <!-- Compétences -->
                <?php if (!empty($offre['competences'])): ?>
                <section class="bloc">
                    <h2>Compétences requises</h2>
                    <div class="competences">
                        <?php foreach (explode(', ', $offre['competences']) as $competence): ?>
                            <span class="tag-skill"><?= htmlspecialchars($competence) ?></span>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Description -->
                <section class="bloc">
                    <h2>Description du poste</h2>
                    <p class="description">
                        <?= nl2br(htmlspecialchars($offre['description'])) ?>
                    </p>
                </section>

                <!-- Date -->
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

            <!-- COLONNE DROITE -->
            <aside class="fiche-offre-droite">

                <!-- Postuler + Favoris — étudiant seulement (SFx20/SFx24) -->
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'etudiant'): ?>
                    <a href="/index.php?page=candidature&action=create&id=<?= htmlspecialchars($offre['id_offre']) ?>"
                       class="btn-postuler">Postuler à cette offre</a>

                    <button class="btn-wishlist btn-coeur-fiche <?= $estFavori ? 'active' : '' ?>"
                            data-id="<?= htmlspecialchars($offre['id_offre']) ?>"
                            aria-label="<?= $estFavori ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>">
                        <?= $estFavori ? '🧡 Retirer des favoris' : '🤍 Ajouter aux favoris' ?>
                    </button>
                <?php endif; ?>

                <!-- Modifier — admin + pilote (SFx9) -->
                <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'pilote'])): ?>
                    <a href="/index.php?page=offres_emplois&action=edit&id=<?= htmlspecialchars($offre['id_offre']) ?>"
                       class="btn-modifier">✏️ Modifier l'offre</a>
                <?php endif; ?>

                <!-- Supprimer — admin + pilote (SFx10) -->
                <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'pilote'])): ?>
                    <a href="/index.php?page=offres_emplois&action=delete&id=<?= htmlspecialchars($offre['id_offre']) ?>"
                       class="btn-supprimer"
                       onclick="return confirm('Supprimer cette offre ?')">🗑 Supprimer l'offre</a>
                <?php endif; ?>

                <!-- Encart entreprise -->
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
                    this.textContent = '🧡 Retirer des favoris';
                    this.classList.add('active');
                    this.setAttribute('aria-label', 'Retirer des favoris');
                } else {
                    this.textContent = '🤍 Ajouter aux favoris';
                    this.classList.remove('active');
                    this.setAttribute('aria-label', 'Ajouter aux favoris');
                }
            })
            .catch(err => console.error('Erreur toggle favori :', err));
        });
    }
    </script>
</body>
</html>