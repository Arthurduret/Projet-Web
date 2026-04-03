<?php
class UtilisateurModele
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // -----------------------------------------------
    // Trouve un utilisateur par email (SFx1)
    // -----------------------------------------------
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------
    // Crée un utilisateur (SFx13/SFx17)
    // -----------------------------------------------
    public function creer(array $data): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO utilisateur (email, nom, prenom, mdp, role, id_pilote)
            VALUES (:email, :nom, :prenom, :mdp, :role, :id_pilote)
        ");
        $stmt->execute($data);
    }

    // -----------------------------------------------
    // Trouve un utilisateur par id
    // -----------------------------------------------
    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------
    // Récupère les étudiants d'un pilote (SFx16)
    // -----------------------------------------------
    public function findEtudiantsByPilote(int $id_pilote, string $search = ''): array
    {
        $sql = "SELECT * FROM utilisateur
                WHERE role = 'etudiant' AND id_pilote = :id_pilote";

        if (!empty($search)) {
            $sql .= " AND (nom LIKE :search OR prenom LIKE :search OR email LIKE :search)";
        }

        $sql .= " ORDER BY nom ASC, prenom ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_pilote', $id_pilote, PDO::PARAM_INT);

        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------
    // Récupère tous les étudiants — admin (SFx16)
    // -----------------------------------------------
    public function findAllEtudiants(string $search = ''): array
    {
        $sql = "SELECT * FROM utilisateur WHERE role = 'etudiant'";

        if (!empty($search)) {
            $sql .= " AND (nom LIKE :search OR prenom LIKE :search OR email LIKE :search)";
        }

        $sql .= " ORDER BY nom ASC, prenom ASC";

        $stmt = $this->pdo->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------
    // Récupère tous les pilotes — admin (SFx12)
    // -----------------------------------------------
    public function findAllPilotes(string $search = ''): array
    {
        $sql = "SELECT * FROM utilisateur WHERE role = 'pilote'";

        if (!empty($search)) {
            $sql .= " AND (nom LIKE :search OR prenom LIKE :search OR email LIKE :search)";
        }

        $sql .= " ORDER BY nom ASC, prenom ASC";

        $stmt = $this->pdo->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------
    // Met à jour un utilisateur (SFx14/SFx18)
    // -----------------------------------------------
    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE utilisateur
            SET nom    = :nom,
                prenom = :prenom,
                email  = :email
            WHERE id_utilisateur = :id
        ");
        $data[':id'] = $id;
        $stmt->execute($data);
    }

    // -----------------------------------------------
    // Supprime un utilisateur (SFx15/SFx19)
    // Supprime aussi ses candidatures et favoris
    // -----------------------------------------------
    public function delete(int $id): void
    {
        // Supprime les favoris liés
        $stmt = $this->pdo->prepare("DELETE FROM liker WHERE id_utilisateur = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Supprime les candidatures liées
        $stmt = $this->pdo->prepare("DELETE FROM candidature WHERE id_utilisateur = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Supprime les évaluations liées
        $stmt = $this->pdo->prepare("DELETE FROM evaluation WHERE id_utilisateur = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Supprime l'utilisateur
        $stmt = $this->pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>