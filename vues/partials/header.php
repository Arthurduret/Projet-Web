<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title><?= htmlspecialchars($meta_title ?? 'Jobeo | Plateforme de stages') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Jobeo — Trouvez votre stage idéal parmi nos offres en région PACA.') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_keywords ?? 'stage, alternance, emploi, CESI, PACA, Marseille') ?>">
    <meta name="author" content="Web4All">
    <meta name="robots" content="index, follow">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($meta_title ?? 'Jobeo | Plateforme de stages') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($meta_description ?? 'Jobeo — Trouvez votre stage idéal parmi nos offres en région PACA.') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://jobeo.local<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
    <meta property="og:site_name" content="Jobeo">
</head>

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
        <?php elseif (isset($_SESSION['user'])): ?>
            <a href="/index.php?page=favoris">Favoris</a>
        <?php endif; ?>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin' ) : ?>
            <div class="nav-dropdown">
                <span class="nav-dropdown__trigger">Créer compte ▾</span>
                <div class="nav-dropdown__menu">
                <a href="/index.php?page=auth&action=inscription&role=etudiant">Etudiant</a>
                <a href="/index.php?page=auth&action=inscription&role=pilote">Pilote</a>
                </div>
            </div>
        <?php elseif (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'pilote' ) : ?>
            <a href="/index.php?page=auth&action=inscription&role=etudiant">Créer compte étudiant</a>
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
                <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['pilote', 'admin'])): ?>
                    <a href="/index.php?page=etudiants">
                        <?= $_SESSION['user']['role'] === 'admin' ? 'Les Étudiants' : 'Mes Étudiants' ?>
                    </a>
                <?php endif; ?>
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                    <a href="/index.php?page=pilotes">Les Pilotes</a>
                <?php endif; ?>
                <?php if ($_SESSION['user']['role'] === 'etudiant'): ?>
                        <a href="/index.php?page=candidature&action=index">Candidatures</a>
                <?php endif; ?>

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


        // Ferme tous les dropdowns si on clique ailleurs
        document.addEventListener('click', function(e) {
            document.querySelectorAll('.nav-dropdown').forEach(function(dropdown) {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('open');
                }
            });
        });

        // Ouvre et ferme chaque dropdown au clic
        document.querySelectorAll('.nav-dropdown__trigger').forEach(function(trigger) {
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                this.closest('.nav-dropdown').classList.toggle('open');
            });
        });
    </script>

</header>