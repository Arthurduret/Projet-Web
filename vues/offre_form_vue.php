<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Le titre change selon si on crée ou modifie -->
    <title>Jobeo | <?php echo isset($offre) ? 'Modifier l\'offre' : 'Créer une offre'; ?></title>
    <link rel="stylesheet" href="/public/css/style_global.css">
    <link rel="stylesheet" href="/public/css/offre.css">
    <link rel="stylesheet" href="/public/css/header_footer.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="form-main">

        <div class="form-container">

            <h1><?php echo isset($offre) ? 'Modifier l\'offre' : 'Créer une offre'; ?></h1>

            <!-- 
                Si $offre existe → on est en modification → action=update
                Si $offre n'existe pas → on est en création → action=store
            -->
            <form method="POST" action="/public/index.php?page=offres_emplois&action=<?php echo isset($offre) ? 'update' : 'store'; ?>">

                <!-- En modification on envoie l'id en champ caché -->
                <?php if (isset($offre)): ?>
                    <input type="hidden" name="id_offre" value="<?php echo htmlspecialchars($offre['id_offre']); ?>">
                <?php endif; ?>

                <!-- TITRE -->
                <div class="form-group">
                    <label for="titre">Titre du poste *</label>
                    <input type="text"
                           name="titre"
                           id="titre"
                           required
                           placeholder="Ex : Développeur PHP"
                           value="<?php echo isset($offre) ? htmlspecialchars($offre['titre']) : ''; ?>">
                </div>

                <!-- ENTREPRISE -->
                <div class="form-group">
                    <label for="id_entreprise">Entreprise *</label>
                    <select name="id_entreprise" id="id_entreprise" required>
                        <option value="">-- Choisir une entreprise --</option>
                        <?php foreach ($entreprises as $e): ?>
                            <option value="<?php echo htmlspecialchars($e['id_entreprise']); ?>"
                                <?php
                                // En modification, on sélectionne l'entreprise actuelle
                                if (isset($offre) && $offre['id_entreprise'] == $e['id_entreprise']) {
                                    echo 'selected';
                                }
                                ?>>
                                <?php echo htmlspecialchars($e['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- DESCRIPTION -->
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea name="description"
                              id="description"
                              rows="6"
                              required
                              placeholder="Décrivez le poste, les missions..."><?php echo isset($offre) ? htmlspecialchars($offre['description']) : ''; ?></textarea>
                </div>

                <!-- LIGNE : SALAIRE + DURÉE -->
                <div class="form-ligne">
                    <div class="form-group">
                        <label for="salaire">Rémunération (€/mois)</label>
                        <input type="number"
                               name="salaire"
                               id="salaire"
                               min="0"
                               step="0.01"
                               placeholder="Ex : 600"
                               value="<?php echo isset($offre) ? htmlspecialchars($offre['salaire']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="duree">Durée (mois) *</label>
                        <input type="number"
                               name="duree"
                               id="duree"
                               required
                               min="1"
                               max="24"
                               placeholder="Ex : 6"
                               value="<?php echo isset($offre) ? htmlspecialchars($offre['duree']) : ''; ?>">
                    </div>
                </div>

                <!-- LIGNE : LOCALISATION + DATE -->
                <div class="form-ligne">
                    <div class="form-group">
                        <label for="localisation">Localisation</label>
                        <input type="text"
                               name="localisation"
                               id="localisation"
                               placeholder="Ex : Paris 75001"
                               value="<?php echo isset($offre) ? htmlspecialchars($offre['localisation']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="date_offre">Date de l'offre *</label>
                        <input type="date"
                               name="date_offre"
                               id="date_offre"
                               required
                               value="<?php echo isset($offre) ? htmlspecialchars($offre['date_offre']) : date('Y-m-d'); ?>">
                    </div>
                </div>

                <!-- COMPÉTENCES — cases à cocher -->
                <div class="form-group">
                    <label>Compétences requises</label>
                    <div class="competences-grid">
                        <?php foreach (($competences ?? []) as $comp): ?>
                            <label class="competence-checkbox">
                                <input type="checkbox"
                                       name="competences[]"
                                       value="<?php echo htmlspecialchars($comp['id_competence']); ?>"
                                       <?php
                                       // En modification, on coche les compétences déjà liées
                                       if (isset($competences_offre) && in_array($comp['id_competence'], $competences_offre)) {
                                           echo 'checked';
                                       }
                                       ?>>
                                <?php echo htmlspecialchars($comp['nom']); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- BOUTONS -->
                <div class="form-boutons">
                    <a href="/public/index.php?page=offres_emplois" class="btn-annuler">
                        Annuler
                    </a>
                    <button type="submit" class="btn-soumettre">
                        <?php echo isset($offre) ? 'Enregistrer les modifications' : 'Créer l\'offre'; ?>
                    </button>
                </div>

            </form>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>