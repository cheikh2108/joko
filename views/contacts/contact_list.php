<?php
require_once APP_PATH . '/Core/helpers.php';

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    redirect(BASE_URL . '/login');
}

// Récupérer les contacts
require_once APP_PATH . '/Models/contact_model.php';
$contacts = getContactsByUserId($pdo, getCurrentUserId());

$error = getFlashMessage('error');
$success = getFlashMessage('success');
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Mes Contacts</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContactModal">
                    <i class="bi bi-plus"></i> Ajouter un contact
                </button>
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

                <?php if (empty($contacts)): ?>
                    <div class="text-center py-5">
                        <p class="text-muted">Vous n'avez pas encore de contacts.</p>
                        <p>Ajoutez des contacts pour commencer à discuter !</p>
                    </div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($contacts as $contact): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?php echo e($contact['username']); ?></h6>
                                    <small class="text-muted"><?php echo e($contact['email']); ?></small>
                                </div>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo BASE_URL; ?>/conversations/start/<?php echo $contact['contact_user_id']; ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-chat"></i> Message
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="removeContact(<?php echo $contact['contact_user_id']; ?>)">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Rechercher des utilisateurs</h5>
            </div>
            <div class="card-body">
                <form id="searchForm">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="searchTerm" 
                               placeholder="Nom d'utilisateur ou email" required>
                    </div>
                    <button type="submit" class="btn btn-outline-secondary w-100">Rechercher</button>
                </form>
                <div id="searchResults" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter un contact -->
<div class="modal fade" id="addContactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addContactForm">
                    <div class="mb-3">
                        <label for="contactEmail" class="form-label">Email de l'utilisateur</label>
                        <input type="email" class="form-control" id="contactEmail" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="addContact()">Ajouter</button>
            </div>
        </div>
    </div>
</div>

<script>
function removeContact(contactId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce contact ?')) {
        fetch('<?php echo BASE_URL; ?>/contacts/remove/' + contactId, {
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
            alert('Erreur lors de la suppression du contact');
        });
    }
}

function addContact() {
    const email = document.getElementById('contactEmail').value;
    
    fetch('<?php echo BASE_URL; ?>/contacts/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ email: email })
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
        alert('Erreur lors de l\'ajout du contact');
    });
}

document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const searchTerm = document.getElementById('searchTerm').value;
    
    fetch('<?php echo BASE_URL; ?>/contacts/search?q=' + encodeURIComponent(searchTerm), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const resultsDiv = document.getElementById('searchResults');
        if (data.users && data.users.length > 0) {
            resultsDiv.innerHTML = data.users.map(user => 
                `<div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>${user.username}</strong><br>
                        <small class="text-muted">${user.email}</small>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" onclick="addContactByEmail('${user.email}')">
                        Ajouter
                    </button>
                </div>`
            ).join('');
        } else {
            resultsDiv.innerHTML = '<p class="text-muted">Aucun utilisateur trouvé</p>';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('searchResults').innerHTML = '<p class="text-danger">Erreur lors de la recherche</p>';
    });
});

function addContactByEmail(email) {
    document.getElementById('contactEmail').value = email;
    const modal = new bootstrap.Modal(document.getElementById('addContactModal'));
    modal.show();
}
</script>