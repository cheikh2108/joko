# Guide d'installation Joko avec XAMPP

## Prérequis
- Windows 10/11
- XAMPP (téléchargeable sur https://www.apachefriends.org/)

## Installation étape par étape

### 1. Installer XAMPP
1. Téléchargez XAMPP depuis https://www.apachefriends.org/
2. Lancez l'installateur
3. Choisissez les composants par défaut (Apache, MySQL, PHP)
4. Installez dans `C:\xampp\`
5. Terminez l'installation

### 2. Démarrer XAMPP
1. Ouvrez XAMPP Control Panel
2. Cliquez sur "Start" à côté d'Apache
3. Cliquez sur "Start" à côté de MySQL
4. Vérifiez que les deux affichent un fond vert

### 3. Placer le projet
1. Copiez le dossier `joko` dans `C:\xampp\htdocs\`
2. Votre structure devrait être : `C:\xampp\htdocs\joko\`

### 4. Créer la base de données
1. Ouvrez votre navigateur
2. Allez sur : `http://localhost/phpmyadmin`
3. Dans la barre de gauche, cliquez sur "Nouvelle base de données"
4. Nom de la base : `joko_db`
5. Interclassement : `utf8mb4_unicode_ci`
6. Cliquez sur "Créer"

### 5. Importer les données
1. Sélectionnez la base `joko_db` dans la barre de gauche
2. Cliquez sur l'onglet "Importer"
3. Cliquez sur "Choisir un fichier"
4. Naviguez vers `C:\xampp\htdocs\joko\database.sql`
5. Sélectionnez le fichier
6. Cliquez sur "Exécuter"

### 6. Configurer l'application
1. Ouvrez le fichier : `C:\xampp\htdocs\joko\app\Config\config.php`
2. Vérifiez que les paramètres sont corrects :

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'joko_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', '/joko');
```

### 7. Tester l'application
1. Ouvrez votre navigateur
2. Allez sur : `http://localhost/joko/`
3. Vous devriez voir la page d'accueil

### 8. Tester avec les comptes existants
Connectez-vous avec un de ces comptes :
- **alice@example.com** / password123
- **bob@example.com** / password123
- **charlie@example.com** / password123
- **diana@example.com** / password123

## Dépannage

### Si l'application ne se charge pas :
1. Vérifiez que Apache et MySQL sont démarrés dans XAMPP
2. Vérifiez l'URL : `http://localhost/joko/` (pas `http://localhost/joko`)
3. Vérifiez que le dossier est bien dans `C:\xampp\htdocs\joko\`

### Si la base de données ne fonctionne pas :
1. Vérifiez que MySQL est démarré dans XAMPP
2. Vérifiez que la base `joko_db` existe dans phpMyAdmin
3. Vérifiez les paramètres dans `config.php`

### Pour voir les erreurs PHP :
Ajoutez ces lignes au début de `C:\xampp\htdocs\joko\public\index.php` :

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Si vous avez des erreurs 404 :
- Assurez-vous d'utiliser `http://localhost/joko/` et non `http://localhost/joko`
- Vérifiez que le dossier est bien nommé `joko` (en minuscules)

## URLs importantes
- Application : `http://localhost/joko/`
- Inscription : `http://localhost/joko/register`
- Connexion : `http://localhost/joko/login`
- phpMyAdmin : `http://localhost/phpmyadmin`

## Fonctionnalités à tester
1. **Inscription** : Créez un nouveau compte
2. **Connexion** : Connectez-vous avec un compte existant
3. **Contacts** : Ajoutez des contacts
4. **Messages** : Envoyez des messages
5. **Thème** : Basculez entre clair et sombre

## Support
Si vous avez des problèmes :
1. Vérifiez que XAMPP est bien démarré
2. Vérifiez les URLs exactes
3. Testez avec les comptes de démonstration
4. Vérifiez les logs d'erreur si activés 