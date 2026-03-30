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

    public function identifier(): void
    {
        require __DIR__ . '/../vues/identifier_vue.php';
    }


    public function check(): void
    {
        $email = $_POST['email'] ?? '';
        $utilisateur = $this->modele->findByEmail($email);

        if ($utilisateur) {
            header('Location: /index.php?page=auth&action=connexion&email=' . urlencode($email));
        } else {
            header('Location: /index.php?page=auth&action=inscription&email=' . urlencode($email));
        }
        exit();
    }

    // Affiche le formulaire de connexion
    public function connexion(): void
    {
        $email = $_GET['email'] ?? '';
        require __DIR__ . '/../vues/connexion_vue.php';
    }

    // Traite le POST de connexion
    public function login(): void
    {
        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalide.');
        }

        $email    = $_POST['email']    ?? '';
        $password = $_POST['password'] ?? '';

        $utilisateur = $this->modele->findByEmail($email);

        if ($utilisateur && password_verify($password, $utilisateur['mdp'])) {
            $_SESSION['user'] = $utilisateur;
            header('Location: /index.php?page=accueil');
        } else {
            $erreur = "Email ou mot de passe incorrect.";
            require __DIR__ . '/../vues/connexion_vue.php';
        }
        exit();
    }

    // Affiche le formulaire d'inscription
    public function inscription(): void
    {
        $email = $_GET['email'] ?? '';
        require __DIR__ . '/../vues/inscription_particulier_vue.php';
    }

    // Traite le POST d'inscription
    public function register(): void
    {

        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalide.');
        }

        $email            = $_POST['email']            ?? '';
        $nom              = $_POST['nom']              ?? '';
        $prenom           = $_POST['prenom']           ?? '';
        $password         = $_POST['password']              ?? '';
        $password_confirm = $_POST['password_confirm']      ?? '';

        // Vérification CGU
        if (empty($_POST['accepte_cgu']) || empty($_POST['accepte_confidentialite'])) {
            $erreur = "Vous devez accepter les CGU et la politique de confidentialité.";
            require __DIR__ . '/../vues/inscription_particulier_vue.php';
            return;
        }

        // Vérification mots de passe
        if ($password !== $password_confirm) {
            $erreur = "Les mots de passe ne correspondent pas.";
            require __DIR__ . '/../vues/inscription_particulier_vue.php';
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $this->modele->creer([
                'email'    => $email,
                'nom'      => $nom,
                'prenom'   => $prenom,
                'mdp'      => $hash,
                'role'     => 'etudiant'
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $utilisateur = $this->modele->findByEmail($email);
        $_SESSION['user'] = $utilisateur;

        header('Location: /index.php?page=accueil');
        exit();
    }

    // Déconnexion
    public function deconnexion(): void
    {
        session_destroy();
        header('Location: /index.php?page=auth&action=connexion');
        exit();
    }
}