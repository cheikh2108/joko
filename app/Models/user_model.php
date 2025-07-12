<?php

/**
 * Modèle pour la gestion des utilisateurs (version fonctionnelle)
 */

/**
 * Crée un nouvel utilisateur
 */
function createUser(PDO $pdo, string $username, string $email, string $password): bool {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([$username, $email, $hashedPassword]);
}

/**
 * Vérifie les identifiants de connexion
 */
function authenticateUser(PDO $pdo, string $email, string $password): ?array {
    $sql = "SELECT id, username, email, password FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        unset($user['password']); // Ne pas retourner le mot de passe
        return $user;
    }
    
    return null;
}

/**
 * Récupère un utilisateur par son ID
 */
function getUserById(PDO $pdo, int $id): ?array {
    $sql = "SELECT id, username, email, created_at FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    
    return $stmt->fetch() ?: null;
}

/**
 * Récupère un utilisateur par son email
 */
function getUserByEmail(PDO $pdo, string $email): ?array {
    $sql = "SELECT id, username, email, created_at FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    
    return $stmt->fetch() ?: null;
}

/**
 * Vérifie si un email existe déjà
 */
function emailExists(PDO $pdo, string $email): bool {
    $sql = "SELECT COUNT(*) FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    
    return $stmt->fetchColumn() > 0;
}

/**
 * Vérifie si un nom d'utilisateur existe déjà
 */
function usernameExists(PDO $pdo, string $username): bool {
    $sql = "SELECT COUNT(*) FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    
    return $stmt->fetchColumn() > 0;
}
