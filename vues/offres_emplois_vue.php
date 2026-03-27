<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | Offres d'emploi</title>
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/offres_emplois.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>

        <!-- BARRE DE RECHERCHE -->
        <section class="recherche-offres">
            <form action="/index.php" method="GET">

                <!-- On garde la page active -->
                <input type="hidden" name="page" value="offres_emplois">

                <div class="search-bar">
                    <div class="input-group">
                        <label for="quoi">Quoi ?</label>
                        <input type="text" 
                               name="quoi" 
                               id="quoi" 
                               placeholder="Titre, compétence..."
                               value="<?php echo htmlspecialchars($quoi); ?>">
                        <!-- value= permet de garder ce que l'utilisateur a tapé -->
                    </div>

                    <div class="separateur"></div>

                    <div class="input-group">
                        <label for="ou">Où ?</label>
                        <input type="text" 
                               name="ou" 
                               id="ou" 
                               placeholder="Ville, département..."
                               value="<?php echo htmlspecialchars($ou); ?>">
                    </div>

                    <button type="submit" class="btn-search">
                        <img src="/images/jobeo/LoupeLogo.png" alt="Rechercher">
                    </button>
                </div>
            </form>
        </section>

        <!-- NOMBRE D'OFFRES TROUVÉES -->
        <section class="liste-offres">
            <p class="nb-resultats">
                <strong><?php echo $nb_offres; ?></strong> offres trouvées
            </p>

            <!-- SI AUCUNE OFFRE -->
            <?php if ($nb_offres === 0): ?>
                <p class="aucun-resultat">Aucune offre ne correspond à votre recherche.</p>

            <!-- SI DES OFFRES EXISTENT -->
            <?php else: ?>
                <?php foreach ($offres as $offre): ?>
                    <article class="carte-offre">

                        <!-- Logo entreprise à gauche -->
                        <div class="offre-image">
                            <img src="/images/entreprises/logo/<?php echo htmlspecialchars($offre['image_logo']); ?>" 
                                 alt="Logo <?php echo htmlspecialchars($offre['nom_entreprise']); ?>">
                        </div>

                        <!-- Infos offre à droite -->
                        <div class="offre-contenu">
                            <h2 class="offre-titre">
                                <?php echo htmlspecialchars($offre['titre']); ?>
                            </h2>

                            <p class="offre-entreprise">
                                <?php echo htmlspecialchars($offre['nom_entreprise']); ?>
                            </p>

                            <div class="offre-tags">
                                <span><?php echo htmlspecialchars($offre['localisation']); ?></span>
                                <span><?php echo htmlspecialchars($offre['duree']); ?> mois</span>
                                <span><?php echo htmlspecialchars($offre['salaire']); ?> €</span>
                            </div>
                                                       
                            <?php if (!empty($offre['competences'])): ?>
                                <div class="offre-competences">
                                    <?php 
                                    // On découpe la chaine "PHP, MySQL, CSS" en tableau
                                    $competences = explode(', ', $offre['competences']);
                                    foreach ($competences as $competence): ?>
                                        <span class="tag-competence">
                                            <?php echo htmlspecialchars($competence); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>                            

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

                        <!-- Bouton voir l'offre -->
                        <div class="offre-action">
                            <a href="/index.php?page=offres_emplois&action=show&id=<?php echo htmlspecialchars($offre['id_offre']); ?>"
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