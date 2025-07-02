<?php
// C'est le contrôleur pour la page d'accueil/tableau de bord
function handleDashboard(PDO $pdo, string $theme_class) {
    // Logique pour le tableau de bord (ex: récupérer les dernières conversations)
    $pageTitle = "Tableau de Bord - Joko";
    require_once VIEWS_PATH . '/includes/header.php';
    require_once VIEWS_PATH . '/dashboard/index.php'; // Votre vue de tableau de bord
    require_once VIEWS_PATH . '/includes/footer.php';
}