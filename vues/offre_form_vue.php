<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | <?php echo isset($offre) ? 'Modifier l\'offre' : 'Créer une offre'; ?></title>
    <link rel="stylesheet" href="/css/style_global.css">
    <link rel="stylesheet" href="/css/form.css">
    <link rel="stylesheet" href="/css/header_footer.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="form-main">

        <div class="form-container">

            <h1><?php echo isset($offre) ? 'Modifier l\'offre' : 'Créer une offre'; ?></h1>

            <form method="POST" action="/index.php?page=offres_emplois&action=<?php echo isset($offre) ? 'update' : 'store'; ?>">

                <?php if (isset($offre)): ?>
                    <input type="hidden" name="id_offre" value="<?php echo htmlspecialchars($offre['id_offre']); ?>">
                <?php endif; ?>

                <div class="input-group">
                    <label for="titre">Titre du poste</label>
                    <input type="text"
                           name="titre"
                           id="titre"
                           required
                           placeholder="Ex : Développeur PHP"
                           value="<?php echo isset($offre) ? htmlspecialchars($offre['titre']) : ''; ?>">
                </div>

                <div class="input-group">
                    <label for="id_entreprise">Entreprise</label>
                    <select name="id_entreprise" id="id_entreprise" required>
                        <option value="">-- Choisir une entreprise --</option>
                        <?php foreach ($entreprises as $e): ?>
                            <option value="<?php echo htmlspecialchars($e['id_entreprise']); ?>"
                                <?php
                                if (isset($offre) && $offre['id_entreprise'] == $e['id_entreprise']) {
                                    echo 'selected';
                                }
                                ?>>
                                <?php echo htmlspecialchars($e['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="input-group">
                    <label for="description">Description</label>
                    <textarea name="description"
                              id="description"
                              rows="6"
                              required
                              placeholder="Décrivez le poste, les missions..."><?php echo isset($offre) ? htmlspecialchars($offre['description']) : ''; ?></textarea>
                </div>

                <div class="form-ligne">
                    <div class="input-group">
                        <label for="salaire">Rémunération (€/mois)</label>
                        <input type="number"
                               name="salaire"
                               id="salaire"
                               min="0"
                               step="50"
                               placeholder="Ex : 600"
                               value="<?php echo isset($offre) ? htmlspecialchars($offre['salaire']) : ''; ?>">
                    </div>

                    <div class="input-group">
                        <label for="duree">Durée (mois)</label>
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

                <div class="form-ligne">
                    <div class="input-group">
                        <label for="localisation">Localisation</label>
                        <input type="text"
                               name="localisation"
                               id="localisation"
                               placeholder="Ex : Paris 75001"
                               value="<?php echo isset($offre) ? htmlspecialchars($offre['localisation']) : ''; ?>">
                    </div>

                    <div class="input-group">
                        <label for="date_offre">Date de l'offre</label>
                        <input type="date"
                               name="date_offre"
                               id="date_offre"
                               required
                               value="<?php echo isset($offre) ? htmlspecialchars($offre['date_offre']) : date('Y-m-d'); ?>">
                    </div>
                </div>

                <div class="input-group">
                    <label>Compétences requises</label>
                    <div class="competences-grid">
                        <?php foreach (($competences ?? []) as $comp): ?>
                            <label class="competence-checkbox">
                                <input type="checkbox"
                                       name="competences[]"
                                       value="<?php echo htmlspecialchars($comp['id_competence']); ?>"
                                       <?php
                                       if (isset($competences_offre) && in_array($comp['id_competence'], $competences_offre)) {
                                           echo 'checked';
                                       }
                                       ?>>
                                <?php echo htmlspecialchars($comp['nom']); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-boutons">
                    <a href="/index.php?page=offres_emplois" class="btn-annuler">
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