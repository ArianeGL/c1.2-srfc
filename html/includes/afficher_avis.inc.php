<script src="../scripts/aime.js"></script>
<?php

// SVG DES BOUTONS
const ENABLED_LIKE = '<path d="M12.1612 19.9999L20.2902 2C21.9072 2 23.4579 2.63214 24.6013 3.75735C25.7446 4.88256 26.387 6.40867 26.387 7.99996V15.9999H37.8895C38.4786 15.9933 39.0622 16.1129 39.5998 16.3503C40.1373 16.5878 40.616 16.9374 41.0026 17.3749C41.3892 17.8125 41.6746 18.3275 41.8388 18.8844C42.0031 19.4413 42.0424 20.0266 41.954 20.5999L39.1495 38.5998C39.0025 39.5536 38.5102 40.423 37.7633 41.0478C37.0164 41.6726 36.0652 42.0107 35.085 41.9997H12.1612M12.1612 19.9999V41.9997M12.1612 19.9999H6.06449C4.98652 19.9999 3.9527 20.4213 3.19046 21.1715C2.42822 21.9216 2 22.939 2 23.9999V37.9998C2 39.0606 2.42822 40.078 3.19046 40.8282C3.9527 41.5783 4.98652 41.9997 6.06449 41.9997H12.1612" stroke="#254766" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>';
const ENABLED_DISLIKE = '<path d="M12.1612 24.0001L20.2902 42C21.9072 42 23.4579 41.3679 24.6013 40.2427C25.7446 39.1174 26.387 37.5913 26.387 36V28.0001H37.8895C38.4786 28.0067 39.0622 27.8871 39.5998 27.6497C40.1373 27.4122 40.616 27.0626 41.0026 26.6251C41.3892 26.1875 41.6746 25.6725 41.8388 25.1156C42.0031 24.5587 42.0424 23.9734 41.954 23.4001L39.1495 5.40024C39.0025 4.44643 38.5102 3.57703 37.7633 2.95224C37.0164 2.32744 36.0652 1.98935 35.085 2.00026H12.1612M12.1612 24.0001V2.00026M12.1612 24.0001H6.06449C4.98652 24.0001 3.9527 23.5787 3.19046 22.8285C2.42822 22.0784 2 21.061 2 20.0001V6.00023C2 4.93937 2.42822 3.92196 3.19046 3.17182C3.9527 2.42168 4.98652 2.00026 6.06449 2.00026H12.1612" stroke="#254766" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>';

const DISABLED_LIKE = '<path d="M14 22L22 4C23.5913 4 25.1174 4.63214 26.2426 5.75736C27.3679 6.88258 28 8.4087 28 10V18H39.32C39.8998 17.9934 40.4741 18.113 41.0031 18.3504C41.5322 18.5879 42.0032 18.9375 42.3837 19.375C42.7642 19.8126 43.045 20.3276 43.2067 20.8845C43.3683 21.4414 43.407 22.0267 43.32 22.6L40.56 40.6C40.4154 41.5538 39.9309 42.4232 39.1958 43.048C38.4608 43.6728 37.5247 44.0109 36.56 44H14M14 22V44M14 22H8C6.93913 22 5.92172 22.4214 5.17157 23.1716C4.42143 23.9217 4 24.9391 4 26V40C4 41.0609 4.42143 42.0783 5.17157 42.8284C5.92172 43.5786 6.93913 44 8 44H14" stroke="#254766" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 22L22 4C23.5913 4 25.1174 4.63214 26.2426 5.75736C27.3679 6.88258 28 8.4087 28 10V18H39.32C39.8998 17.9934 40.4741 18.113 41.0031 18.3504C41.5322 18.5879 42.0032 18.9375 42.3837 19.375C42.7642 19.8126 43.045 20.3276 43.2067 20.8845C43.3683 21.4414 43.407 22.0267 43.32 22.6L40.56 40.6C40.4154 41.5538 39.9309 42.4232 39.1958 43.048C38.4608 43.6728 37.5247 44.0109 36.56 44H14M14 22V44M14 22H8C6.93913 22 5.92172 22.4214 5.17157 23.1716C4.42143 23.9217 4 24.9391 4 26V40C4 41.0609 4.42143 42.0783 5.17157 42.8284C5.92172 43.5786 6.93913 44 8 44H14" stroke="#254766" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 21.5H15L23 5L27.5 9L28 19L42.5 20L39.5 43H5.5L5 21.5Z" fill="#254766"/>';
const DISABLED_DISLIKE = '<path d="M14 26L22 44C23.5913 44 25.1174 43.3679 26.2426 42.2426C27.3679 41.1174 28 39.5913 28 38V30H39.32C39.8998 30.0066 40.4741 29.887 41.0031 29.6496C41.5322 29.4121 42.0032 29.0625 42.3837 28.625C42.7642 28.1874 43.045 27.6724 43.2067 27.1155C43.3683 26.5586 43.407 25.9733 43.32 25.4L40.56 7.4C40.4154 6.44619 39.9309 5.57679 39.1958 4.95199C38.4608 4.32719 37.5247 3.98909 36.56 4H14M14 26V4M14 26H8C6.93913 26 5.92172 25.5786 5.17157 24.8284C4.42143 24.0783 4 23.0609 4 22V8C4 6.93913 4.42143 5.92172 5.17157 5.17157C5.92172 4.42143 6.93913 4 8 4H14" stroke="#254766" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 26L22 44C23.5913 44 25.1174 43.3679 26.2426 42.2426C27.3679 41.1174 28 39.5913 28 38V30H39.32C39.8998 30.0066 40.4741 29.887 41.0031 29.6496C41.5322 29.4121 42.0032 29.0625 42.3837 28.625C42.7642 28.1874 43.045 27.6724 43.2067 27.1155C43.3683 26.5586 43.407 25.9733 43.32 25.4L40.56 7.4C40.4154 6.44619 39.9309 5.57679 39.1958 4.95199C38.4608 4.32719 37.5247 3.98909 36.56 4H14M14 26V4M14 26H8C6.93913 26 5.92172 25.5786 5.17157 24.8284C4.42143 24.0783 4 23.0609 4 22V8C4 6.93913 4.42143 5.92172 5.17157 5.17157C5.92172 4.42143 6.93913 4 8 4H14" stroke="#254766" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 26.5H15L23 43L27.5 39L28 29L42.5 28L39.5 5H5.5L5 26.5Z" fill="#254766"/>';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "../vendor/autoload.php";

session_start();
require_once "../db_connection.inc.php";
require_once "../includes/modifier_avis.inc.php";

/*
 * prend en argument l'id d'une offre pour en afficher touts les avis
 * recupere touts les avis de l'offre et les passe un par un en argument de afficher_avis(&avis)
 */
function afficher_liste_avis($id_offre)
{
    global $dbh;

    try {
?>
        <script src="../scripts/modifier_avis.js"></script>
        <?php
        $query = "SELECT * FROM " . NOM_SCHEMA . "." . NOM_TABLE_AVIS . " WHERE idoffre = '" . $id_offre . "';";
        $liste_avis = $dbh->query($query)->fetchAll();
        foreach ($liste_avis as $avis) {
            afficher_avis($avis);
        ?>
            <hr style="border: none; border-top: 2px solid var(--navy-blue); margin: 20px; margin-left: 0px;">
    <?php
        }
    } catch (PDOException $e) {
        die("Couldn't fetch comments : " . $e->getMessage());
    }
}

function est_membre($email)
{
    $ret = false;
    global $dbh;

    $query = "SELECT * FROM " . NOM_SCHEMA . "." . VUE_MEMBRE . " WHERE email = '" . $email . "';";
    $row = $dbh->query($query)->fetch();

    if (isset($row['pseudo'])) $ret = true;

    return $ret;
}


/*
 * prend en argument un array contenant toutes les informations d'un avis
 * affiche un div DISABLED_DISLIKE esentant l'avis
 */
function afficher_avis($avis)
{
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

        $isLiked = $vote && $vote['aime'];
        $isDisliked = $vote && !$vote['aime'];
        ?>
        <hr style="border: none; border-top: 2px solid var(--navy-blue); margin: 20px; margin-left: 0px;">
        <?php
        if (isset($_SESSION['identifiant']) && !$avis['signale'] && !avis_appartient($avis['idavis'])) {
        ?>
            <form method="post">
                <input type="text" style="display: none" name="idavis" value="<?php echo $avis['idavis'] ?>">
                <input type="submit" class="smallButton" value="Signaler">
            </form>
        <?php
        }
        if (isset($_SESSION['identifiant']) && avis_appartient($avis['idavis'])) {
        ?>
            <div>
                <button type="button" onclick="modifier_avis(this, <?php echo "'" . $avis['idavis'] . "', '" . $avis["idoffre"]; ?>')" class="smallButton modifier">Modifier</button>
            </div>
            <section style="display: flex; align-items: center; justify-content: space-between; max-width: 200px;"
                <?php
            }
            if (est_membre($_SESSION["identifiant"])) {
                ?>
                <section id="like" style="display: flex; align-items: center; margin: 10px;">
                <button data-avis-id="<?php echo $idAvis; ?>" <?php if (!est_membre($_SESSION['identifiant']) || $isLiked === true) echo "disabled"; ?> style="
        display: flex;
        align-items: center;
                background: none;
                color: inherit;
                border: none;
                padding: 0;
                font: inherit;
                cursor: pointer;
                outline: inherit;
                " id="pouceHaut">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <?php echo $isLiked ? DISABLED_LIKE : ENABLED_LIKE; ?>
                    </svg>
                    <h2 id="nb_like" style="margin-left: 10px; color: var(--navy-blue);"><?php echo $avis['nblike'] ?> </h2>
                </button>
            </section>
            <?php
            ?>
            <section id="like" style="display: flex; align-items: center; margin: 10px;">
                <button data-avis-id="<?php echo $idAvis; ?>" <?php if (!est_membre($_SESSION['identifiant']) || $isDisliked === true) echo "disabled"; ?> style="
        display: flex;
        align-items: center;
                background: none;
                color: inherit;
                border: none;
                padding: 0;
                font: inherit;
                cursor: pointer;
                outline: inherit;
                " id="pouceBas">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <?php echo $isDisliked ? DISABLED_DISLIKE : ENABLED_DISLIKE; ?>
                    </svg>
                    <h2 id="nb_dislike" style="margin-left: 10px; color: var(--navy-blue);"><?php echo $avis['nbdislike'] ?> </h2>
                </button>
            </section>
        <?php
            } else {
        ?>
            <?php
            ?>
            <input type="button" id="pouceBas" value="<?php echo $avis["nbdislike"] ?>👎"></input>
        <?php
            }
        ?>
        </section>
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

/*
 * prend en parametre l'id d'un avis
 * retourne true si l'avis appartient au compte connecté
 * retourne false sinon
 */
function avis_appartient($id_avis): bool
{
    global $dbh;
    $ret = false;

    try {
        $query = "SELECT idavis FROM " . NOM_SCHEMA . "." . VUE_AVIS . " WHERE idavis = :id_avis AND idcompte = :id_compte";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":id_avis", $id_avis);
        $id_compte = get_account_id();
        $stmt->bindParam(":id_compte", $id_compte);
        $stmt->execute();
        $ret = $stmt->rowCount() == 1;
    } catch (PDOException $e) {
        die("Couldn't check review belonging : " . $e->getMessage());
    }

    return $ret;
}
