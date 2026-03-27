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
            header('Location: /index.php?page=auth&action=connexion');
        } else {
            header('Location: /index.php?page=auth&action=inscription');
        }
        exit();
    }

    // Affiche le formulaire de connexion
    public function connexion(): void
    {
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

        if ($utilisateur && password_verify($password, $utilisateur['mot_de_passe'])) {
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
        require __DIR__ . '/../vues/inscription_particulier_vue.php';
    }

    // Traite le POST d'inscription
    public function register(): void
    {
        // à compléter selon tes champs
    }

    // Déconnexion
    public function deconnexion(): void
    {
        session_destroy();
        header('Location: /index.php?page=auth&action=connexion');
        exit();
    }
}