<?php

/**
 * Modèle pour la gestion des conversations (version fonctionnelle)
 */

/**
 * Récupère toutes les conversations d'un utilisateur
 */
function getConversationsByUserId(PDO $pdo, int $userId): array {
    $sql = "SELECT c.*, 
                   u1.username as user1_username, 
                   u2.username as user2_username,
                   (SELECT COUNT(*) FROM messages m WHERE m.conversation_id = c.id) as message_count,
                   (SELECT MAX(created_at) FROM messages m WHERE m.conversation_id = c.id) as last_message_date
            FROM conversations c
            JOIN users u1 ON c.user1_id = u1.id
            JOIN users u2 ON c.user2_id = u2.id
            WHERE c.user1_id = ? OR c.user2_id = ?
            ORDER BY last_message_date DESC, c.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId, $userId]);
    
    return $stmt->fetchAll();
}

/**
 * Récupère une conversation par son ID
 */
function getConversationById(PDO $pdo, int $conversationId, int $userId): ?array {
    $sql = "SELECT c.*, 
                   u1.username as user1_username, 
                   u2.username as user2_username
            FROM conversations c
            JOIN users u1 ON c.user1_id = u1.id
            JOIN users u2 ON c.user2_id = u2.id
            WHERE c.id = ? AND (c.user1_id = ? OR c.user2_id = ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$conversationId, $userId, $userId]);
    
    return $stmt->fetch() ?: null;
}

/**
 * Crée une nouvelle conversation entre deux utilisateurs
 */
function createConversation(PDO $pdo, int $user1Id, int $user2Id): ?int {
    // Vérifier si une conversation existe déjà
    $existingConversation = getConversationBetweenUsers($pdo, $user1Id, $user2Id);
    if ($existingConversation) {
        return $existingConversation['id'];
    }
    
    $sql = "INSERT INTO conversations (user1_id, user2_id, created_at) VALUES (?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$user1Id, $user2Id])) {
        return $pdo->lastInsertId();
    }
    
    return null;
}

/**
 * Récupère une conversation entre deux utilisateurs
 */
function getConversationBetweenUsers(PDO $pdo, int $user1Id, int $user2Id): ?array {
    $sql = "SELECT * FROM conversations 
            WHERE (user1_id = ? AND user2_id = ?) 
               OR (user1_id = ? AND user2_id = ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user1Id, $user2Id, $user2Id, $user1Id]);
    
    return $stmt->fetch() ?: null;
}

/**
 * Supprime une conversation
 */
function deleteConversation(PDO $pdo, int $conversationId, int $userId): bool {
    // Vérifier que l'utilisateur fait partie de la conversation
    $conversation = getConversationById($pdo, $conversationId, $userId);
    if (!$conversation) {
        return false;
    }
    
    $sql = "DELETE FROM conversations WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([$conversationId]);
}
