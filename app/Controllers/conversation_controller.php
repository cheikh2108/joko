<?php
// C'est le contrôleur pour les conversations
function listConversations(PDO $pdo, string $theme_class) {
    require_once APP_PATH . '/Core/helpers.php';
    
    // Vérifier si l'utilisateur est connecté
    if (!isLoggedIn()) {
        redirect(BASE_URL . '/login');
    }
    
    // Logique pour récupérer et afficher la liste des conversations
    $pageTitle = "Mes Conversations - Joko";
    require_once VIEWS_PATH . '/includes/header.php';
    require_once VIEWS_PATH . '/messages/conversation_list.php';
    require_once VIEWS_PATH . '/includes/footer.php';
}

function deleteConversation(PDO $pdo, string $theme_class) {
    require_once APP_PATH . '/Core/helpers.php';
    require_once APP_PATH . '/Models/conversation_model.php';
    
    if (!isLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Non autorisé'], 401);
    }
    
    if (!isAjaxRequest()) {
        jsonResponse(['success' => false, 'message' => 'Requête invalide'], 400);
    }
    
    // Récupérer l'ID de la conversation depuis l'URL
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $parts = explode('/', trim($uri, '/'));
    $conversationId = end($parts);
    
    if (!is_numeric($conversationId)) {
        jsonResponse(['success' => false, 'message' => 'ID de conversation invalide']);
    }
    
    if (deleteConversation($pdo, (int)$conversationId, getCurrentUserId())) {
        jsonResponse(['success' => true, 'message' => 'Conversation supprimée avec succès']);
    } else {
        jsonResponse(['success' => false, 'message' => 'Erreur lors de la suppression']);
    }
}