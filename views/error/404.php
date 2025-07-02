<?php
// Inclure le header ici car c'est une page d'erreur
$pageTitle = "Page Non Trouvée - Joko";
require_once VIEWS_PATH . '/includes/header.php';
?>
<div class="text-center py-5">
    <h1>404</h1>
    <p class="lead">Oups ! La page que vous cherchez n'existe pas.</p>
    <a href="<?php echo BASE_URL; ?>" class="btn btn-primary mt-3">Retour à l'accueil</a>
</div>
<?php require_once VIEWS_PATH . '/includes/footer.php'; ?>