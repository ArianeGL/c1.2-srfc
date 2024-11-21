function rechercheTitre(){
    const input = document.getElementById('barre_recherche').value.toUpperCase();
    const table = document.querySelector('.offre div');
    const info_principale = document.getElementsByClassName('upper_row');
    const info_titre = document.getElementsById('titre');

    for (let i = 1; i < info_secondaire.length; i++) {
        const recherche = info_titre[i].getElementsByTagName('td')[0];
        if (td) {
            const txtValue = td.textContent || td.innerText;
            tr[i].style.display = txtValue.toUpperCase().indexOf(input) > -1 ? '' : 'none';
        }
    }
}