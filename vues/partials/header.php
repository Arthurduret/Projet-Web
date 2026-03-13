<header>

<?php $_SESSION['role'] = 'pilote';?>

    <a href="/public/index.php" class="logo">
        <img src="/public/images/jobeo/LogoJobeo.png" alt="Logo" width="90" height="50">
    </a>

    <nav id="nav-menu">
        <a href="/public/index.php?page=entreprises">Nos Entreprises</a>
        <a href="/public/index.php?page=a_propos">A propos de nous</a>
        <a href="/public/index.php?page=offres_emplois">Offres d'emplois</a>

        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'pilote' || $_SESSION['role'] === 'admin')): ?>
            <!-- Menu déroulant Créer — visible uniquement pour les pilotes -->
            <div class="nav-dropdown">
                <span class="nav-dropdown__trigger">Créer ▾</span>
                <div class="nav-dropdown__menu">
                    <a href="/public/index.php?page=creer_entreprise">Entreprise</a>
                    <a href="/public/index.php?page=creer_offre">Offre</a>
                </div>
            </div>
        <?php else: ?>
            <!-- Bouton Favoris — visible pour les étudiants -->
            <a href="/favoris.php">Favoris</a>
        <?php endif; ?>
    </nav>

    <div class="header-right">
        <a href="/identifier.php" class="btn-compte">
            <img src="/public/images/jobeo/logo_profil.png" alt="logo_profil" width="40" height="40">
            <span>Mon compte</span>
        </a>
        
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
