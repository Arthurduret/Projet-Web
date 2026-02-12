<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo | Inscription</title>
    <link rel="stylesheet" href="style_global.css">
    <link rel="stylesheet" href="header_footer.css">
    <link rel="stylesheet" href="inscription.css">
    <link rel="icon" type="image/png" href="images/LogoJobeo.png">

</head>

<body>
    
<?php include 'header.php'; ?> 

<main>
    <div class="login-container">

        <a href="#" class="back-link">← Retour</a>

        <h1>S'inscrire</h1>
        
        <div class="switch_buttons">
            <a class="switch_btn active" disabled>Particulier</a>
            <a href="inscription_entreprise.php" class="switch_btn">Entreprise</a>
        </div>

        <form>
            
            <div class="input-group">
                <label>Email</label>
                <input type="email" placeholder="Exemple : prenom.nom@gmail.com" required>
            </div>

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
                <label>Type de contrat</label>
                <select required>
                    <option value="">Que recherchez-vous ?</option>
                    <option>CDI</option>
                    <option>CDD</option>
                    <option>Stage</option>
                    <option>Alternance</option>
                    <option>Freelance</option>
                </select>
            </div>

            <div class="input-group">
                <label>Domaine d'activité</label>
                <select required>
                    <option value="">Où recherchez-vous ?</option>
                    <option>Informatique</option>
                    <option>Marketing</option>
                    <option>Finance</option>
                    <option>Design</option>
                </select>
            </div>

            <div class="input-group">
                <label>Expérience</label>
                <select>
                    <option>Année d'expérience</option>
                    <option>0 - 1 an</option>
                    <option>1 - 3 ans</option>
                    <option>3 - 5 ans</option>
                    <option>5+ ans</option>
                </select>
            </div>

            <div class="input-group">
                <label>CV</label>
                <input type="file">
            </div>

            <div class="input-group">
                <label>Ressources additionnelles</label>
                <input type="file">
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
            
<?php include 'footer.php'; ?> 

</body>
</html>