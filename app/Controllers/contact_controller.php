<?php
// C'est le contrôleur pour les contacts
function listContacts(PDO $pdo, string $theme_class) {
    // Logique pour récupérer et afficher la liste des contacts
    $pageTitle = "Mes Contacts - Joko";
    require_once VIEWS_PATH . '/includes/header.php';
    require_once VIEWS_PATH . '/contacts/contact_list.php';
    require_once VIEWS_PATH . '/includes/footer.php';
}