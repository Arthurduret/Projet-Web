<?php
require_once __DIR__ . '/../modeles/utilisateur_modele.php';

class EtudiantsControleur
{
    private PDO $pdo;
    private UtilisateurModele $modele;

    public function __construct(PDO $pdo)
    {
        $this->pdo    = $pdo;
        $this->modele = new UtilisateurModele($pdo);
    }

    private function verifierPilote(): void
    {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['pilote', 'admin'])) {
            header('Location: /index.php?page=accueil');
            exit();
        }
    }

    public function index(): void
    {
        $this->verifierPilote();

        $search = $_GET['search'] ?? '';

        if ($_SESSION['user']['role'] === 'admin') {
            $etudiants = $this->modele->findAllEtudiants($search);
        } else {
            $id_pilote = $_SESSION['user']['id_utilisateur'];
            $etudiants = $this->modele->findEtudiantsByPilote($id_pilote, $search);
        }

        $total = count($etudiants);
        require __DIR__ . '/../vues/mes_etudiants_vue.php';
    }

    public function edit(): void
    {
        $this->verifierPilote();

        $id       = $_GET['id'] ?? null;
        $etudiant = $this->modele->findById($id);

        if (!$etudiant) {
            header('Location: /index.php?page=etudiants');
            exit();
        }

        require __DIR__ . '/../vues/etudiant_edit_vue.php';
    }

    public function update(): void
    {
        $this->verifierPilote();

        $id     = $_POST['id']     ?? null;
        $nom    = $_POST['nom']    ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email  = $_POST['email']  ?? '';

        $this->modele->update($id, [
            'nom'    => $nom,
            'prenom' => $prenom,
            'email'  => $email,
        ]);

        header('Location: /index.php?page=etudiants');
        exit();
    }

    public function delete(): void
    {
        $this->verifierPilote();

        $id = $_GET['id'] ?? null;
        $this->modele->delete($id);

        header('Location: /index.php?page=etudiants');
        exit();
    }
}
