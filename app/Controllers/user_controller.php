<?php

/**
 * Gère l'affichage et le traitement du formulaire de connexion.
 * @param PDO $pdo L'objet de connexion PDO.
 * @param string $theme_class La classe CSS du thème.
 */
function handleLogin(PDO $pdo, string $theme_class) {
    // Si le formulaire est soumis (méthode POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$email) {
            $_SESSION['flash_error'] = "Veuillez entrer une adresse email valide.";
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        if (empty($password)) {
            $_SESSION['flash_error'] = "Veuillez entrer votre mot de passe.";
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        // --- Logique d'authentification (à développer avec la BDD) ---
        // Pour l'instant, simulation d'une authentification réussie ou échouée
        if ($email === 'test@example.com' && $password === 'password123') {
            $_SESSION['user_id'] = 1; // Stocker l'ID utilisateur en session
            $_SESSION['user_email'] = $email; // Stocker l'email en session
            $_SESSION['flash_success'] = "Connexion réussie ! Bienvenue.";
            header('Location: ' . BASE_URL . '/'); // Rediriger vers le tableau de bord
            exit();
        } else {
            $_SESSION['flash_error'] = "Email ou mot de passe incorrect.";
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        // --- Fin Logique d'authentification ---

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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
        $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_SPECIAL_CHARS);

        // Validation simple côté serveur
        if (empty($username) || !$email || empty($password) || empty($confirm_password)) {
            $_SESSION['flash_error'] = "Tous les champs sont requis.";
            header('Location: ' . BASE_URL . '/register');
            exit();
        }
        if ($password !== $confirm_password) {
            $_SESSION['flash_error'] = "Les mots de passe ne correspondent pas.";
            header('Location: ' . BASE_URL . '/register');
            exit();
        }
        if (strlen($password) < 6) {
            $_SESSION['flash_error'] = "Le mot de passe doit contenir au moins 6 caractères.";
            header('Location: ' . BASE_URL . '/register');
            exit();
        }

        // --- Logique d'enregistrement (à développer avec la BDD) ---
        // Pour l'instant, simulation d'un enregistrement
        // Vous devrez insérer l'utilisateur dans la BDD et hacher le mot de passe ici
        $_SESSION['flash_success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        header('Location: ' . BASE_URL . '/login');
        exit();
        // --- Fin Logique d'enregistrement ---

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
    $_SESSION['flash_success'] = "Vous avez été déconnecté avec succès.";
    header('Location: ' . BASE_URL . '/login');
    exit();
}