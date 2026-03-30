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
            INSERT INTO utilisateur (email, nom, prenom, mdp, role)
            VALUES (:email, :nom, :prenom, :mdp, :role)
        ");
        $stmt->execute($data);
    }
}
