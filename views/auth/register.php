<?php
require_once APP_PATH . '/Core/helpers.php';
$error = getFlashMessage('error');
$success = getFlashMessage('success');
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mt-5">
            <div class="card-header">
                <h3 class="text-center">Inscription</h3>
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

                <form action="<?php echo BASE_URL; ?>/register" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de Passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmer le Mot de Passe</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">S'inscrire</button>
                    </div>
                </form>
                <div class="mt-3 text-center">
                    <p>Déjà un compte ? <a href="<?php echo BASE_URL; ?>/login">Se connecter ici</a></p>
                </div>
            </div>
        </div>
    </div>
</div>