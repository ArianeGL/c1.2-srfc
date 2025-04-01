function supprimer_avis(idavis) {
    value = confirm("Voulez vous vraiment supprimer cet avis ?");
    if (value === true) {
        php_delete_call(idavis);
    }
}

function php_delete_call(idavis) {
    fetch(`../includes/suppr_avis.inc.php?idavis=${encodeURIComponent(idavis)}`)
        .then(() => window.location.reload());
}
