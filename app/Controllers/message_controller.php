<?php
// C'est le contrôleur pour les messages
function listMessages(PDO $pdo, string $theme_class) {
    // Logique pour récupérer et afficher les messages d'une conversation
    // Pour l'instant, juste un placeholder
    $pageTitle = "Messages - Joko";
    require_once VIEWS_PATH . '/includes/header.php';
    require_once VIEWS_PATH . '/messages/message_list.php';
    require_once VIEWS_PATH . '/includes/footer.php';
}

function sendMessage(PDO $pdo, string $theme_class) {
    // Logique pour traiter l'envoi d'un message
    $pageTitle = "Envoyer un Message - Joko";
    require_once VIEWS_PATH . '/includes/header.php';
    require_once VIEWS_PATH . '/messages/send_message.php';
    require_once VIEWS_PATH . '/includes/footer.php';
}