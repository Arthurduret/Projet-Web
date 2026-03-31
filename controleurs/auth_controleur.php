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

    // public function identifier(): void
    // {
    //     require __DIR__ . '/../vues/identifier_vue.php';
    // }


    // public function check(): void
    // {
    //     $email = $_POST['email'] ?? '';
    //     $utilisateur = $this->modele->findByEmail($email);

    //     if ($utilisateur) {
    //         header('Location: /index.php?page=auth&action=connexion&email=' . urlencode($email));
    //     } else {
    //         $erreur = "Aucun compte associé à cet email.";
    //         require __DIR__ . '/../vues/identifier_vue.php';
    //     }
    //     exit();
    // }

    public function connexion(): void
    {
        $email = $_GET['email'] ?? '';
        require __DIR__ . '/../vues/connexion_vue.php';
    }

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

    public function inscription(): void
    {

        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'pilote'])) {
        header('Location: /index.php?page=accueil');
        exit();
        }

        $email = $_GET['email'] ?? '';
        $role  = $_GET['role']  ?? 'etudiant';
        require __DIR__ . '/../vues/inscription_etudiant_vue.php';
    }

    public function register(): void
    {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'pilote'])) {
        header('Location: /index.php?page=accueil');
        exit();
    }

        $role = $_POST['role'] ?? 'etudiant';
    

        if (!in_array($role, ['etudiant', 'pilote'])) {
            $role = 'etudiant';
        }

        $id_pilote = null;

        if ($_SESSION['user']['role'] === 'pilote') {
            $id_pilote = $_SESSION['user']['id_utilisateur'];
            $role = 'etudiant';
        }

        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalide.');
        }

        $email            = $_POST['email']            ?? '';
        $nom              = $_POST['nom']              ?? '';
        $prenom           = $_POST['prenom']           ?? '';
        $password         = $_POST['password']              ?? '';
        $password_confirm = $_POST['password_confirm']      ?? '';


        if (empty($_POST['accepte_cgu']) || empty($_POST['accepte_confidentialite'])) {
            $erreur = "Vous devez accepter les CGU et la politique de confidentialité.";
            require __DIR__ . '/../vues/inscription_etudiant_vue.php';
            return;
        }


        if ($password !== $password_confirm) {
            $erreur = "Les mots de passe ne correspondent pas.";
            require __DIR__ . '/../vues/inscription_etudiant_vue.php';
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $this->modele->creer([
                'email'    => $email,
                'nom'      => $nom,
                'prenom'   => $prenom,
                'mdp'      => $hash,
                'role'     => $role,
                'id_pilote' => $id_pilote
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }

        header('Location: /index.php?page=accueil');
        exit();
    }

    public function monCompte(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /index.php?page=auth&action=connexion');
            exit();
        }
        $utilisateur = $_SESSION['user'];
        require __DIR__ . '/../vues/mon_compte_vue.php';
    }


    public function deconnexion(): void
    {
        session_destroy();
        header('Location: /index.php?page=auth&action=connexion');
        exit();
    }
}