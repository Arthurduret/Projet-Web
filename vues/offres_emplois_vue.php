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

        <!-- ===== BARRE DE RECHERCHE ===== -->
        <section class="recherche-offres">
            <form action="/index.php" method="GET">
                <input type="hidden" name="page" value="offres_emplois">
                <div class="search-bar">
                    <div class="input-group">
                        <label for="quoi">Quoi ?</label>
                        <input type="text"
                               name="quoi"
                               id="quoi"
                               placeholder="Titre, compétence..."
                               value="<?= htmlspecialchars($quoi) ?>">
                    </div>
                    <div class="separateur"></div>
                    <div class="input-group">
                        <label for="ou">Où ?</label>
                        <input type="text"
                               name="ou"
                               id="ou"
                               placeholder="Ville, département..."
                               value="<?= htmlspecialchars($ou) ?>">
                    </div>
                    <button type="submit" class="btn-search">
                        <img src="/images/jobeo/LoupeLogo.png" alt="Rechercher">
                    </button>
                </div>
            </form>

            <!-- BOUTON FILTRES -->
            <div class="filtres-trigger">
                <button class="btn-toggle-filtres" onclick="toggleFiltres()">
                    ▼ Filtres avancés
                </button>
            </div>
        </section>

        <!-- ===== FILTRES AVANCÉS ===== -->
        <div class="filtres-container">
            <div id="filtres-avances" style="display:none;">
                <form action="/index.php" method="GET">
                    <input type="hidden" name="page" value="offres_emplois">
                    <input type="hidden" name="quoi" value="<?= htmlspecialchars($quoi) ?>">
                    <input type="hidden" name="ou" value="<?= htmlspecialchars($ou) ?>">

                    <div class="filtres-grille">

                        <div class="filtre-group">
                            <label>Compétence</label>
                            <input type="text" name="f_competence"
                                   placeholder="Ex : PHP"
                                   value="<?= htmlspecialchars($filtres['f_competence'] ?? '') ?>">
                        </div>

                        <div class="filtre-group">
                            <label>Rémunération min (€)</label>
                            <input type="number" name="f_salaire_min"
                                   placeholder="Ex : 500"
                                   value="<?= htmlspecialchars($filtres['f_salaire_min'] ?? '') ?>">
                        </div>

                        <div class="filtre-group">
                            <label>Publiée depuis le</label>
                            <input type="date" name="f_date"
                                   value="<?= htmlspecialchars($filtres['f_date'] ?? '') ?>">
                        </div>

                        <div class="filtre-group">
                            <label>Candidatures minimum</label>
                            <input type="number" name="f_candidatures_min"
                                   placeholder="Ex : 5"
                                   value="<?= htmlspecialchars($filtres['f_candidatures_min'] ?? '') ?>">
                        </div>

                        <div class="filtre-group">
                            <label>Trier par</label>
                            <select name="f_tri">
                                <option value="">-- Choisir --</option>
                                <option value="date_desc" <?= ($filtres['f_tri'] ?? '') === 'date_desc' ? 'selected' : '' ?>>Date (récent)</option>
                                <option value="date_asc" <?= ($filtres['f_tri'] ?? '') === 'date_asc' ? 'selected' : '' ?>>Date (ancien)</option>
                                <option value="salaire_desc" <?= ($filtres['f_tri'] ?? '') === 'salaire_desc' ? 'selected' : '' ?>>Salaire (élevé)</option>
                                <option value="salaire_asc" <?= ($filtres['f_tri'] ?? '') === 'salaire_asc' ? 'selected' : '' ?>>Salaire (faible)</option>
                                <option value="candidatures_desc" <?= ($filtres['f_tri'] ?? '') === 'candidatures_desc' ? 'selected' : '' ?>>Plus de candidatures</option>
                            </select>
                        </div>

                    </div>

                    <div class="filtres-boutons">
                        <a href="/index.php?page=offres_emplois" class="btn-reset-filtres">
                            ✕ Réinitialiser
                        </a>
                        <button type="submit" class="btn-appliquer-filtres">
                            Appliquer les filtres
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- ===== LISTE DES OFFRES ===== -->
        <section class="liste-offres">

            <?php
                $debut = $offset + 1;
                $fin   = min($offset + $limite, $total);
            ?>

            <p class="nb-resultats">
                <?php if ($total === 0): ?>
                    <strong>0</strong> offre trouvée
                <?php elseif ($total <= $limite): ?>
                    <strong><?= $total ?></strong> offre<?= $total > 1 ? 's' : '' ?> trouvée<?= $total > 1 ? 's' : '' ?>
                <?php else: ?>
                    <strong><?= $debut ?></strong> à <strong><?= $fin ?></strong> sur <strong><?= $total ?></strong> offre<?= $total > 1 ? 's' : '' ?>
                <?php endif; ?>
            </p>

            <?php if ($nb_offres === 0): ?>
                <p class="aucun-resultat">Aucune offre ne correspond à votre recherche.</p>

            <?php else: ?>
                <?php foreach ($offres as $offre): ?>
                    <article class="carte-offre">

                        <!-- COEUR FAVORI -->
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'etudiant'): ?>
                            <button class="btn-coeur <?= isset($favoris_ids) && in_array($offre['id_offre'], $favoris_ids) ? 'active' : '' ?>"
                                    data-id="<?= htmlspecialchars($offre['id_offre']) ?>">
                                <?= isset($favoris_ids) && in_array($offre['id_offre'], $favoris_ids) ? '❤️' : '🤍' ?>
                            </button>
                        <?php endif; ?>

                        <!-- LOGO -->
                        <div class="offre-image">
                            <img src="/images/entreprises/logo/<?= htmlspecialchars($offre['image_logo']) ?>"
                                 alt="Logo <?= htmlspecialchars($offre['nom_entreprise']) ?>">
                        </div>

                        <!-- CONTENU -->
                        <div class="offre-contenu">
                            <h2 class="offre-titre"><?= htmlspecialchars($offre['titre']) ?></h2>
                            <p class="offre-entreprise"><?= htmlspecialchars($offre['nom_entreprise']) ?></p>

                            <div class="offre-tags">
                                <span>📍 <?= htmlspecialchars($offre['localisation']) ?></span>
                                <span>⏱ <?= htmlspecialchars($offre['duree']) ?> mois</span>
                                <span>💶 <?= htmlspecialchars($offre['salaire']) ?> €</span>
                                <span>👥 <?= $offre['nb_candidatures'] ?? 0 ?> candidature<?= ($offre['nb_candidatures'] ?? 0) > 1 ? 's' : '' ?></span>
                            </div>

                            <?php if (!empty($offre['competences'])): ?>
                                <div class="offre-competences">
                                    <?php foreach (explode(', ', $offre['competences']) as $competence): ?>
                                        <span class="tag-competence"><?= htmlspecialchars($competence) ?></span>
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
                            </p>
                        </div>

                        <!-- ACTIONS -->
                        <div class="offre-action">
                            <a href="/index.php?page=offres_emplois&action=show&id=<?= htmlspecialchars($offre['id_offre']) ?>"
                               class="btn-voir">Voir l'offre</a>

                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'etudiant'): ?>
                                <a href="/index.php?page=candidature&action=create&id=<?= htmlspecialchars($offre['id_offre']) ?>"
                                   class="btn-postuler">Postuler</a>
                            <?php endif; ?>
                        </div>

                    </article>
                <?php endforeach; ?>
            <?php endif; ?>

        </section>

        <!-- ===== PAGINATION ===== -->
        <?php if ($nb_pages > 1): ?>
            <?php
            $params_url = http_build_query(array_filter([
                'page'               => 'offres_emplois',
                'quoi'               => $quoi,
                'ou'                 => $ou,
                'f_competence'       => $filtres['f_competence'] ?? '',
                'f_salaire_min'      => $filtres['f_salaire_min'] ?? '',
                'f_date'             => $filtres['f_date'] ?? '',
                'f_candidatures_min' => $filtres['f_candidatures_min'] ?? '',
                'f_tri'              => $filtres['f_tri'] ?? '',
            ]));
            ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?<?= $params_url ?>&p=<?= $page - 1 ?>" class="page-btn">← Précédent</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $nb_pages; $i++): ?>
                    <a href="?<?= $params_url ?>&p=<?= $i ?>"
                       class="page-btn <?= $i === $page ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $nb_pages): ?>
                    <a href="?<?= $params_url ?>&p=<?= $page + 1 ?>" class="page-btn">Suivant →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script>
    // ===== TOGGLE FILTRES =====
    function toggleFiltres() {
        const filtres = document.getElementById('filtres-avances');
        const btn     = document.querySelector('.btn-toggle-filtres');
        if (filtres.style.display === 'none' || filtres.style.display === '') {
            filtres.style.display = 'block';
            btn.textContent = '▲ Masquer les filtres';
            sessionStorage.setItem('filtresOuverts', 'true');
        } else {
            filtres.style.display = 'none';
            btn.textContent = '▼ Filtres avancés';
            sessionStorage.setItem('filtresOuverts', 'false');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const filtresActifs = <?= !empty(array_filter($filtres)) ? 'true' : 'false' ?>;
        const filtresOuverts = sessionStorage.getItem('filtresOuverts') === 'true';

        if (filtresActifs || filtresOuverts) {
            const filtres = document.getElementById('filtres-avances');
            const btn     = document.querySelector('.btn-toggle-filtres');
            filtres.style.display = 'block';
            btn.textContent = '▲ Masquer les filtres';
        }
    });

    // ===== COEUR FAVORI =====
    document.querySelectorAll('.btn-coeur').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch('/index.php?page=favoris&action=toggle&id=' + id, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.favori) {
                    this.textContent = '❤️';
                    this.classList.add('active');
                } else {
                    this.textContent = '🤍';
                    this.classList.remove('active');
                }
            });
        });
    });
    </script>
</body>
</html>