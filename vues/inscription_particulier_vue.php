<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/public/images/jobeo/HeadLogoJobeo.png">
    <title>Jobeo | Inscription</title>

    <link rel="stylesheet" href="/public/css/style_global.css">
    <link rel="stylesheet" href="/public/css/header_footer.css">
    <link rel="stylesheet" href="/public/css/Style_connexion.css">

</head>

<body>
    
    <?php include __DIR__ . '/partials/header.php'; ?> 

    <main>
        <div class="login-container">

            <a href="#" class="back-link">← Retour</a>

            <h1>Inscription</h1>

            <form>
                <div class="double-input">
                    <div class="input-group">
                        <label>Nom</label>
                        <input type="text" placeholder="Votre nom" required>
                    </div>

                    <div class="input-group">
                        <label>Prénom</label>
                        <input type="text" placeholder="Votre prénom" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" placeholder="prenom.nom@gmail.com" required>
                </div>

                <div class="input-group radio-container">
                    <label class="label-radio-titre">Je suis</label>
                    <div class="radio-group">
                        <input type="radio" name="role" value="Étudiant" id="Étudiant" required>
                            <label for="Étudiant">Étudiant</label>

                        <input type="radio" name="role" value="Pilote" id="Pilote">
                            <label for="Pilote">Pilote</label>
                    </div>
                </div>

                <div class="input-group">
                    <label>Mot de passe</label>
                    <input type="password" placeholder="Votre mot de passe" required>
                </div>

                <div class="input-group">
                    <label>Mot de passe</label>
                    <input type="password" placeholder="Confirmer Votre mot de passe" required>
                </div>




                <div class="checkbox-group">
                    <input type="checkbox" required>
                    <a href="pages_footeur/cgu.html" >J'accepte les conditions générales d'utilisation</a>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" required>
                    <a href="pages_footeur/mentions_legales.html" >J’accepte la politique de confidentialité</a>
                </div>
                <button type="submit">S'inscrire</button>
            </form>
        </div>
    </main>
            
    <?php include __DIR__ . '/partials/footer.php'; ?>

</body>
</html>