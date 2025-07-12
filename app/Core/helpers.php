<?php

/**
 * Fonctions utilitaires pour l'application
 */

/**
 * Échappe les caractères spéciaux pour l'affichage sécurisé
 */
function e(string $string): string {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirige vers une URL
 */
function redirect(string $url): void {
    header('Location: ' . $url);
    exit();
}

/**
 * Vérifie si l'utilisateur est connecté
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * Récupère l'ID de l'utilisateur connecté
 */
function getCurrentUserId(): ?int {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Formate une date pour l'affichage
 */
function formatDate(string $date, string $format = 'd/m/Y H:i'): string {
    return date($format, strtotime($date));
}

/**
 * Tronque un texte à une longueur donnée
 */
function truncateText(string $text, int $length = 100): string {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

/**
 * Génère un token CSRF
 */
function generateCSRFToken(): string {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie un token CSRF
 */
function verifyCSRFToken(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Affiche un message flash
 */
function setFlashMessage(string $type, string $message): void {
    $_SESSION['flash_' . $type] = $message;
}

/**
 * Récupère et supprime un message flash
 */
function getFlashMessage(string $type): ?string {
    $message = $_SESSION['flash_' . $type] ?? null;
    unset($_SESSION['flash_' . $type]);
    return $message;
}

/**
 * Vérifie si une requête est AJAX
 */
function isAjaxRequest(): bool {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Renvoie une réponse JSON
 */
function jsonResponse(array $data, int $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

/**
 * Valide une adresse email
 */
function isValidEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Nettoie une chaîne de caractères
 */
function sanitizeString(string $string): string {
    return filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
}
