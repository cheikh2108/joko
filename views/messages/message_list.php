<?php
require_once APP_PATH . '/Core/helpers.php';

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    redirect(BASE_URL . '/login');
}

// Récupérer l'ID de la conversation depuis l'URL
$conversationId = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$conversationId) {
    setFlashMessage('error', 'Conversation non trouvée');
    redirect(BASE_URL . '/conversations');
}

// Récupérer les informations de la conversation et les messages
require_once APP_PATH . '/Models/conversation_model.php';
require_once APP_PATH . '/Models/message_model.php';

$conversation = getConversationById($pdo, $conversationId, getCurrentUserId());
if (!$conversation) {
    setFlashMessage('error', 'Conversation non trouvée ou accès refusé');
    redirect(BASE_URL . '/conversations');
}

$messages = getMessagesByConversationId($pdo, $conversationId, getCurrentUserId());

// Marquer les messages comme lus
markMessagesAsRead($pdo, $conversationId, getCurrentUserId());

// Déterminer l'autre utilisateur
$otherUser = ($conversation['user1_id'] == getCurrentUserId()) 
    ? $conversation['user2_username'] 
    : $conversation['user1_username'];

$error = getFlashMessage('error');
$success = getFlashMessage('success');
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3>Conversation avec <?php echo e($otherUser); ?></h3>
                    <small class="text-muted">Conversation créée le <?php echo formatDate($conversation['created_at']); ?></small>
                </div>
                <a href="<?php echo BASE_URL; ?>/conversations" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
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

                <!-- Zone des messages -->
                <div id="messagesContainer" class="mb-4" style="height: 400px; overflow-y: auto;">
                    <?php if (empty($messages)): ?>
                        <div class="text-center py-5">
                            <p class="text-muted">Aucun message dans cette conversation.</p>
                            <p>Soyez le premier à envoyer un message !</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($messages as $message): ?>
                            <div class="message-item mb-3 <?php echo ($message['sender_id'] == getCurrentUserId()) ? 'text-end' : 'text-start'; ?>">
                                <div class="d-inline-block">
                                    <div class="message-bubble p-3 rounded <?php echo ($message['sender_id'] == getCurrentUserId()) ? 'bg-primary text-white' : 'bg-light'; ?>" 
                                         style="max-width: 70%;">
                                        <div class="message-content">
                                            <?php echo nl2br(e($message['content'])); ?>
                                        </div>
                                        <div class="message-meta mt-2">
                                            <small class="<?php echo ($message['sender_id'] == getCurrentUserId()) ? 'text-white-50' : 'text-muted'; ?>">
                                                <?php echo e($message['username']); ?> - 
                                                <?php echo formatDate($message['created_at']); ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Formulaire d'envoi de message -->
                <form id="messageForm" class="mt-3">
                    <div class="input-group">
                        <textarea class="form-control" id="messageContent" rows="2" 
                                  placeholder="Tapez votre message..." required></textarea>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-send"></i> Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.message-bubble {
    word-wrap: break-word;
}

#messagesContainer {
    scrollbar-width: thin;
    scrollbar-color: #888 #f1f1f1;
}

#messagesContainer::-webkit-scrollbar {
    width: 6px;
}

#messagesContainer::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#messagesContainer::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

#messagesContainer::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>

<script>
// Faire défiler vers le bas pour voir les derniers messages
function scrollToBottom() {
    const container = document.getElementById('messagesContainer');
    container.scrollTop = container.scrollHeight;
}

// Charger la page en faisant défiler vers le bas
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
});

// Gérer l'envoi de message
document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const content = document.getElementById('messageContent').value.trim();
    if (!content) return;
    
    // Envoyer le message via AJAX
    fetch('<?php echo BASE_URL; ?>/messages/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            conversation_id: <?php echo $conversationId; ?>,
            content: content
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Ajouter le message à l'interface
            addMessageToInterface(data.message);
            // Vider le champ de saisie
            document.getElementById('messageContent').value = '';
            // Faire défiler vers le bas
            scrollToBottom();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'envoi du message');
    });
});

function addMessageToInterface(message) {
    const container = document.getElementById('messagesContainer');
    const isOwnMessage = message.sender_id == <?php echo getCurrentUserId(); ?>;
    
    const messageHtml = `
        <div class="message-item mb-3 ${isOwnMessage ? 'text-end' : 'text-start'}">
            <div class="d-inline-block">
                <div class="message-bubble p-3 rounded ${isOwnMessage ? 'bg-primary text-white' : 'bg-light'}" 
                     style="max-width: 70%;">
                    <div class="message-content">
                        ${message.content.replace(/\n/g, '<br>')}
                    </div>
                    <div class="message-meta mt-2">
                        <small class="${isOwnMessage ? 'text-white-50' : 'text-muted'}">
                            ${message.username} - ${formatDate(message.created_at)}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', messageHtml);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Actualiser les messages toutes les 5 secondes
setInterval(function() {
    fetch('<?php echo BASE_URL; ?>/messages/<?php echo $conversationId; ?>?ajax=1')
    .then(response => response.json())
    .then(data => {
        if (data.messages) {
            // Mettre à jour les messages si nécessaire
            // Cette logique peut être améliorée pour ne charger que les nouveaux messages
        }
    })
    .catch(error => console.error('Erreur lors de l\'actualisation:', error));
}, 5000);
</script>