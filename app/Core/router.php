<?php

/**
 * Gère la requête HTTP entrante et la redirige vers la fonction de contrôleur appropriée.
 *
 * @param PDO $pdo L'objet de connexion PDO à la base de données.
 * @param string $theme_class La classe CSS du thème actuel (dark-theme ou vide).
 */
function handleRequest(PDO $pdo, string $theme_class) {
    // Obtenez l'URI de la requête
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    // Supprimez le BASE_URL de l'URI pour obtenir le chemin "propre"
    $uri = str_replace(BASE_URL, '', $uri);
    // Supprimez le slash de début et de fin pour une comparaison facile
    $uri = trim($uri, '/');

    // Diviser l'URI en parties pour gérer les paramètres
    $parts = explode('/', $uri);
    $route = $parts[0] ?? '';
    $param1 = $parts[1] ?? '';
    $param2 = $parts[2] ?? '';

    // Définition des routes
    // Chaque 'case' représente une URL attendue (après BASE_URL)
    // et ce qu'il faut faire quand cette URL est demandée.

    switch ($route) {
        case '': // Route par défaut : La page d'accueil ou tableau de bord
            require_once APP_PATH . '/Controllers/dashboard_controller.php';
            handleDashboard($pdo, $theme_class); // Fonction du contrôleur du tableau de bord
            break;

        case 'login': // Route pour la page de connexion
            require_once APP_PATH . '/Controllers/user_controller.php';
            handleLogin($pdo, $theme_class); // Fonction de connexion du contrôleur utilisateur
            break;

        case 'register': // Route pour la page d'inscription
            require_once APP_PATH . '/Controllers/user_controller.php';
            handleRegister($pdo, $theme_class); // Fonction d'inscription du contrôleur utilisateur
            break;

        case 'logout': // Route pour la déconnexion
            require_once APP_PATH . '/Controllers/user_controller.php';
            handleLogout($pdo, $theme_class); // Fonction de déconnexion du contrôleur utilisateur
            break;

        case 'conversations': // Route pour la liste des conversations
            if ($param1 === 'delete' && is_numeric($param2)) {
                require_once APP_PATH . '/Controllers/conversation_controller.php';
                deleteConversation($pdo, $theme_class);
            } else {
                require_once APP_PATH . '/Controllers/conversation_controller.php';
                listConversations($pdo, $theme_class);
            }
            break;
            
        case 'messages': // Route pour afficher les messages d'une conversation spécifique
            if ($param1 === 'send') {
                require_once APP_PATH . '/Controllers/message_controller.php';
                sendMessage($pdo, $theme_class);
            } elseif (is_numeric($param1)) {
                require_once APP_PATH . '/Controllers/message_controller.php';
                listMessages($pdo, $theme_class);
            } else {
                require_once APP_PATH . '/Controllers/message_controller.php';
                listMessages($pdo, $theme_class);
            }
            break;

        case 'contacts': // Route pour la liste des contacts
            if ($param1 === 'add') {
                require_once APP_PATH . '/Controllers/contact_controller.php';
                addContact($pdo, $theme_class);
            } elseif ($param1 === 'remove' && is_numeric($param2)) {
                require_once APP_PATH . '/Controllers/contact_controller.php';
                removeContact($pdo, $theme_class);
            } elseif ($param1 === 'search') {
                require_once APP_PATH . '/Controllers/contact_controller.php';
                searchUsers($pdo, $theme_class);
            } else {
                require_once APP_PATH . '/Controllers/contact_controller.php';
                listContacts($pdo, $theme_class);
            }
            break;

        case 'toggle-theme': // Route pour basculer le thème (géré par JS)
            require_once APP_PATH . '/Controllers/settings_controller.php';
            toggleTheme($pdo, $theme_class); // Fonction pour changer le thème
            break;
            
        default: // Route par défaut pour toute URL non reconnue (Page 404)
            // Vous devrez créer une vue pour la page 404
            http_response_code(404); // Définit le code de statut HTTP à 404
            require_once VIEWS_PATH . '/error/404.php'; // Inclure la vue 404
            break;
    }
}