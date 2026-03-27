<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | <?php echo htmlspecialchars($offre['titre']); ?></title>

    <link rel="stylesheet" href="/public/css/style_global.css">
    <link rel="stylesheet" href="/public/css/offre.css">
    <link rel="stylesheet" href="/public/css/header_footer.css">
</head>

<body>

<?php include __DIR__ . '/partials/header.php'; ?>

<main class="fiche-offre-main">

    <a href="/public/index.php?page=offres_emplois" class="btn-retour">← Retour aux offres</a>

    <!-- BLOC INFOS PRINCIPALES — PLEINE LARGEUR -->
    <section class="bloc-infos-full">

        <div class="fiche-header">
            <div class="fiche-logo">
                <img src="/public/images/entreprises/logo/<?php echo htmlspecialchars($offre['image_logo']); ?>"
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
        </div>

    </section>

    <!-- BLOC BAS : 2 COLONNES -->
    <div class="fiche-offre-container">

        <!-- COLONNE GAUCHE -->
        <div class="fiche-offre-gauche">

            <?php if (!empty($offre['competences'])): ?>
            <section class="bloc bloc-competences">
                <h2>Compétences requises</h2>

                <div class="competences">
                    <?php foreach (explode(', ', $offre['competences']) as $competence): ?>
                        <span class="tag-skill"><?php echo htmlspecialchars($competence); ?></span>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <section class="bloc bloc-description">
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

        <!-- COLONNE DROITE -->
        <aside class="fiche-offre-droite">

            <a href="/public/index.php?page=candidature&action=create&id=<?php echo htmlspecialchars($offre['id_offre']); ?>"
               class="btn-postuler">Postuler à cette offre</a>

            <div class="encart-entreprise">
                <h3>L’entreprise</h3>
                <p><?php echo htmlspecialchars($offre['nom_entreprise']); ?></p>
                <div class="mini-logo">
                    <img src="/public/images/entreprises/logo/<?php echo htmlspecialchars($offre['image_logo']); ?>"
                        alt="Logo <?php echo htmlspecialchars($offre['nom_entreprise']); ?>">
                </div>
                <a href="/public/index.php?page=entreprises&action=show&id=<?php echo htmlspecialchars($offre['id_entreprise']); ?>"
                   class="btn-voir">Voir la fiche entreprise →</a>
            </div>

        </aside>

    </div>

</main>

<?php include __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
