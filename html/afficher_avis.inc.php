<?php
require_once "db_connection.inc.php";


function afficher_liste_avis($id_offre)
{
    global $dbh;

    try {
        $query = "SELECT * FROM " . NOM_SCHEMA . "." . NOM_TABLE_AVIS . " WHERE idoffre = '" . $id_offre . "';";
        $liste_avis = $dbh->query($query)->fetchAll();
        foreach ($liste_avis as $avis) afficher_avis($avis);
    } catch (PDOException $e) {
        die("Couldn't fetch comments : " . $e->getMessage());
    }
}

function afficher_avis($avis)
{
?>
    <div class="avis">
        <h3 class="titre_avis"><?php echo $avis['titre']; ?></h3>
        <p class="note_avis"> <?php echo $avis['noteavis'] . "/5"; ?> </p> <!-- a modifier avec le bon affichage de la note -->
        <p class="date_visite"> <?php echo $avis
        <p class="contexte"> <?php echo $avis['contexte']; ?> </p>
        <p class="commentaire"><?php echo $avis['commentaire'] ?></p>
    </div>
<?php
}
