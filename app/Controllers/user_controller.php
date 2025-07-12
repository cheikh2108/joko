<?php

/**
 * Gère l'affichage et le traitement du formulaire de connexion.
 * @param PDO $pdo L'objet de connexion PDO.
 * @param string $theme_class La classe CSS du thème.
 */
function handleLogin(PDO $pdo, string $theme_class) {
    // Inclure les modèles et helpers
    require_once APP_PATH . '/Models/user_model.php';
    require_once APP_PATH . '/Core/helpers.php';
    
    // Si le formulaire est soumis (méthode POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$email) {
            setFlashMessage('error', "Veuillez entrer une adresse email valide.");
            redirect(BASE_URL . '/login');
        }
        if (empty($password)) {
            setFlashMessage('error', "Veuillez entrer votre mot de passe.");
            redirect(BASE_URL . '/login');
        }

        // Authentification avec le modèle
        $user = authenticateUser($pdo, $email, $password);
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_username'] = $user['username'];
            setFlashMessage('success', "Connexion réussie ! Bienvenue " . e($user['username']) . ".");
            redirect(BASE_URL . '/');
        } else {
            setFlashMessage('error', "Email ou mot de passe incorrect.");
            redirect(BASE_URL . '/login');
        }

    } else {
        // Afficher le formulaire de connexion (méthode GET)
        $pageTitle = "Connexion - Joko";
        require_once VIEWS_PATH . '/includes/header.php';
        require_once VIEWS_PATH . '/auth/login.php';
        require_once VIEWS_PATH . '/includes/footer.php';
    }
}

/**
 * Gère l'affichage et le traitement du formulaire d'inscription.
 * @param PDO $pdo L'objet de connexion PDO.
 * @param string $theme_class La classe CSS du thème.
 */
function handleRegister(PDO $pdo, string $theme_class) {
    // Inclure les modèles et helpers
    require_once APP_PATH . '/Models/user_model.php';
    require_once APP_PATH . '/Core/helpers.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = sanitizeString($_POST['username'] ?? '');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validation côté serveur
        if (empty($username) || !$email || empty($password) || empty($confirm_password)) {
            setFlashMessage('error', "Tous les champs sont requis.");
            redirect(BASE_URL . '/register');
        }
        
        if ($password !== $confirm_password) {
            setFlashMessage('error', "Les mots de passe ne correspondent pas.");
            redirect(BASE_URL . '/register');
        }
        
        if (strlen($password) < 6) {
            setFlashMessage('error', "Le mot de passe doit contenir au moins 6 caractères.");
            redirect(BASE_URL . '/register');
        }
        
        if (emailExists($pdo, $email)) {
            setFlashMessage('error', "Cette adresse email est déjà utilisée.");
            redirect(BASE_URL . '/register');
        }
        
        if (usernameExists($pdo, $username)) {
            setFlashMessage('error', "Ce nom d'utilisateur est déjà pris.");
            redirect(BASE_URL . '/register');
        }

        // Créer l'utilisateur
        if (createUser($pdo, $username, $email, $password)) {
            setFlashMessage('success', "Inscription réussie ! Vous pouvez maintenant vous connecter.");
            redirect(BASE_URL . '/login');
        } else {
            setFlashMessage('error', "Erreur lors de l'inscription. Veuillez réessayer.");
            redirect(BASE_URL . '/register');
        }

    } else {
        // Afficher le formulaire d'inscription (méthode GET)
        $pageTitle = "Inscription - Joko";
        require_once VIEWS_PATH . '/includes/header.php';
        require_once VIEWS_PATH . '/auth/register.php';
        require_once VIEWS_PATH . '/includes/footer.php';
    }
}

/**
 * Gère la déconnexion de l'utilisateur.
 * @param PDO $pdo L'objet de connexion PDO.
 * @param string $theme_class La classe CSS du thème.
 */
function handleLogout(PDO $pdo, string $theme_class) {
    require_once APP_PATH . '/Core/helpers.php';
    
    // Détruire toutes les variables de session
    $_SESSION = array();

    // Si vous utilisez des cookies de session, détruisez aussi le cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finalement, détruire la session.
    session_destroy();

    // Rediriger vers la page de connexion
    setFlashMessage('success', "Vous avez été déconnecté avec succès.");
    redirect(BASE_URL . '/login');
}