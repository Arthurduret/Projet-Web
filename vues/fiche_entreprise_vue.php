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

        <a href="/index.php?page=entreprises" class="btn-retour">← Retour aux entreprises</a>

        <div class="entreprise-banniere">
            <img src="/images/entreprises/fond/<?= htmlspecialchars($entreprise['image_fond']) ?>"
                alt="Fond <?= htmlspecialchars($entreprise['nom']) ?>">
        </div>

        <section class="bloc-infos-full entreprise-bloc-infos">
            
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

            <div class="tags">
                <?php if (!empty($entreprise['email'])): ?>
                    <span>✉️ <?= htmlspecialchars($entreprise['email']) ?></span>
                <?php endif; ?>
                <?php if (!empty($entreprise['tel'])): ?>
                    <span>📞 <?= htmlspecialchars($entreprise['tel']) ?></span>
                <?php endif; ?>
                <div class="entreprise-note">
                    <?php if ($moyenne_eval): ?>
                        <span class="moyenne-badge">
                            ⭐ <strong><?= $moyenne_eval ?> / 5</strong> — <?= $nb_avis ?> avis
                        </span>
                    <?php else: ?>
                        <span class="moyenne-badge gris">Pas encore évaluée</span>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'pilote'])): ?>
                        <button class="btn-evaluer" onclick="ouvrirPopup()">
                            ⭐ <?= $ma_note ? 'Modifier (' . $ma_note . '/5)' : 'Évaluer' ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

        </section>
        <div class="fiche-offre-container">

            <div class="fiche-offre-gauche">
                <section class="bloc">
                    <h2>À propos</h2>
                    <p class="description">
                        <?= nl2br(htmlspecialchars($entreprise['description'])) ?>
                    </p>
                </section>
            </div>

            <aside class="fiche-offre-droite">

                <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'pilote'])): ?>
                    <a href="/index.php?page=entreprises&action=edit&id=<?= htmlspecialchars($entreprise['id_entreprise']) ?>"
                    class="btn-modifier">✏️ Modifier l'entreprise</a>
                <?php endif; ?>

                <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'pilote'])): ?>
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
    <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'pilote'])): ?>
    <div id="popup-evaluation" class="popup-overlay" style="display:none;">
        <div class="popup-contenu">
            <button class="popup-fermer" onclick="fermerPopup()">✕</button>
            <h3>Évaluer <?= htmlspecialchars($entreprise['nom']) ?></h3>
            <p>Votre note actuelle : 
                <strong><?= $ma_note ? $ma_note . ' ⭐' : 'Aucune' ?></strong>
            </p>

            <div class="etoiles" id="etoiles">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="etoile <?= $ma_note >= $i ? 'active' : '' ?>"
                        data-note="<?= $i ?>">★</span>
                <?php endfor; ?>
            </div>

            <p id="note-selectionnee">
                <?= $ma_note ? 'Note sélectionnée : ' . $ma_note . ' / 5' : 'Cliquez sur une étoile' ?>
            </p>

            <button class="btn-soumettre-eval" onclick="soumettrEvaluation(<?= $entreprise['id_entreprise'] ?>)">
                Enregistrer
            </button>
        </div>
    </div>
    <?php endif; ?>

    <?php include __DIR__ . '/partials/footer.php'; ?>
<script>
let noteSelectionnee = <?= $ma_note ?? 0 ?>;

function ouvrirPopup() {
    document.getElementById('popup-evaluation').style.display = 'flex';
}

function fermerPopup() {
    document.getElementById('popup-evaluation').style.display = 'none';
}

document.querySelectorAll('.etoile').forEach(etoile => {
    etoile.addEventListener('mouseover', function() {
        const note = this.dataset.note;
        document.querySelectorAll('.etoile').forEach((e, i) => {
            e.classList.toggle('hover', i < note);
        });
    });

    etoile.addEventListener('mouseout', function() {
        document.querySelectorAll('.etoile').forEach(e => {
            e.classList.remove('hover');
        });
    });

    etoile.addEventListener('click', function() {
        noteSelectionnee = this.dataset.note;
        document.querySelectorAll('.etoile').forEach((e, i) => {
            e.classList.toggle('active', i < noteSelectionnee);
        });
        document.getElementById('note-selectionnee').textContent = 
            'Note sélectionnée : ' + noteSelectionnee + ' / 5';
    });
});

function soumettrEvaluation(id_entreprise) {
    if (noteSelectionnee == 0) {
        alert('Veuillez sélectionner une note !');
        return;
    }

    fetch('/index.php?page=evaluation&action=noter', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'id_entreprise=' + id_entreprise + '&note=' + noteSelectionnee
    })
    .then(res => res.json())
    .then(data => {
        if (data.succes) {
            window.location.reload();
        } else {
            alert(data.message);
        }
    });
}

document.getElementById('popup-evaluation').addEventListener('click', function(e) {
    if (e.target === this) fermerPopup();
});
</script>

</body>
</html>