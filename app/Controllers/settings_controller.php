<?php
// C'est le contrôleur pour les paramètres (comme le thème)
function toggleTheme(PDO $pdo, string $theme_class) {
    // Logique pour basculer le thème
    if (isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark') {
        $_SESSION['theme'] = 'light';
    } else {
        $_SESSION['theme'] = 'dark';
    }

    // Si la requête est AJAX (fetch), on renvoie une réponse simple
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['status' => 'success', 'theme' => $_SESSION['theme']]);
        exit();
    } else {
        // Sinon, rediriger l'utilisateur vers la page précédente ou l'accueil
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL));
        exit();
    }
}