# Joko - Application de Messagerie

Une application de messagerie simple développée en PHP avec une architecture fonctionnelle (sans classes).

## Fonctionnalités

- ✅ Authentification utilisateur (inscription/connexion)
- ✅ Gestion des contacts
- ✅ Conversations privées
- ✅ Messagerie en temps réel (AJAX)
- ✅ Thème sombre/clair
- ✅ Interface responsive avec Bootstrap

## Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- XAMPP, WAMP, ou serveur web local

## Installation étape par étape

### Étape 1 : Préparer l'environnement

1. **Installez XAMPP** (si pas déjà fait)
   - Téléchargez XAMPP depuis https://www.apachefriends.org/
   - Installez-le dans `C:\xampp\`

2. **Démarrez XAMPP**
   - Ouvrez XAMPP Control Panel
   - Cliquez sur "Start" pour Apache et MySQL
   - Vérifiez que les deux services sont en vert

### Étape 2 : Placer le projet

1. **Copiez le projet**
   - Placez le dossier `joko` dans `C:\xampp\htdocs\`
   - Votre projet sera accessible à : `http://localhost/joko/`

### Étape 3 : Créer la base de données

1. **Ouvrez phpMyAdmin**
   - Allez sur : `http://localhost/phpmyadmin`
   - Connectez-vous (utilisateur : `root`, mot de passe : vide par défaut)

2. **Créez la base de données**
   - Cliquez sur "Nouvelle base de données"
   - Nom : `joko_db`
   - Interclassement : `utf8mb4_unicode_ci`
   - Cliquez sur "Créer"

3. **Importez les données**
   - Sélectionnez la base `joko_db`
   - Cliquez sur "Importer"
   - Cliquez sur "Choisir un fichier"
   - Sélectionnez le fichier `database.sql` de votre projet
   - Cliquez sur "Exécuter"



## Structure du projet

```
joko/
├── app/
│   ├── Config/          # Configuration
│   ├── Controllers/     # Contrôleurs (logique métier)
│   ├── Core/           # Fonctions de base (router, database, helpers)
│   └── Models/         # Modèles (fonctions de base de données)
├── public/             # Point d'entrée et assets
│   ├── css/
│   ├── js/
│   └── index.php
├── views/              # Vues (templates)
│   ├── auth/
│   ├── contacts/
│   ├── dashboard/
│   ├── messages/
│   └── includes/
├── database.sql        # Script de création de la base de données
└── README.md
```

## Utilisation

### Fonctionnalités principales

1. **Inscription/Connexion** : Créez un compte ou connectez-vous
2. **Gestion des contacts** : Ajoutez des contacts pour pouvoir discuter
3. **Conversations** : Créez des conversations avec vos contacts
4. **Messagerie** : Envoyez et recevez des messages en temps réel
5. **Thème** : Basculez entre le thème clair et sombre

## Architecture

### Modèle fonctionnel

L'application utilise une architecture fonctionnelle sans classes :

- **Modèles** : Fonctions pures pour l'accès aux données
- **Contrôleurs** : Fonctions pour la logique métier
- **Vues** : Templates PHP avec les fonctions helpers
- **Router** : Système de routage simple basé sur les URLs

### Sécurité

- Mots de passe hachés avec `password_hash()`
- Protection contre les injections SQL (requêtes préparées)
- Échappement des données d'affichage
- Validation des entrées utilisateur
- Protection CSRF (tokens)

### AJAX

L'application utilise AJAX pour :
- Envoi de messages en temps réel
- Ajout/suppression de contacts
- Recherche d'utilisateurs
- Basculement de thème

## Dépannage

### Erreurs courantes

1. **Erreur de connexion à la base de données**
   - Vérifiez que MySQL est démarré dans XAMPP
   - Vérifiez les paramètres dans `app/Config/config.php`
   - Assurez-vous que la base `joko_db` existe

2. **Page blanche ou erreur 500**
   - Vérifiez que Apache est démarré dans XAMPP
   - Vérifiez les logs d'erreur dans `C:\xampp\apache\logs\error.log`

3. **Erreurs AJAX**
   - Ouvrez la console du navigateur (F12)
   - Vérifiez les erreurs JavaScript

### Activer les logs d'erreur

Pour voir les erreurs PHP, ajoutez dans `public/index.php` au début :

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Vérifier que tout fonctionne

1. **Test de la base de données** : `http://localhost/joko/`
2. **Test de l'inscription** : `http://localhost/joko/register`
3. **Test de la connexion** : `http://localhost/joko/login`

## Développement

### Ajouter une nouvelle fonctionnalité

1. Créez les fonctions dans le modèle approprié (`app/Models/`)
2. Ajoutez le contrôleur dans `app/Controllers/`
3. Créez la vue dans `views/`
4. Ajoutez la route dans `app/Core/router.php`

### Conventions de nommage

- **Fonctions** : camelCase (`getUserById`, `createUser`)
- **Fichiers** : snake_case (`user_model.php`, `contact_controller.php`)
- **Variables** : camelCase ou snake_case selon le contexte
- **Constantes** : UPPER_CASE (`BASE_URL`, `DB_HOST`)

## Support

Pour toute question ou problème :
1. Vérifiez que XAMPP est bien démarré
2. Vérifiez les logs d'erreur
3. Testez avec les comptes de démonstration
4. Créez une issue sur le repository si le problème persiste 
