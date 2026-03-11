<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/public/images/jobeo/HeadLogoJobeo.png">
    <title>Jobeo | Inscription</title>

    <link rel="stylesheet" href="/public/css/style_global.css">
    <link rel="stylesheet" href="/public/css/header_footer.css">
    <link rel="stylesheet" href="/public/css/inscription.css">

</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <div class="login-container">

            <a href="#" class="back-link">← Retour</a>
            <h1>S'inscrire</h1>

            <div class="switch_buttons">
                <a href="inscription.php"class="switch_btn" disabled>Particulier</a>
                <a class="switch_btn active">Entreprise</a>
            </div>

            <form class="form-entreprise" action="inscription_traitement.php" method="POST" enctype="multipart/form-data">
    
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Exemple : prenom.nom@gmail.com" required>
                </div>

                <div class="input-group">
                    <label>Nom</label>
                    <input type="text" name="nom_entreprise" placeholder="Nom d'entreprises" required>
                </div>

                <div class="input-group">
                    <label>Taille d'entreprises</label>
                    <select name="taille_entreprise" required>
                        <option value="">Combien d'employés avez-vous ?</option>
                        <option value="micro">Micro-entreprise (moins de 10 pers)</option>
                        <option value="petite">Petite entreprise (10-50 pers)</option>
                        <option value="moyenne">Moyenne entreprise (50-250 pers)</option>
                        <option value="grande">Grande entreprise (plus de 250 pers)</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Domaine d'activité</label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="domaine[]" value="Informatique"> Informatique</label>
                        <label><input type="checkbox" name="domaine[]" value="Marketing"> Marketing</label>
                        <label><input type="checkbox" name="domaine[]" value="Finance"> Finance</label>
                        <label><input type="checkbox" name="domaine[]" value="Design"> Design</label>
                        <label><input type="checkbox" name="domaine[]" value="Communication"> Communication</label>
                    </div>
                </div>

                <div class="input-group">
                    <label>Logo entreprise</label>
                    <input type="file" name="logo" accept="image/*">
                </div>

                <div class="input-group input-group--commentaire">
                    <label for="commentaire">Commentaire supplémentaire</label>
                    <textarea
                        id="commentaire"
                        name="description"
                        placeholder="Faîtes une description de votre entreprise"
                        rows="5"
                        required
                    ></textarea>
                </div>

                <div class="input-group">
                    <label>Mot de passe</label>
                    <input type="password" name="mdp" placeholder="Votre mot de passe" required>
                </div>

                <div class="input-group">
                    <label>Confirmer Mot de passe</label>
                    <input type="password" name="mdp_confirm" placeholder="Confirmer Votre mot de passe" required>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" name="cgu" required>
                    <a href="pages_footeur/cgu.html" >J'accepte les conditions générales d'utilisation</a>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="confidentialite" required>
                    <a href="pages_footeur/mentions_legales.html" >J’accepte la politique de confidentialité</a>
                </div>
                <button type="submit">S'inscrire</button>
            </form>
        </div>
    </main>


    <?php include __DIR__ . '/partials/footer.php'; ?>

</body>
</html>