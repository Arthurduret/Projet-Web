<?php
require_once __DIR__ . '/../modeles/utilisateur_modele.php';
require_once __DIR__ . '/../helper/csrf.php';

class AuthControleur
{
    private PDO $pdo;
    private UtilisateurModele $modele;

    public function __construct(PDO $pdo)
    {
        $this->pdo    = $pdo;
        $this->modele = new UtilisateurModele($pdo);
    }

    // CONNEXION — affiche le formulaire
    public function connexion(): void
    {
        $email = $_GET['email'] ?? '';

        $meta_title       = "Jobeo | Connexion";
        $meta_description = "Connectez-vous à votre espace Jobeo pour accéder aux offres de stage et gérer vos candidatures.";
        $meta_keywords    = "connexion Jobeo, espace étudiant, espace pilote";

        require __DIR__ . '/../vues/connexion_vue.php';
    }

    // LOGIN — traite le POST de connexion
    public function login(): void
    {
        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalide.');
        }

        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $erreur = "Veuillez remplir tous les champs.";
            require __DIR__ . '/../vues/connexion_vue.php';
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = "L'adresse email n'est pas valide.";
            require __DIR__ . '/../vues/connexion_vue.php';
            return;
        }

        $utilisateur = $this->modele->findByEmail($email);

        if ($utilisateur && password_verify($password, $utilisateur['mdp'])) {
            $_SESSION['user'] = $utilisateur;

            // Redirection selon le rôle
            switch ($utilisateur['role']) {
                case 'admin':
                case 'pilote':
                    header('Location: /index.php?page=entreprises');
                    break;
                default:
                    header('Location: /index.php?page=offres_emplois');
                    break;
            }
        } else {
            $erreur = "Email ou mot de passe incorrect.";
            require __DIR__ . '/../vues/connexion_vue.php';
        }
        exit();
    }

    // INSCRIPTION — affiche le formulaire
    // Réservé admin + pilote (matrice SFx17)
    public function inscription(): void
    {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'pilote'])) {
            header('Location: /index.php?page=accueil');
            exit();
        }

        $email = $_GET['email'] ?? '';
        $role  = $_GET['role']  ?? 'etudiant';

        $meta_title       = "Jobeo | Créer un compte";
        $meta_description = "Créez un compte étudiant ou pilote sur la plateforme Jobeo.";
        $meta_keywords    = "inscription Jobeo, créer compte étudiant, créer compte pilote";

        require __DIR__ . '/../vues/inscription_etudiant_vue.php';
    }

    // REGISTER — traite le POST d'inscription
    // Réservé admin + pilote (matrice SFx17)
    public function register(): void
    {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'pilote'])) {
            header('Location: /index.php?page=accueil');
            exit();
        }

        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalide.');
        }

        $role = $_POST['role'] ?? 'etudiant';

        // Sécurité : un pilote ne peut créer que des étudiants
        if ($_SESSION['user']['role'] === 'pilote') {
            $role = 'etudiant';
        }

        if (!in_array($role, ['etudiant', 'pilote'])) {
            $role = 'etudiant';
        }

        $id_pilote = null;
        if ($_SESSION['user']['role'] === 'pilote') {
            $id_pilote = $_SESSION['user']['id_utilisateur'];
        }

        $email            = trim($_POST['email']            ?? '');
        $nom              = trim($_POST['nom']              ?? '');
        $prenom           = trim($_POST['prenom']           ?? '');
        $password         = $_POST['password']         ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        // Validation champs obligatoires
        if (empty($email) || empty($nom) || empty($prenom) || empty($password)) {
            $erreur = "Tous les champs sont obligatoires.";
            require __DIR__ . '/../vues/inscription_etudiant_vue.php';
            return;
        }

        // Validation email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = "L'adresse email n'est pas valide.";
            require __DIR__ . '/../vues/inscription_etudiant_vue.php';
            return;
        }

        // Validation longueur mot de passe
        if (strlen($password) < 8) {
            $erreur = "Le mot de passe doit contenir au moins 8 caractères.";
            require __DIR__ . '/../vues/inscription_etudiant_vue.php';
            return;
        }

        // Validation confirmation mot de passe
        if ($password !== $password_confirm) {
            $erreur = "Les mots de passe ne correspondent pas.";
            require __DIR__ . '/../vues/inscription_etudiant_vue.php';
            return;
        }

        // Validation CGU
        if (empty($_POST['accepte_cgu']) || empty($_POST['accepte_confidentialite'])) {
            $erreur = "Vous devez accepter les CGU et la politique de confidentialité.";
            require __DIR__ . '/../vues/inscription_etudiant_vue.php';
            return;
        }

        // Vérification email déjà utilisé
        if ($this->modele->findByEmail($email)) {
            $erreur = "Cette adresse email est déjà utilisée.";
            require __DIR__ . '/../vues/inscription_etudiant_vue.php';
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $this->modele->creer([
                'email'     => $email,
                'nom'       => $nom,
                'prenom'    => $prenom,
                'mdp'       => $hash,
                'role'      => $role,
                'id_pilote' => $id_pilote
            ]);
        } catch (Exception $e) {
            $erreur = "Une erreur est survenue lors de la création du compte.";
            require __DIR__ . '/../vues/inscription_etudiant_vue.php';
            return;
        }

        header('Location: /index.php?page=accueil');
        exit();
    }

    // MON COMPTE — profil utilisateur connecté
    public function monCompte(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /index.php?page=auth&action=connexion');
            exit();
        }

        $utilisateur = $_SESSION['user'];

        $meta_title       = "Jobeo | Mon Compte";
        $meta_description = "Gérez votre profil et vos informations personnelles sur Jobeo.";
        $meta_keywords    = "mon compte, profil Jobeo, espace personnel";

        require __DIR__ . '/../vues/mon_compte_vue.php';
    }

    // DÉCONNEXION
    public function deconnexion(): void
    {
        session_destroy();
        header('Location: /index.php?page=auth&action=connexion');
        exit();
    }
}