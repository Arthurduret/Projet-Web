<?php
function get_candidatures_by_user($id_user, $pdo) {
    $sql = "SELECT o.titre, o.entreprise, c.date_postulation 
            FROM candidatures c
            JOIN offres_emplois o ON c.id_offre = o.id
            WHERE c.id_utilisateur = :id_user
            ORDER BY c.date_postulation DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_user' => $id_user]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}