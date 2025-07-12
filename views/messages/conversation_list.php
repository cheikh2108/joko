<?php
require_once APP_PATH . '/Core/helpers.php';

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    redirect(BASE_URL . '/login');
}

// Récupérer les conversations
require_once APP_PATH . '/Models/conversation_model.php';
$conversations = getConversationsByUserId($pdo, getCurrentUserId());

$error = getFlashMessage('error');
$success = getFlashMessage('success');
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Mes Conversations</h3>
                <a href="<?php echo BASE_URL; ?>/contacts" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Nouvelle conversation
                </a>
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

                <?php if (empty($conversations)): ?>
                    <div class="text-center py-5">
                        <p class="text-muted">Vous n'avez pas encore de conversations.</p>
                        <p>Ajoutez des contacts pour commencer à discuter !</p>
                        <a href="<?php echo BASE_URL; ?>/contacts" class="btn btn-primary">
                            Voir mes contacts
                        </a>
                    </div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($conversations as $conversation): ?>
                            <?php 
                            // Déterminer l'autre utilisateur dans la conversation
                            $otherUser = ($conversation['user1_id'] == getCurrentUserId()) 
                                ? $conversation['user2_username'] 
                                : $conversation['user1_username'];
                            $otherUserId = ($conversation['user1_id'] == getCurrentUserId()) 
                                ? $conversation['user2_id'] 
                                : $conversation['user1_id'];
                            ?>
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?php echo e($otherUser); ?></h6>
                                        <small class="text-muted">
                                            <?php echo formatDate($conversation['last_message_date'] ?? $conversation['created_at']); ?>
                                        </small>
                                    </div>
                                    <p class="mb-1 text-muted">
                                        <?php echo $conversation['message_count']; ?> message(s)
                                    </p>
                                </div>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo BASE_URL; ?>/messages/<?php echo $conversation['id']; ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-chat"></i> Ouvrir
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteConversation(<?php echo $conversation['id']; ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function deleteConversation(conversationId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette conversation ? Tous les messages seront perdus.')) {
        fetch('<?php echo BASE_URL; ?>/conversations/delete/' + conversationId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression de la conversation');
        });
    }
}
</script>