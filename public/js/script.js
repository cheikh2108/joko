document.addEventListener('DOMContentLoaded', function() {
    const themeToggleButton = document.getElementById('theme-toggle-button');

    if (themeToggleButton) {
        themeToggleButton.addEventListener('click', function() {
            document.body.classList.toggle('dark-theme'); // Bascule la classe immédiatement

            // Envoyer une requête POST au serveur pour enregistrer la préférence
            // Utilisez la fonction fetch() pour une requête AJAX moderne
            fetch('<?php echo BASE_URL; ?>/toggle-theme', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest' // Indique que c'est une requête AJAX
                }
            })
            .then(response => response.json()) // Assurez-vous que le serveur renvoie du JSON
            .then(data => {
                console.log('Thème mis à jour sur le serveur :', data.theme);
            })
            .catch(error => console.error('Erreur lors de la mise à jour du thème :', error));
        });
    }
});