<?php
require_once __DIR__ . '/../modeles/utilisateur_modele.php';

class PilotesControleur
{
    private PDO $pdo;
    private UtilisateurModele $modele;

    public function __construct(PDO $pdo)
    {
        $this->pdo    = $pdo;
        $this->modele = new UtilisateurModele($pdo);
    }

    private function verifierAdmin(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /index.php?page=accueil');
            exit();
        }
    }

    public function index(): void
    {
        $this->verifierAdmin();
        $search  = $_GET['search'] ?? '';
        $pilotes = $this->modele->findAllPilotes($search);
        $total   = count($pilotes);
        require __DIR__ . '/../vues/pilotes_vue.php';
    }

    public function edit(): void
    {
        $this->verifierAdmin();
        $id     = $_GET['id'] ?? null;
        $pilote = $this->modele->findById($id);

        if (!$pilote) {
            header('Location: /index.php?page=pilotes');
            exit();
        }
        require __DIR__ . '/../vues/pilote_edit_vue.php';
    }

    public function update(): void
    {
        $this->verifierAdmin();
        $id     = $_POST['id']     ?? null;
        $nom    = $_POST['nom']    ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email  = $_POST['email']  ?? '';

        $this->modele->update($id, [
            'nom'    => $nom,
            'prenom' => $prenom,
            'email'  => $email,
        ]);

        header('Location: /index.php?page=pilotes');
        exit();
    }

    public function delete(): void
    {
        $this->verifierAdmin();
        $id = $_GET['id'] ?? null;
        $this->modele->delete($id);

        header('Location: /index.php?page=pilotes');
        exit();
    }
}
