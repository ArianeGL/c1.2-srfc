document.addEventListener('DOMContentLoaded', function() {
    // Écoute les clics sur le bouton "pouceHaut"
    document.querySelectorAll('.pouceHaut').forEach(function(button) {
        button.addEventListener('click', function() {
            const avisId = this.getAttribute('data-avis-id');
            const isActive = this.classList.contains('active'); // Vérifie si le bouton est déjà actif (déjà like)
            toggleAimeAvis(avisId, isActive, this);
        });
    });

    // Écoute les clics sur le bouton "pouceBas"
    document.querySelectorAll('.pouceBas').forEach(function(button) {
        button.addEventListener('click', function() {
            const avisId = this.getAttribute('data-avis-id');
            const isActive = this.classList.contains('active'); // Vérifie si le bouton est déjà actif (déjà dislike)
            toggleAimePasAvis(avisId, isActive, this);
        });
    });
});

function toggleAimeAvis(avisId, isActive, button) {
    fetch(`../includes/aimeAvis.php?avisId=${avisId}&action=${isActive ? 'remove' : 'add'}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const currentValue = parseInt(button.value);
                if (data.action === 'add') {
                    // Ajout du like
                    button.value = `${currentValue + 1}👍`;
                    button.classList.add('active');

                    // Supprime visuellement le dislike si l'utilisateur avait déjà dislike
                    const pouceBas = document.querySelector(`.pouceBas[data-avis-id="${avisId}"]`);
                    if (pouceBas && pouceBas.classList.contains('active')) {
                        const dislikeValue = parseInt(pouceBas.value);
                        pouceBas.value = `${dislikeValue - 1}👎`;
                        pouceBas.classList.remove('active');
                    }
                } else if (data.action === 'remove') {
                    // Retire le like
                    button.value = `${currentValue - 1}👍`;
                    button.classList.remove('active');
                }
            }
        })
        .catch(error => console.error('Erreur lors de la requête AJAX :', error));
}

function toggleAimePasAvis(avisId, isActive, button) {
    fetch(`../includes/aimePasAvis.php?avisId=${avisId}&action=${isActive ? 'remove' : 'add'}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const currentValue = parseInt(button.value);
                if (data.action === 'add') {
                    // Ajout du dislike
                    button.value = `${currentValue + 1}👎`;
                    button.classList.add('active');

                    // Supprime visuellement le like si l'utilisateur avait déjà like
                    const pouceHaut = document.querySelector(`.pouceHaut[data-avis-id="${avisId}"]`);
                    if (pouceHaut && pouceHaut.classList.contains('active')) {
                        const likeValue = parseInt(pouceHaut.value);
                        pouceHaut.value = `${likeValue - 1}👍`;
                        pouceHaut.classList.remove('active');
                    }
                } else if (data.action === 'remove') {
                    // Retire le dislike
                    button.value = `${currentValue - 1}👎`;
                    button.classList.remove('active');
                }
            }
        })
        .catch(error => console.error('Erreur lors de la requête AJAX :', error));
}