<header>

    <a href="/index.php" class="logo">
        <img src="/images/jobeo/LogoJobeo.png" alt="Logo" width="90" height="50">
    </a>
    
    <nav id="nav-menu">
        <a href="/index.php?page=entreprises">Nos Entreprises</a>
        <a href="/index.php?page=a_propos">A propos de nous</a>
        <a href="/index.php?page=offres_emplois">Offres d'emplois</a>

        <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['pilote', 'admin'])): ?>
            <div class="nav-dropdown">
                <span class="nav-dropdown__trigger">Créer ▾</span>
                <div class="nav-dropdown__menu">
                    <a href="/index.php?page=entreprises&action=create">Entreprise</a>
                    <a href="/index.php?page=offres_emplois&action=create">Offre</a>
                </div>
            </div>
            <a href="/index.php?page=auth&action=inscription">Créer compte étudiant</a>
        <?php elseif (isset($_SESSION['user'])): ?>
            <a href="/index.php?page=favoris">Favoris</a>
        <?php endif; ?>
    </nav>

    <div class="header-right">
        <?php if (isset($_SESSION['user'])): ?>
            <div class="nav-dropdown" id="dropdown-compte">
                <span class="nav-dropdown__trigger btn-compte">
                    <img src="/images/jobeo/logo_profil.png" alt="logo_profil" width="40" height="40">
                    <span><?php echo htmlspecialchars($_SESSION['user']['prenom']); ?></span>
                </span>
                <div class="nav-dropdown__menu">
                    <a href="/index.php?page=mon_compte">Mon compte</a>
                    <a href="/index.php?page=auth&action=deconnexion">Déconnexion</a>
                </div>
            </div>
        <?php else: ?>
            <a href="/index.php?page=auth&action=connexion" class="btn-compte">
                <img src="/images/jobeo/logo_profil.png" alt="logo_profil" width="40" height="40">
                <span>Mon compte</span>
            </a>
        <?php endif; ?>

        <div class="menu-tel" onclick="toggleMenu()">☰</div>
    </div>

    <script>
        function toggleMenu() {
            const nav = document.getElementById('nav-menu');
            nav.classList.toggle('active');
        }

        // Ferme le dropdown si on clique ailleurs sur la page
        document.addEventListener('click', function(e) {
            const dropdown = document.querySelector('.nav-dropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });

        // Ouvre/ferme le dropdown au clic
        const trigger = document.querySelector('.nav-dropdown__trigger');
        if (trigger) {
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                document.querySelector('.nav-dropdown').classList.toggle('open');
            });
        }
    </script>

</header>