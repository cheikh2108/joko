<?php
require_once APP_PATH . '/Core/helpers.php';
$error = getFlashMessage('error');
$success = getFlashMessage('success');
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mt-5">
            <div class="card-header">
                <h3 class="text-center">Connexion</h3>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo e($error); ?>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo e($success); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>/login" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de Passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </div>
                </form>
                <div class="mt-3 text-center">
                    <p>Pas encore de compte ? <a href="<?php echo BASE_URL; ?>/register">S'inscrire ici</a></p>
                </div>
            </div>
        </div>
    </div>
</div>