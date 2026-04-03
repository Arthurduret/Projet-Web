<?php
require_once __DIR__ . '/../modeles/utilisateur_modele.php';
require_once __DIR__ . '/../helper/csrf.php';

class PilotesControleur
{
    private PDO $pdo;
    private UtilisateurModele $modele;

    public function __construct(PDO $pdo)
    {
        $this->pdo    = $pdo;
        $this->modele = new UtilisateurModele($pdo);
    }

    // -----------------------------------------------
    // Vérifie que l'utilisateur est admin (SFx12-15)
    // -----------------------------------------------
    private function verifierAdmin(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /index.php?page=auth&action=connexion');
            exit();
        }
    }

    // -----------------------------------------------
    // LISTE PILOTES — admin seulement (SFx12)
    // -----------------------------------------------
    public function index(): void
    {
        $this->verifierAdmin();

        $search  = trim($_GET['search'] ?? '');
        $pilotes = $this->modele->findAllPilotes($search);
        $total   = count($pilotes);

        $meta_title       = "Jobeo | Gestion des Pilotes";
        $meta_description = "Gérez les comptes pilotes de la plateforme Jobeo.";
        $meta_keywords    = "pilotes, gestion comptes, admin Jobeo";

        require __DIR__ . '/../vues/pilotes_vue.php';
    }

    // -----------------------------------------------
    // FORMULAIRE MODIFICATION — admin seulement (SFx14)
    // -----------------------------------------------
    public function edit(): void
    {
        $this->verifierAdmin();

        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            header('Location: /index.php?page=pilotes');
            exit();
        }

        $pilote = $this->modele->findById($id);

        if (!$pilote || $pilote['role'] !== 'pilote') {
            header('Location: /index.php?page=pilotes');
            exit();
        }

        $meta_title       = "Jobeo | Modifier " . htmlspecialchars($pilote['prenom']) . " " . htmlspecialchars($pilote['nom']);
        $meta_description = "Modifiez les informations du compte pilote sur Jobeo.";
        $meta_keywords    = "modifier pilote, gestion compte, Jobeo admin";

        require __DIR__ . '/../vues/pilote_edit_vue.php';
    }

    // -----------------------------------------------
    // TRAITEMENT MODIFICATION — admin seulement (SFx14)
    // -----------------------------------------------
    public function update(): void
    {
        $this->verifierAdmin();

        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalide.');
        }

        $id     = (int)($_POST['id']     ?? 0);
        $nom    = trim($_POST['nom']    ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email  = trim($_POST['email']  ?? '');

        if (!$id) {
            header('Location: /index.php?page=pilotes');
            exit();
        }

        // Validation champs obligatoires
        if (empty($nom) || empty($prenom) || empty($email)) {
            header('Location: /index.php?page=pilotes&action=edit&id=' . $id . '&erreur=champs_obligatoires');
            exit();
        }

        // Validation email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /index.php?page=pilotes&action=edit&id=' . $id . '&erreur=email_invalide');
            exit();
        }

        // Vérification que c'est bien un pilote
        $pilote = $this->modele->findById($id);
        if (!$pilote || $pilote['role'] !== 'pilote') {
            header('Location: /index.php?page=pilotes');
            exit();
        }

        $this->modele->update($id, [
            'nom'    => $nom,
            'prenom' => $prenom,
            'email'  => $email,
        ]);

        header('Location: /index.php?page=pilotes');
        exit();
    }

    // -----------------------------------------------
    // SUPPRESSION — admin seulement (SFx15)
    // -----------------------------------------------
    public function delete(): void
    {
        $this->verifierAdmin();

        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            header('Location: /index.php?page=pilotes');
            exit();
        }

        // Vérification que c'est bien un pilote
        $pilote = $this->modele->findById($id);
        if (!$pilote || $pilote['role'] !== 'pilote') {
            header('Location: /index.php?page=pilotes');
            exit();
        }

        $this->modele->delete($id);

        header('Location: /index.php?page=pilotes');
        exit();
    }
}