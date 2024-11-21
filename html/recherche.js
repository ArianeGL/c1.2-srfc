function rechercheOffre() {
    console.log("Fonction rechercheOffre appelée !");
    const input = document.getElementById('rechercheOffre').value.toUpperCase().trim();
    const offres = document.querySelectorAll('.offre');

    offres.forEach(offre => {
        const nomOffre = (offre.querySelector('.upper_row .nom_offre')?.textContent || '').toUpperCase().trim();
        //offre.style.display = nomOffre.includes(input) ? '' : 'none';
        const distance = levenshtein(input, nomOffre, { useCollator: true }); 
        offre.style.display = distance <= 2 ? '' : 'none';
    });
}