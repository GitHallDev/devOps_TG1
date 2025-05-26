function showNotification(message, type = 'success') {
    // Supprimer toute notification existante
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Créer la nouvelle notification
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;

    // Ajouter la notification au document
    document.body.appendChild(notification);

    // Supprimer la notification après l'animation
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Vérifier les paramètres d'URL au chargement de la page
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        showNotification('Le stage a été mis à jour avec succès !', 'success');
    } else if (urlParams.has('error')) {
        showNotification('Une erreur est survenue lors de la mise à jour du stage.', 'error');
    }
}); 