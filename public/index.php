<?php

// Démarrer la session PHP au tout début
session_start();

// Définir le chemin absolu vers le dossier racine du projet (joko/)
// __DIR__ est 'C:\xampp\htdocs\joko\public'
// dirname(__DIR__) va donc retourner 'C:\xampp\htdocs\joko'
define('ROOT_PATH', dirname(__DIR__));

// Inclure le fichier de configuration (qui utilisera ROOT_PATH déjà défini)
require_once ROOT_PATH . '/app/Config/config.php';

// Inclure le fichier de connexion à la base de données
require_once APP_PATH . '/Core/database.php'; // Cette ligne devrait maintenant fonctionner

// Établir la connexion à la base de données
try {
    $pdo = getConnection();
} catch (PDOException $e) {
    die("Erreur critique: Impossible de se connecter à la base de données. " . $e->getMessage());
}

// --- Logique de gestion du thème (basée sur la session) ---
$theme_class = ''; // Par défaut, pas de classe de thème sombre
if (isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark') {
    $theme_class = 'dark-theme';
}
// --- Fin logique de gestion du thème ---

// Inclure le routeur (sera créé à la prochaine étape)
require_once APP_PATH . '/Core/router.php';

// Appel de la fonction de routage pour gérer la requête actuelle
handleRequest($pdo, $theme_class);