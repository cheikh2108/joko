<?php

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'joko_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Chemins de l'application
// ROOT_PATH est maintenant défini dans public/index.php avant l'inclusion de ce fichier
define('APP_PATH', ROOT_PATH . '/app');      // Utilise ROOT_PATH qui est correct
define('VIEWS_PATH', ROOT_PATH . '/views');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// URL de base
define('BASE_URL', '/joko'); 