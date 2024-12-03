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
    $date_visite = getdate(strtotime($avis['datevisite']));
?>
    <div class="avis">
        <h3 class="titre_avis"><?php echo $avis['titre']; ?></h3>
        <p class="note_avis"> <?php echo $avis['noteavis'] . "/5"; ?> </p> <!-- a modifier avec le bon affichage de la note -->
        <p class="date_visite"> <?php echo $avis['datevisite']; ?> </p>
        <p class="contexte"> <?php echo $avis['contexte']; ?> </p>
        <p class="commentaire"><?php echo $avis['commentaire'] ?></p>
    </div>
<?php
}

function get_jour($date_visite): string
{
    switch ($date_visite['wday']) {
        case 0:
            $ret = "Dimanche";
            break;
        case 1:
            $ret = "Lundi";
            break;
        case 2:
            $ret = "Mardi";
            break;
        case 3:
            $ret = "Mercredi";
            break;
        case 4:
            $ret = "Jeudi";
            break;
        case 5:
            $ret = "Vendredi";
            break;
        case 6:
            $ret = "Samedi";
            break;
    }
    return $ret;
}

function get_mois(): string {}
