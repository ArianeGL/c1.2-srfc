<?php
require_once "../db_connection.inc.php";
require_once "../includes/repondre_avis.php";

/*
 * prend en argument l'id d'une offre pour en afficher touts les avis
 * recupere touts les avis de l'offre et les passe un par un en argument de afficher_avis(&avis)
 */
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

/*
 * prend en argument un array contenant toutes les informations d'un avis
 * affiche un div representant l'avis
 */
function afficher_avis($avis)
{
    global $dbh;
    $date_visite = getdate(strtotime($avis['datevisite']));
    $appartient = offre_appartient($_SESSION['identifiant'], $avis['idoffre']);
?>
    <div class="avis">
        <div class="avis-header">
            <section class="avis-titre">
                <h2 class="note_avis"> <?php echo $avis['noteavis'] . "/5"; ?> </h2> <!-- a modifier avec le bon affichage de la note -->
                <h1 class="titre_avis"><?php echo $avis['titre']; ?></h1>
            </section>            
            <section class="avis-infos">
                <h3 class="date_visite"> <?php echo format_date($date_visite); ?> </h3>
                <p class="contexte"> <?php echo $avis['contexte']; ?> </p>
            </section>
        </div>
        

        <p class="commentaire"><?php echo $avis['commentaire'] ?></p>
        <hr style="border: none; border-top: 2px solid var(--navy-blue); margin: 20px; margin-left: 0px;">
        
        <?php if ($avis['reponse']): ?>
            <div class="reponse">
                <h3>Réponse :</h3>
                <p><?php echo htmlspecialchars($avis['reponse']); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($appartient && !reponse_existe($avis['idavis'])): ?>
            <?php afficher_form_reponse($avis['idavis']); ?>
        <?php endif; ?>
    </div>
<?php
}

// Permet de savoir si une réponse existe pour un avis donné
function reponse_existe($idAvis)
{
    global $dbh;

    try {
        $query = "SELECT COUNT(*) FROM " . NOM_SCHEMA . "._reponse WHERE idavis = :idavis";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':idavis', $idAvis);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        // Retourne true si une réponse existe, sinon false
        return $count > 0; 
    } catch (PDOException $e) {
        error_log("Erreur lors de la vérification de l'existence d'une réponse : " . $e->getMessage());
        return false; 
    }
}

/*
 * prend en argument un array resultant d'un getdate($timestamp = null)
 * retourne une string contenant le nom du jour associé a la date en francais
 * retourne false en cas d'erreur
 */
function get_jour($date): string | bool
{
    $ret = false;

    switch ($date['wday']) {
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


/*
 * prend en argument un array resultant d'un getdate($timestamp = null)
 * retourne une string contenant le nom du jour associé a la date en francais
 * retourne false en cas d'erreur
 */
function get_mois($date): string | bool
{
    $ret = false;

    switch ($date['mon']) {
        case 1:
            $ret = "Janvier";
            break;
        case 2:
            $ret = "Février";
            break;
        case 3:
            $ret = "Mars";
            break;
        case 4:
            $ret = "Avril";
            break;
        case 5:
            $ret = "Mai";
            break;
        case 6:
            $ret = "Juin";
            break;
        case 7:
            $ret = "Juillet";
            break;
        case 8:
            $ret = "Aout";
            break;
        case 9:
            $ret = "Septembre";
            break;
        case 10:
            $ret = "Octobre";
            break;
        case 11:
            $ret = "Novembre";
            break;
        case 12:
            $ret = "Decembre";
            break;
    }

    return $ret;
}


/*
 * prend en argument un array resultant d'un getdate($timestamp = null)
 * retourne une string contenant la date dans une phrase en francais
 */
function format_date($date): string
{
    return get_jour($date) . " " . $date['mday'] . " " . get_mois($date) . " " . $date['year'];
}


