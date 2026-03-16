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

        <!-- BOUTON RETOUR -->
        <div class="retour">
            <a href="/public/index.php?page=offres_emplois" class="btn-retour">
                ← Retour aux offres
            </a>
        </div>

        <div class="fiche-offre-container">

            <!-- COLONNE GAUCHE — infos principales -->
            <div class="fiche-offre-gauche">

                <!-- En-tête avec logo + titre -->
                <div class="fiche-offre-header">
                    <div class="fiche-logo">
                        <img src="/public/images/entreprises/logo/<?php echo htmlspecialchars($offre['image_logo']); ?>"
                             alt="Logo <?php echo htmlspecialchars($offre['nom_entreprise']); ?>">
                    </div>
                    <div class="fiche-header-texte">
                        <h1><?php echo htmlspecialchars($offre['titre']); ?></h1>
                        <p class="fiche-entreprise">
                            <?php echo htmlspecialchars($offre['nom_entreprise']); ?>
                        </p>
                    </div>
                </div>

                <!-- Tags -->
                <div class="offre-tags">
                    <?php if (!empty($offre['localisation'])): ?>
                        <span>📍 <?php echo htmlspecialchars($offre['localisation']); ?></span>
                    <?php endif; ?>
                    <span>⏱ <?php echo htmlspecialchars($offre['duree']); ?> mois</span>
                    <span>💶 <?php echo htmlspecialchars($offre['salaire']); ?> €/mois</span>
                </div>

                <!-- Compétences -->
                <?php if (!empty($offre['competences'])): ?>
                    <div class="fiche-section">
                        <h2>Compétences requises</h2>
                        <div class="offre-competences">
                            <?php foreach (explode(', ', $offre['competences']) as $competence): ?>
                                <span class="tag-competence">
                                    <?php echo htmlspecialchars($competence); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Description -->
                <div class="fiche-section">
                    <h2>Description du poste</h2>
                    <p class="fiche-description">
                        <?php echo nl2br(htmlspecialchars($offre['description'])); ?>
                        <!-- nl2br() convertit les sauts de ligne en <br> -->
                    </p>
                </div>

                <!-- Date -->
                <p class="offre-date">
                    Publiée le <?php
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

            <!-- COLONNE DROITE — actions -->
            <div class="fiche-offre-droite">

                <!-- Bouton postuler — visible uniquement pour les étudiants -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'etudiant'): ?>
                    <a href="/public/index.php?page=candidature&action=create&id=<?php echo htmlspecialchars($offre['id_offre']); ?>"
                       class="btn-postuler">
                        Postuler à cette offre
                    </a>

                    <!-- Bouton wishlist -->
                    <a href="/public/index.php?page=wishlist&action=toggle&id=<?php echo htmlspecialchars($offre['id_offre']); ?>"
                       class="btn-wishlist">
                        ♡ Ajouter à ma wishlist
                    </a>
                <?php endif; ?>

                <!-- Boutons admin/pilote -->
                <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'pilote'])): ?>
                    <a href="/public/index.php?page=offres_emplois&action=edit&id=<?php echo htmlspecialchars($offre['id_offre']); ?>"
                       class="btn-modifier">
                        ✏️ Modifier l'offre
                    </a>
                <?php endif; ?>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="/public/index.php?page=offres_emplois&action=delete&id=<?php echo htmlspecialchars($offre['id_offre']); ?>"
                       class="btn-supprimer"
                       onclick="return confirm('Supprimer cette offre ?')">
                        🗑 Supprimer l'offre
                    </a>
                <?php endif; ?>

                <!-- Encart entreprise -->
                <div class="fiche-encart-entreprise">
                    <h3>À propos de l'entreprise</h3>
                    <p><?php echo htmlspecialchars($offre['nom_entreprise']); ?></p>
                    <a href="/public/index.php?page=entreprises&action=show&id=<?php echo htmlspecialchars($offre['id_entreprise']); ?>"
                       class="btn-voir">
                        Voir la fiche entreprise →
                    </a>
                </div>

            </div>

        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>