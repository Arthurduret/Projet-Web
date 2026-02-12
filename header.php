<header>
    <a href="/index.php" class="logo">
        <img src="/images/LogoJobeo.png" alt="Logo" width="90" height="50">
    </a>

    <nav id="nav-menu">
        <a href="/nos_entreprises.php">Nos Entreprises</a>
        <a href="/a_propos.php">A propos de nous</a>
        <a href="/offres_emplois.php">Offres d'emplois</a>
        <a href="/favoris.php">Favoris</a>
    </nav>

    <div class="header-right">
        <a href="/identifier.php" class="btn-compte">
            <img src="/images/logo_profil.png" alt="logo_profil" width="40" height="40">
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