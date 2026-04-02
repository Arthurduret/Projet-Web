<?php
class UtilisateurModele
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

        public function creer(array $data): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO utilisateur (email, nom, prenom, mdp, role, id_pilote)
            VALUES (:email, :nom, :prenom, :mdp, :role, :id_pilote)
        ");
        $stmt->execute($data);
    }

    public function findEtudiantsByPilote(int $id_pilote, string $search = ''): array
    {
        $sql = "SELECT * FROM utilisateur 
                WHERE role = 'etudiant' AND id_pilote = :id_pilote";

        if (!empty($search)) {
            $sql .= " AND (nom LIKE :search OR prenom LIKE :search OR email LIKE :search)";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_pilote', $id_pilote);

        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%');
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAllEtudiants(string $search = ''): array
    {
        $sql = "SELECT * FROM utilisateur WHERE role = 'etudiant'";

        if (!empty($search)) {
            $sql .= " AND (nom LIKE :search OR prenom LIKE :search OR email LIKE :search)";
        }

        $stmt = $this->pdo->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%');
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAllPilotes(string $search = ''): array
{
    $sql = "SELECT * FROM utilisateur WHERE role = 'pilote'";

    if (!empty($search)) {
        $sql .= " AND (nom LIKE :search OR prenom LIKE :search OR email LIKE :search)";
    }

    $stmt = $this->pdo->prepare($sql);

    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%');
    }

    $stmt->execute();
    return $stmt->fetchAll();
}

    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE utilisateur 
            SET nom = :nom, prenom = :prenom, email = :email
            WHERE id_utilisateur = :id
        ");
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
