<?php
// C'est le contrôleur pour les messages
function listMessages(PDO $pdo, string $theme_class) {
    require_once APP_PATH . '/Core/helpers.php';
    
    // Vérifier si l'utilisateur est connecté
    if (!isLoggedIn()) {
        redirect(BASE_URL . '/login');
    }
    
    // Récupérer l'ID de la conversation depuis l'URL
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $parts = explode('/', trim($uri, '/'));
    $conversationId = $parts[1] ?? null;
    
    if (!$conversationId || !is_numeric($conversationId)) {
        setFlashMessage('error', 'Conversation non trouvée');
        redirect(BASE_URL . '/conversations');
    }
    
    // Logique pour récupérer et afficher les messages d'une conversation
    $pageTitle = "Messages - Joko";
    require_once VIEWS_PATH . '/includes/header.php';
    require_once VIEWS_PATH . '/messages/message_list.php';
    require_once VIEWS_PATH . '/includes/footer.php';
}

function sendMessage(PDO $pdo, string $theme_class) {
    require_once APP_PATH . '/Core/helpers.php';
    require_once APP_PATH . '/Models/message_model.php';
    
    if (!isLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Non autorisé'], 401);
    }
    
    if (!isAjaxRequest()) {
        jsonResponse(['success' => false, 'message' => 'Requête invalide'], 400);
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    $conversationId = $input['conversation_id'] ?? null;
    $content = trim($input['content'] ?? '');
    
    if (!$conversationId || !is_numeric($conversationId)) {
        jsonResponse(['success' => false, 'message' => 'ID de conversation invalide']);
    }
    
    if (empty($content)) {
        jsonResponse(['success' => false, 'message' => 'Le message ne peut pas être vide']);
    }
    
    // Envoyer le message
    $messageId = sendMessage($pdo, (int)$conversationId, getCurrentUserId(), $content);
    
    if ($messageId) {
        // Récupérer les informations du message envoyé
        $message = getMessageById($pdo, $messageId);
        if ($message) {
            jsonResponse([
                'success' => true, 
                'message' => $message,
                'message_id' => $messageId
            ]);
        } else {
            jsonResponse(['success' => true, 'message_id' => $messageId]);
        }
    } else {
        jsonResponse(['success' => false, 'message' => 'Erreur lors de l\'envoi du message']);
    }
}