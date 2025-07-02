<?php

/**
 * Établit et retourne une connexion à la base de données en utilisant PDO.
 * @return PDO L'objet de connexion PDO.
 * @throws PDOException Si la connexion échoue.
 */
function getConnection() {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Rapporte les erreurs SQL comme des exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Les résultats sont retournés comme des tableaux associatifs
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Désactive l'émulation des requêtes préparées pour une meilleure sécurité et performance
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // En cas d'erreur de connexion, arrête l'exécution et affiche le message d'erreur
        // En production, il est préférable de loguer l'erreur et afficher un message générique
        die('Erreur de connexion à la base de données : ' . $e->getMessage());
    }
}