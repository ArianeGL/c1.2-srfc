function rechercheOffre() {
    console.log("Fonction rechercherOffres appelée !");
    const input = document.getElementById('rechercheOffre').value.toUpperCase().trim();
    const offres = document.querySelectorAll('.offre');

    if (input === '') {
        offres.forEach(offre => {
            offre.style.display = 'flex'; // Affiche toutes les offres si la barre de recherche est vide
        });
    } else {
        const searchTokens = input.split(' '); // Divise la valeur de la barre de recherche en tokens (mots)
        offres.forEach(offre => {
            const nomOffre = (offre.querySelector('.upper_row .nom_offre')?.textContent || '').toUpperCase().trim();
            const offerTokens = nomOffre.split(' '); // Divise le nom de l'offre en tokens (mots)
            let allMatch = true; // Flag pour suivre si tous les tokens de recherche trouvent une correspondance

            searchTokens.forEach(searchToken => {
                let tokenMatch = false; // Flag pour suivre si le token de recherche trouve une correspondance
                offerTokens.forEach(offerToken => {
                    const distance = levenshtein(searchToken, offerToken); // Calcule la distance de Levenshtein
                    const threshold = Math.ceil(searchToken.length * 0.2); // Calcule le seuil (20% de la longueur du token de recherche)
                    if (distance <= threshold) {
                        tokenMatch = true; // Marque une correspondance pour ce token de recherche
                    }
                });
                if (!tokenMatch) {
                    allMatch = false; // Si un token de recherche ne trouve aucune correspondance, marque que tous ne correspondent pas
                }
            });

            offre.style.display = allMatch ? 'flex' : 'none'; // Affiche l'offre seulement si tous les tokens de recherche ont trouvé une correspondance
        });
    }
}

function levenshtein(a, b) {
    if (a.length == 0) return b.length;
    if (b.length == 0) return a.length
    var matrix = [];
    for (var i = 0; i <= b.length; i++) {
        matrix[i] = [i];
    }
    for (var j = 0; j <= a.length; j++) {
        matrix[0][j] = j;
    }
    for (var i = 1; i <= b.length; i++) {
        for (var j = 1; j <= a.length; j++) {
            if (b.charAt(i - 1) == a.charAt(j - 1)) {
                matrix[i][j] = matrix[i - 1][j - 1];
            } else {
                matrix[i][j] = Math.min(matrix[i - 1][j - 1] + 1, 
                                        Math.min(matrix[i][j - 1] + 1,
                                                 matrix[i - 1][j] + 1)); 
            }
        }
    }
    return matrix[b.length][a.length];
}