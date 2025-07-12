<?php

/**
 * Modèle pour la gestion des messages (version fonctionnelle)
 */

/**
 * Récupère tous les messages d'une conversation
 */
function getMessagesByConversationId(PDO $pdo, int $conversationId, int $userId, int $limit = 50, int $offset = 0): array {
    // Vérifier que l'utilisateur fait partie de la conversation
    $sql = "SELECT COUNT(*) FROM conversations 
            WHERE id = ? AND (user1_id = ? OR user2_id = ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$conversationId, $userId, $userId]);
    
    if ($stmt->fetchColumn() == 0) {
        return [];
    }
    
    $sql = "SELECT m.*, u.username, u.email
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.conversation_id = ?
            ORDER BY m.created_at DESC
            LIMIT ? OFFSET ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$conversationId, $limit, $offset]);
    
    return array_reverse($stmt->fetchAll()); // Inverser pour avoir l'ordre chronologique
}

/**
 * Récupère un message par son ID
 */
function getMessageById(PDO $pdo, int $messageId): ?array {
    $sql = "SELECT m.*, u.username, u.email
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$messageId]);
    
    return $stmt->fetch() ?: null;
}

/**
 * Envoie un nouveau message
 */
function sendMessage(PDO $pdo, int $conversationId, int $senderId, string $content): ?int {
    // Vérifier que l'expéditeur fait partie de la conversation
    $sql = "SELECT COUNT(*) FROM conversations 
            WHERE id = ? AND (user1_id = ? OR user2_id = ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$conversationId, $senderId, $senderId]);
    
    if ($stmt->fetchColumn() == 0) {
        return null;
    }
    
    $sql = "INSERT INTO messages (conversation_id, sender_id, content, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$conversationId, $senderId, $content])) {
        return $pdo->lastInsertId();
    }
    
    return null;
}

/**
 * Supprime un message
 */
function deleteMessage(PDO $pdo, int $messageId, int $userId): bool {
    // Vérifier que l'utilisateur est l'expéditeur du message
    $sql = "SELECT COUNT(*) FROM messages WHERE id = ? AND sender_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$messageId, $userId]);
    
    if ($stmt->fetchColumn() == 0) {
        return false;
    }
    
    $sql = "DELETE FROM messages WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([$messageId]);
}

/**
 * Récupère le dernier message d'une conversation
 */
function getLastMessageByConversationId(PDO $pdo, int $conversationId): ?array {
    $sql = "SELECT m.*, u.username
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.conversation_id = ?
            ORDER BY m.created_at DESC
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$conversationId]);
    
    return $stmt->fetch() ?: null;
}

/**
 * Marque les messages comme lus
 */
function markMessagesAsRead(PDO $pdo, int $conversationId, int $userId): bool {
    $sql = "UPDATE messages 
            SET is_read = 1 
            WHERE conversation_id = ? 
            AND sender_id != ? 
            AND is_read = 0";
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([$conversationId, $userId]);
}

/**
 * Compte les messages non lus d'un utilisateur
 */
function getUnreadMessageCount(PDO $pdo, int $userId): int {
    $sql = "SELECT COUNT(*) 
            FROM messages m
            JOIN conversations c ON m.conversation_id = c.id
            WHERE (c.user1_id = ? OR c.user2_id = ?)
            AND m.sender_id != ?
            AND m.is_read = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId, $userId, $userId]);
    
    return $stmt->fetchColumn();
}
