<?php
// C'est le contrôleur pour les conversations
function listConversations(PDO $pdo, string $theme_class) {
    // Logique pour récupérer et afficher la liste des conversations
    $pageTitle = "Mes Conversations - Joko";
    require_once VIEWS_PATH . '/includes/header.php';
    require_once VIEWS_PATH . '/messages/conversation_list.php';
    require_once VIEWS_PATH . '/includes/footer.php';
}