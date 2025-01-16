<script src="../scripts/aime.js"></script>
<?php
require_once "../db_connection.inc.php";

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

function est_membre($email) {
    $ret = false;
    global $dbh;

    $query = "SELECT * FROM " . NOM_SCHEMA . "." . VUE_MEMBRE . " WHERE email = '" . $email . "';";
    $row = $dbh->query($query)->fetch();

    if (isset($row['pseudo'])) $ret = true;

    return $ret;
}
    

/*
 * prend en argument un array contenant toutes les informations d'un avis
 * affiche un div representant l'avis
 */
function afficher_avis($avis) {
    $date_visite = getdate(strtotime($avis['datevisite']));
?>
    <div class="avis">
        <div class="avis-header">
            <section class="avis-titre">
                <h2 class="note_avis"> <?php echo $avis['noteavis'] . "/5"; ?> </h2>
                <h1 class="titre_avis"><?php echo $avis['titre']; ?></h1>
            </section>            
            <section class="avis-infos">
                <h3 class="date_visite"> <?php echo format_date($date_visite); ?> </h3>
                <p class="contexte"> <?php echo $avis['contexte']; ?> </p>
            </section>
        </div>
        
        <p class="commentaire"><?php echo $avis['commentaire'] ?></p>
        <?php 
        // Vérifier si l'utilisateur a déjà liké ou disliké cet avis
        global $dbh;
        $email = $_SESSION["identifiant"];
        $idCompte = get_account_id($email);
        $idAvis = $avis["idavis"];
        $query = "SELECT aime FROM " . NOM_SCHEMA . "." . VUE_AIME_AVIS . " WHERE idcompte = :idcompte AND idavis = :idavis";
        $stmt = $dbh->prepare($query);
        $stmt->execute([':idcompte' => $idCompte, ':idavis' => $idAvis]);
        $vote = $stmt->fetch(PDO::FETCH_ASSOC);

        $isLiked = ($vote && $vote['aime'] === true);
        $isDisliked = ($vote && $vote['aime'] === false);
        if (est_membre($_SESSION["identifiant"])): ?>
            <input type="button" class="pouceHaut <?php echo $isLiked ? 'active' : ''; ?>" data-avis-id="<?php echo $idAvis; ?>" value="<?php echo $avis["nblike"]?>👍"></input>
            <input type="button" class="pouceBas <?php echo $isDisliked ? 'active' : ''; ?>" data-avis-id="<?php echo $idAvis; ?>" value="<?php echo $avis["nbdislike"]?>👎"></input>
        <?php else: ?>
            <input type="button" class="pouceHaut" value="<?php echo $avis["nblike"]?>👍" disabled></input>
            <input type="button" class="pouceBas" value="<?php echo $avis["nbdislike"]?>👎" disabled></input>
        <?php endif; ?>
        <hr style="border: none; border-top: 2px solid var(--navy-blue); margin: 20px; margin-left: 0px;">
    </div>
<?php
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
