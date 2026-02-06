<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobeo - Inscription</title>

    <link rel="stylesheet" href="header_footer.css">
    <link rel="stylesheet" href="inscription.css">
    <link rel="icon" type="image/png" href="images/LogoJobeo.png">

</head>

<body>
<<<<<<< HEAD:inscription.html
    <header>
        <a href="index.html" class="logo">
            <img src="images/LogoJobeo.png" alt="Logo" width="90" height="50">
        </a>



        <nav id="nav-menu">
            <a href="#entreprises">Nos Entreprises</a>
            <a href="a_propos.html">A propos de nous</a>
            <a href="offres_emplois.html">Offres d'emplois</a>
            <a href="favoris.html">Favoris</a>
        </nav>

        <div class="header-right">
            <a href="identifier.html" class="btn-compte">
                <img src="images/logo_profil.png" alt="logo_profil" width="40" height="40">
                <span>Mon compte</span>
            </a>
            
            <div class="menu-tel" onclick="toggleMenu()">☰</div>
        </div>

        <script>
        function toggleMenu() {
            const nav = document.getElementById('nav-menu');
            // Ajoute ou enlève la classe "active" à chaque clic
            nav.classList.toggle('active');
        }
    </script>

    </header>

=======
    <?php include 'header.php'; ?> 
    <h1>S'inscrire</h1>
>>>>>>> arthur:inscription.php

<main>
    <div class="login-container">

        <a href="#" class="back-link">← Retour</a>

        <h1>S'inscrire</h1>
        
        <div class="switch_buttons">
            <a class="switch_btn active" disabled>Particulier</a>
            <a href="inscription_entreprise.html" class="switch_btn">Entreprise</a>
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
<<<<<<< HEAD:inscription.html

        </form>
    </div>
</main>

<footer>
    <a href="#">Mentions légales</a>
    <p>✦</p>
    <a href="#">CGU</a>
    <p>✦</p>
    <a href="#">Cookies</a>
</footer>

=======
        </div>
            
    </form>
    <?php include 'footer.php'; ?> 
>>>>>>> arthur:inscription.php
</body>
</html>