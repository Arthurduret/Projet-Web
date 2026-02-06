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