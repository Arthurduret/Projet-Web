<header>
    <a href="/index.php" class="logo">
        <img src="/images/jobeo/LogoJobeo.png" alt="Logo" width="90" height="50">
    </a>

    <nav id="nav-menu">
        <a href="/index.php?page=entreprises">Nos Entreprises</a>
        <a href="/index.php?page=a_propos">A propos de nous</a>
        <a href="/index.php?page=offres_emplois">Offres d'emplois</a>
        <a href="/favoris.php">Favoris</a>
    </nav>

    <div class="header-right">
        <a href="/index.php?page=identifier" class="btn-compte">
            <img src="/images/jobeo/logo_profil.png" alt="logo_profil" width="40" height="40">
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