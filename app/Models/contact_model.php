<?php

/**
 * Modèle pour la gestion des contacts (version fonctionnelle)
 */

/**
 * Récupère tous les contacts d'un utilisateur
 */
function getContactsByUserId(PDO $pdo, int $userId): array {
    $sql = "SELECT c.*, u.username, u.email 
            FROM contacts c 
            JOIN users u ON c.contact_user_id = u.id 
            WHERE c.user_id = ? 
            ORDER BY u.username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    
    return $stmt->fetchAll();
}

/**
 * Ajoute un contact pour un utilisateur
 */
function addContact(PDO $pdo, int $userId, int $contactUserId): bool {
    // Vérifier si le contact n'existe pas déjà
    if (contactExists($pdo, $userId, $contactUserId)) {
        return false;
    }
    
    $sql = "INSERT INTO contacts (user_id, contact_user_id, created_at) VALUES (?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([$userId, $contactUserId]);
}

/**
 * Supprime un contact
 */
function removeContact(PDO $pdo, int $userId, int $contactUserId): bool {
    $sql = "DELETE FROM contacts WHERE user_id = ? AND contact_user_id = ?";
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([$userId, $contactUserId]);
}

/**
 * Vérifie si un contact existe déjà
 */
function contactExists(PDO $pdo, int $userId, int $contactUserId): bool {
    $sql = "SELECT COUNT(*) FROM contacts WHERE user_id = ? AND contact_user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId, $contactUserId]);
    
    return $stmt->fetchColumn() > 0;
}

/**
 * Recherche des utilisateurs pour ajouter en contact
 */
function searchUsersForContact(PDO $pdo, int $userId, string $searchTerm): array {
    $sql = "SELECT id, username, email 
            FROM users 
            WHERE (username LIKE ? OR email LIKE ?) 
            AND id != ? 
            AND id NOT IN (
                SELECT contact_user_id FROM contacts WHERE user_id = ?
            )
            ORDER BY username 
            LIMIT 10";
    $stmt = $pdo->prepare($sql);
    $searchPattern = "%$searchTerm%";
    $stmt->execute([$searchPattern, $searchPattern, $userId, $userId]);
    
    return $stmt->fetchAll();
}
