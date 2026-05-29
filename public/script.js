// Confirmation avant toute suppression ou desactivation
// Les liens concernés utilisent déjà onclick="return confirm(...)" dans les vues
// Ce fichier est prévu pour des extensions ultérieures si nécessaire

// Masquer automatiquement les messages de succes apres 4 secondes
document.addEventListener('DOMContentLoaded', function () {
    var msg = document.querySelector('.message.succes');
    if (msg) {
        setTimeout(function () {
            msg.style.transition = 'opacity 0.5s';
            msg.style.opacity = '0';
            setTimeout(function () { msg.remove(); }, 500);
        }, 4000);
    }
});
