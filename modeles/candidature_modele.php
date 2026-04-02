<?php
class CandidatureModele {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

  public function creerCandidature(array $donnees): bool {
    $sql = "INSERT INTO candidature (id_offre, id_utilisateur, cv, lettre_motivation)
            VALUES (:id_offre, :id_utilisateur, :cv, :lettre_motivation)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($donnees); 
}

    public function getCandidaturesParUtilisateur(int $id_utilisateur): array {
    $sql = "SELECT c.*, o.titre, e.nom AS nom_entreprise
            FROM candidature c
            JOIN offre o ON c.id_offre = o.id_offre
            JOIN entreprise e ON o.id_entreprise = e.id_entreprise
            WHERE c.id_utilisateur = :id_utilisateur";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id_utilisateur' => $id_utilisateur]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getCandidaturesByPilote(int $id_pilote, string $role, string $search = ''): array {
        $sql = "
            SELECT 
                c.id_candidature,
                c.cv,
                c.lettre_motivation,
                u.nom,
                u.prenom,
                u.email,
                o.titre AS titre_offre,
                o.id_offre,
                e.nom AS nom_entreprise
            FROM candidature c
            JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
            JOIN offre o ON c.id_offre = o.id_offre
            JOIN entreprise e ON o.id_entreprise = e.id_entreprise
            WHERE u.role = 'etudiant'
        ";

        if ($role === 'pilote') {
            $sql .= " AND u.id_pilote = :id_pilote";
        }

        if (!empty($search)) {
            $sql .= " AND (u.nom LIKE :search OR u.prenom LIKE :search OR o.titre LIKE :search)";
        }

        $sql .= " ORDER BY c.id_candidature DESC";

        $stmt = $this->pdo->prepare($sql);

        if ($role === 'pilote') {
            $stmt->bindValue(':id_pilote', $id_pilote);
        }

        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%');
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function dejaCandidature(int $id_offre, int $id_utilisateur): bool {
        $sql  = "SELECT COUNT(*) FROM candidature 
                 WHERE id_offre = :id_offre AND id_utilisateur = :id_utilisateur";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_offre'       => $id_offre,
            'id_utilisateur' => $id_utilisateur,
        ]);
        return $stmt->fetchColumn() > 0;
    }

    public function getCandidaturesPilote() {
        $stmt = $this->pdo->prepare("
            SELECT candidature.*, 
                offre.titre AS titre_offre,
                entreprise.nom AS nom_entreprise,
                utilisateur.nom AS nom_etudiant,
                utilisateur.prenom AS prenom_etudiant,
                utilisateur.email AS email_etudiant
            FROM candidature
            JOIN offre ON candidature.id_offre = offre.id_offre
            JOIN entreprise ON offre.id_entreprise = entreprise.id_entreprise
            JOIN utilisateur ON candidature.id_utilisateur = utilisateur.id_utilisateur
            ORDER BY offre.titre, utilisateur.nom
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    

}
?>