<?php
// C'est le contrôleur pour les contacts
function listContacts(PDO $pdo, string $theme_class) {
    require_once APP_PATH . '/Core/helpers.php';
    
    // Vérifier si l'utilisateur est connecté
    if (!isLoggedIn()) {
        redirect(BASE_URL . '/login');
    }
    
    // Logique pour récupérer et afficher la liste des contacts
    $pageTitle = "Mes Contacts - Joko";
    require_once VIEWS_PATH . '/includes/header.php';
    require_once VIEWS_PATH . '/contacts/contact_list.php';
    require_once VIEWS_PATH . '/includes/footer.php';
}

function addContact(PDO $pdo, string $theme_class) {
    require_once APP_PATH . '/Core/helpers.php';
    require_once APP_PATH . '/Models/contact_model.php';
    require_once APP_PATH . '/Models/user_model.php';
    
    if (!isLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Non autorisé'], 401);
    }
    
    if (!isAjaxRequest()) {
        jsonResponse(['success' => false, 'message' => 'Requête invalide'], 400);
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'] ?? '';
    
    if (!isValidEmail($email)) {
        jsonResponse(['success' => false, 'message' => 'Email invalide']);
    }
    
    // Trouver l'utilisateur par email
    $user = getUserByEmail($pdo, $email);
    if (!$user) {
        jsonResponse(['success' => false, 'message' => 'Utilisateur non trouvé']);
    }
    
    if ($user['id'] == getCurrentUserId()) {
        jsonResponse(['success' => false, 'message' => 'Vous ne pouvez pas vous ajouter vous-même']);
    }
    
    // Ajouter le contact
    if (addContact($pdo, getCurrentUserId(), $user['id'])) {
        jsonResponse(['success' => true, 'message' => 'Contact ajouté avec succès']);
    } else {
        jsonResponse(['success' => false, 'message' => 'Ce contact existe déjà']);
    }
}

function removeContact(PDO $pdo, string $theme_class) {
    require_once APP_PATH . '/Core/helpers.php';
    require_once APP_PATH . '/Models/contact_model.php';
    
    if (!isLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Non autorisé'], 401);
    }
    
    if (!isAjaxRequest()) {
        jsonResponse(['success' => false, 'message' => 'Requête invalide'], 400);
    }
    
    // Récupérer l'ID du contact depuis l'URL
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $parts = explode('/', trim($uri, '/'));
    $contactId = end($parts);
    
    if (!is_numeric($contactId)) {
        jsonResponse(['success' => false, 'message' => 'ID de contact invalide']);
    }
    
    if (removeContact($pdo, getCurrentUserId(), (int)$contactId)) {
        jsonResponse(['success' => true, 'message' => 'Contact supprimé avec succès']);
    } else {
        jsonResponse(['success' => false, 'message' => 'Erreur lors de la suppression']);
    }
}

function searchUsers(PDO $pdo, string $theme_class) {
    require_once APP_PATH . '/Core/helpers.php';
    require_once APP_PATH . '/Models/contact_model.php';
    
    if (!isLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Non autorisé'], 401);
    }
    
    if (!isAjaxRequest()) {
        jsonResponse(['success' => false, 'message' => 'Requête invalide'], 400);
    }
    
    $searchTerm = $_GET['q'] ?? '';
    if (strlen($searchTerm) < 2) {
        jsonResponse(['success' => false, 'message' => 'Terme de recherche trop court']);
    }
    
    $users = searchUsersForContact($pdo, getCurrentUserId(), $searchTerm);
    
    jsonResponse(['success' => true, 'users' => $users]);
}