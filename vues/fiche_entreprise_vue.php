<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | <?php echo htmlspecialchars($entreprise['nom']); ?></title>
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/offre.css">
    <link rel="stylesheet" href="/css/header_footer.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="fiche-offre-main">

        <!-- Bouton retour -->
        <a href="/index.php?page=entreprises" class="btn-retour">← Retour aux entreprises</a>

        <!-- BANNIÈRE -->
        <div class="entreprise-banniere">
            <img src="/images/entreprises/fond/<?= htmlspecialchars($entreprise['image_fond']) ?>"
                alt="Fond <?= htmlspecialchars($entreprise['nom']) ?>">
        </div>

        <!-- BLOC INFOS avec logo qui chevauche -->
        <section class="bloc-infos-full entreprise-bloc-infos">
            
            <!-- GAUCHE : logo + nom -->
            <div class="fiche-header" style="margin-bottom:0;">
                <div class="fiche-logo entreprise-logo-chevauchant">
                    <img src="/images/entreprises/logo/<?= htmlspecialchars($entreprise['image_logo']) ?>"
                        alt="Logo <?= htmlspecialchars($entreprise['nom']) ?>">
                </div>
                <div>
                    <h1><?= htmlspecialchars($entreprise['nom']) ?></h1>
                    <p class="entreprise"><?= htmlspecialchars($entreprise['nb_offres']) ?> offres disponibles</p>
                </div>
            </div>

            <!-- DROITE : email + tel -->
            <div class="tags">
                <?php if (!empty($entreprise['email'])): ?>
                    <span>✉️ <?= htmlspecialchars($entreprise['email']) ?></span>
                <?php endif; ?>
                <?php if (!empty($entreprise['tel'])): ?>
                    <span>📞 <?= htmlspecialchars($entreprise['tel']) ?></span>
                <?php endif; ?>
            </div>

        </section>
        <!-- LAYOUT BAS -->
        <div class="fiche-offre-container">

            <!-- COLONNE GAUCHE -->
            <div class="fiche-offre-gauche">
                <section class="bloc">
                    <h2>À propos</h2>
                    <p class="description">
                        <?= nl2br(htmlspecialchars($entreprise['description'])) ?>
                    </p>
                </section>
            </div>

            <!-- COLONNE DROITE -->
            <aside class="fiche-offre-droite">

                <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'pilote'])): ?>
                    <a href="/index.php?page=entreprises&action=edit&id=<?= htmlspecialchars($entreprise['id_entreprise']) ?>"
                       class="btn-modifier">✏️ Modifier l'entreprise</a>
                <?php endif; ?>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="/index.php?page=entreprises&action=delete&id=<?= htmlspecialchars($entreprise['id_entreprise']) ?>"
                       class="btn-supprimer"
                       onclick="return confirm('Supprimer cette entreprise ?')">🗑 Supprimer l'entreprise</a>
                <?php endif; ?>

                <div class="encart-entreprise">
                    <h3>Offres de l'entreprise</h3>
                    <a href="/index.php?page=offres_emplois&quoi=<?= htmlspecialchars($entreprise['nom']) ?>"
                       class="btn-voir">Voir toutes les offres →</a>
                </div>

            </aside>
        </div>

    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>