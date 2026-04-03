<?php
require_once __DIR__ . '/../modeles/utilisateur_modele.php';
require_once __DIR__ . '/../helper/csrf.php';

class EtudiantsControleur
{
    private PDO $pdo;
    private UtilisateurModele $modele;

    public function __construct(PDO $pdo)
    {
        $this->pdo    = $pdo;
        $this->modele = new UtilisateurModele($pdo);
    }

    // -----------------------------------------------
    // Vérifie que l'utilisateur est pilote ou admin
    // -----------------------------------------------
    private function verifierPilote(): void
    {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['pilote', 'admin'])) {
            header('Location: /index.php?page=auth&action=connexion');
            exit();
        }
    }

    // -----------------------------------------------
    // LISTE — pilote voit ses étudiants, admin voit tous (SFx16)
    // -----------------------------------------------
    public function index(): void
    {
        $this->verifierPilote();

        $search = trim($_GET['search'] ?? '');

        if ($_SESSION['user']['role'] === 'admin') {
            $etudiants = $this->modele->findAllEtudiants($search);
        } else {
            $id_pilote = $_SESSION['user']['id_utilisateur'];
            $etudiants = $this->modele->findEtudiantsByPilote($id_pilote, $search);
        }

        $total = count($etudiants);

        $meta_title       = "Jobeo | Mes Étudiants";
        $meta_description = "Gérez les comptes étudiants rattachés à votre promotion sur Jobeo.";
        $meta_keywords    = "étudiants, gestion comptes, pilote, promotion";

        require __DIR__ . '/../vues/mes_etudiants_vue.php';
    }

    // -----------------------------------------------
    // FORMULAIRE MODIFICATION — pilote + admin (SFx18)
    // -----------------------------------------------
    public function edit(): void
    {
        $this->verifierPilote();

        $id       = (int)($_GET['id'] ?? 0);

        if (!$id) {
            header('Location: /index.php?page=etudiants');
            exit();
        }

        $etudiant = $this->modele->findById($id);

        if (!$etudiant) {
            header('Location: /index.php?page=etudiants');
            exit();
        }

        // Un pilote ne peut modifier que ses propres étudiants
        if ($_SESSION['user']['role'] === 'pilote' &&
            $etudiant['id_pilote'] !== $_SESSION['user']['id_utilisateur']) {
            header('Location: /index.php?page=etudiants');
            exit();
        }

        $meta_title       = "Jobeo | Modifier " . htmlspecialchars($etudiant['prenom']) . " " . htmlspecialchars($etudiant['nom']);
        $meta_description = "Modifiez les informations du compte étudiant sur Jobeo.";
        $meta_keywords    = "modifier étudiant, gestion compte, Jobeo";

        require __DIR__ . '/../vues/etudiant_edit_vue.php';
    }

    // -----------------------------------------------
    // TRAITEMENT MODIFICATION — pilote + admin (SFx18)
    // -----------------------------------------------
    public function update(): void
    {
        $this->verifierPilote();

        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalide.');
        }

        $id     = (int)($_POST['id'] ?? 0);
        $nom    = trim($_POST['nom']    ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email  = trim($_POST['email']  ?? '');

        if (!$id) {
            header('Location: /index.php?page=etudiants');
            exit();
        }

        // Validation champs obligatoires
        if (empty($nom) || empty($prenom) || empty($email)) {
            header('Location: /index.php?page=etudiants&action=edit&id=' . $id . '&erreur=champs_obligatoires');
            exit();
        }

        // Validation email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /index.php?page=etudiants&action=edit&id=' . $id . '&erreur=email_invalide');
            exit();
        }

        // Un pilote ne peut modifier que ses propres étudiants
        if ($_SESSION['user']['role'] === 'pilote') {
            $etudiant = $this->modele->findById($id);
            if (!$etudiant || $etudiant['id_pilote'] !== $_SESSION['user']['id_utilisateur']) {
                header('Location: /index.php?page=etudiants');
                exit();
            }
        }

        $this->modele->update($id, [
            'nom'    => $nom,
            'prenom' => $prenom,
            'email'  => $email,
        ]);

        header('Location: /index.php?page=etudiants');
        exit();
    }

    // -----------------------------------------------
    // SUPPRESSION — pilote + admin (SFx19)
    // -----------------------------------------------
    public function delete(): void
    {
        $this->verifierPilote();

        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            header('Location: /index.php?page=etudiants');
            exit();
        }

        // Un pilote ne peut supprimer que ses propres étudiants
        if ($_SESSION['user']['role'] === 'pilote') {
            $etudiant = $this->modele->findById($id);
            if (!$etudiant || $etudiant['id_pilote'] !== $_SESSION['user']['id_utilisateur']) {
                header('Location: /index.php?page=etudiants');
                exit();
            }
        }

        $this->modele->delete($id);

        header('Location: /index.php?page=etudiants');
        exit();
    }
}