<script src="../scripts/aime.js"></script>
<?php

// SVG DES BOUTONS
const ENABLED_LIKE = '<path d="M12.1612 19.9999L20.2902 2C21.9072 2 23.4579 2.63214 24.6013 3.75735C25.7446 4.88256 26.387 6.40867 26.387 7.99996V15.9999H37.8895C38.4786 15.9933 39.0622 16.1129 39.5998 16.3503C40.1373 16.5878 40.616 16.9374 41.0026 17.3749C41.3892 17.8125 41.6746 18.3275 41.8388 18.8844C42.0031 19.4413 42.0424 20.0266 41.954 20.5999L39.1495 38.5998C39.0025 39.5536 38.5102 40.423 37.7633 41.0478C37.0164 41.6726 36.0652 42.0107 35.085 41.9997H12.1612M12.1612 19.9999V41.9997M12.1612 19.9999H6.06449C4.98652 19.9999 3.9527 20.4213 3.19046 21.1715C2.42822 21.9216 2 22.939 2 23.9999V37.9998C2 39.0606 2.42822 40.078 3.19046 40.8282C3.9527 41.5783 4.98652 41.9997 6.06449 41.9997H12.1612" stroke="#254766" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>';
const ENABLED_DISLIKE = '<path d="M12.1612 24.0001L20.2902 42C21.9072 42 23.4579 41.3679 24.6013 40.2427C25.7446 39.1174 26.387 37.5913 26.387 36V28.0001H37.8895C38.4786 28.0067 39.0622 27.8871 39.5998 27.6497C40.1373 27.4122 40.616 27.0626 41.0026 26.6251C41.3892 26.1875 41.6746 25.6725 41.8388 25.1156C42.0031 24.5587 42.0424 23.9734 41.954 23.4001L39.1495 5.40024C39.0025 4.44643 38.5102 3.57703 37.7633 2.95224C37.0164 2.32744 36.0652 1.98935 35.085 2.00026H12.1612M12.1612 24.0001V2.00026M12.1612 24.0001H6.06449C4.98652 24.0001 3.9527 23.5787 3.19046 22.8285C2.42822 22.0784 2 21.061 2 20.0001V6.00023C2 4.93937 2.42822 3.92196 3.19046 3.17182C3.9527 2.42168 4.98652 2.00026 6.06449 2.00026H12.1612" stroke="#254766" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>';

const DISABLED_LIKE = '<path d="M14 22L22 4C23.5913 4 25.1174 4.63214 26.2426 5.75736C27.3679 6.88258 28 8.4087 28 10V18H39.32C39.8998 17.9934 40.4741 18.113 41.0031 18.3504C41.5322 18.5879 42.0032 18.9375 42.3837 19.375C42.7642 19.8126 43.045 20.3276 43.2067 20.8845C43.3683 21.4414 43.407 22.0267 43.32 22.6L40.56 40.6C40.4154 41.5538 39.9309 42.4232 39.1958 43.048C38.4608 43.6728 37.5247 44.0109 36.56 44H14M14 22V44M14 22H8C6.93913 22 5.92172 22.4214 5.17157 23.1716C4.42143 23.9217 4 24.9391 4 26V40C4 41.0609 4.42143 42.0783 5.17157 42.8284C5.92172 43.5786 6.93913 44 8 44H14" stroke="#254766" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 22L22 4C23.5913 4 25.1174 4.63214 26.2426 5.75736C27.3679 6.88258 28 8.4087 28 10V18H39.32C39.8998 17.9934 40.4741 18.113 41.0031 18.3504C41.5322 18.5879 42.0032 18.9375 42.3837 19.375C42.7642 19.8126 43.045 20.3276 43.2067 20.8845C43.3683 21.4414 43.407 22.0267 43.32 22.6L40.56 40.6C40.4154 41.5538 39.9309 42.4232 39.1958 43.048C38.4608 43.6728 37.5247 44.0109 36.56 44H14M14 22V44M14 22H8C6.93913 22 5.92172 22.4214 5.17157 23.1716C4.42143 23.9217 4 24.9391 4 26V40C4 41.0609 4.42143 42.0783 5.17157 42.8284C5.92172 43.5786 6.93913 44 8 44H14" stroke="#254766" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 21.5H15L23 5L27.5 9L28 19L42.5 20L39.5 43H5.5L5 21.5Z" fill="#254766"/>';
const DISABLED_DISLIKE = '<path d="M14 26L22 44C23.5913 44 25.1174 43.3679 26.2426 42.2426C27.3679 41.1174 28 39.5913 28 38V30H39.32C39.8998 30.0066 40.4741 29.887 41.0031 29.6496C41.5322 29.4121 42.0032 29.0625 42.3837 28.625C42.7642 28.1874 43.045 27.6724 43.2067 27.1155C43.3683 26.5586 43.407 25.9733 43.32 25.4L40.56 7.4C40.4154 6.44619 39.9309 5.57679 39.1958 4.95199C38.4608 4.32719 37.5247 3.98909 36.56 4H14M14 26V4M14 26H8C6.93913 26 5.92172 25.5786 5.17157 24.8284C4.42143 24.0783 4 23.0609 4 22V8C4 6.93913 4.42143 5.92172 5.17157 5.17157C5.92172 4.42143 6.93913 4 8 4H14" stroke="#254766" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 26L22 44C23.5913 44 25.1174 43.3679 26.2426 42.2426C27.3679 41.1174 28 39.5913 28 38V30H39.32C39.8998 30.0066 40.4741 29.887 41.0031 29.6496C41.5322 29.4121 42.0032 29.0625 42.3837 28.625C42.7642 28.1874 43.045 27.6724 43.2067 27.1155C43.3683 26.5586 43.407 25.9733 43.32 25.4L40.56 7.4C40.4154 6.44619 39.9309 5.57679 39.1958 4.95199C38.4608 4.32719 37.5247 3.98909 36.56 4H14M14 26V4M14 26H8C6.93913 26 5.92172 25.5786 5.17157 24.8284C4.42143 24.0783 4 23.0609 4 22V8C4 6.93913 4.42143 5.92172 5.17157 5.17157C5.92172 4.42143 6.93913 4 8 4H14" stroke="#254766" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 26.5H15L23 43L27.5 39L28 29L42.5 28L39.5 5H5.5L5 26.5Z" fill="#254766"/>';

const EDIT = '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4 4L33 11L36 26L26 36L11 33L4 4ZM4 4L19.172 19.172M24 38L38 24L44 30L30 44L24 38ZM26 22C26 24.2091 24.2091 26 22 26C19.7909 26 18 24.2091 18 22C18 19.7909 19.7909 18 22 18C24.2091 18 26 19.7909 26 22Z" stroke="#254766" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
const REPORT = '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8 30C8 30 10 28 16 28C22 28 26 32 32 32C38 32 40 30 40 30V6C40 6 38 8 32 8C26 8 22 4 16 4C10 4 8 6 8 6V30ZM8 30V44" stroke="#254766" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M8 30C8 30 10 28 16 28C22 28 26 32 32 32C38 32 40 30 40 30V6C40 6 38 8 32 8C26 8 22 4 16 4C10 4 8 6 8 6V30ZM8 30V44" stroke="#254766" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';

const DISABLED_BLACKLIST = '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M7.5 30.5L12 37.5L22.5 42L10.5 40.5L4 31.5L3 22L5.5 13.5L11.5 7L19 3.5L28 3L37.5 7.5L43.5 15.5L45 24L43.5 32L39 39L35 42L31 44L24 45L17.5 44L11.5 40.68L27.5 41.5L35.5 37.5L41 30L41.5 22.5L39 14.5L33 9L24.5 6L15.5 8.5L10 13L6.5 22L7.5 30.5Z" fill="#254766"/>
<rect x="12" y="21" width="24" height="6" fill="#254766"/>
<path d="M35.9999 26.8999H36.4999V26.3999V21.5999V21.0999H35.9999H11.9999H11.4999V21.5999V26.3999V26.8999H11.9999H35.9999ZM12.2774 6.45589C15.7473 4.1374 19.8267 2.8999 23.9999 2.8999C29.596 2.8999 34.9628 5.12293 38.9199 9.07995C42.8769 13.037 45.0999 18.4038 45.0999 23.9999C45.0999 28.1731 43.8624 32.2526 41.5439 35.7224C39.2254 39.1923 35.9301 41.8968 32.0745 43.4938C28.219 45.0908 23.9765 45.5086 19.8835 44.6945C15.7905 43.8803 12.0308 41.8707 9.07996 38.9198C6.12907 35.969 4.11949 32.2093 3.30534 28.1163C2.49119 24.0233 2.90904 19.7808 4.50605 15.9253C6.10306 12.0698 8.8075 8.77439 12.2774 6.45589Z" stroke="#254766"/>
<path d="M36 27H36.5V26.5V21.5V21H36H12H11.5V21.5V26.5V27H12H36ZM14.2775 9.44928C17.1554 7.52636 20.5388 6.5 24 6.5C28.6413 6.5 33.0925 8.34374 36.3744 11.6256C39.6562 14.9075 41.5 19.3587 41.5 24C41.5 27.4612 40.4736 30.8446 38.5507 33.7225C36.6278 36.6003 33.8947 38.8433 30.697 40.1679C27.4993 41.4924 23.9806 41.839 20.5859 41.1637C17.1913 40.4885 14.0731 38.8218 11.6256 36.3744C9.17822 33.9269 7.51151 30.8087 6.83627 27.4141C6.16102 24.0194 6.50758 20.5007 7.83212 17.303C9.15665 14.1053 11.3997 11.3722 14.2775 9.44928Z" stroke="#254766"/>
</svg>';

const ENABLED_BLACKLIST = '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M7.5 30.5L12 37.5L22.5 42L10.5 40.5L4 31.5L3 22L5.5 13.5L11.5 7L19 3.5L28 3L37.5 7.5L43.5 15.5L45 24L43.5 32L39 39L35 42L31 44L24 45L17.5 44L11.5 40.68L27.5 41.5L35.5 37.5L41 30L41.5 22.5L39 14.5L33 9L24.5 6L15.5 8.5L10 13L6.5 22L7.5 30.5Z" fill="#254766"/>
<rect x="12" y="21" width="24" height="6" fill="#254766"/>
<path d="M35.9999 26.8999H36.4999V26.3999V21.5999V21.0999H35.9999H11.9999H11.4999V21.5999V26.3999V26.8999H11.9999H35.9999ZM12.2774 6.45589C15.7473 4.1374 19.8267 2.8999 23.9999 2.8999C29.596 2.8999 34.9628 5.12293 38.9199 9.07995C42.8769 13.037 45.0999 18.4038 45.0999 23.9999C45.0999 28.1731 43.8624 32.2526 41.5439 35.7224C39.2254 39.1923 35.9301 41.8968 32.0745 43.4938C28.219 45.0908 23.9765 45.5086 19.8835 44.6945C15.7905 43.8803 12.0308 41.8707 9.07996 38.9198C6.12907 35.969 4.11949 32.2093 3.30534 28.1163C2.49119 24.0233 2.90904 19.7808 4.50605 15.9253C6.10306 12.0698 8.8075 8.77439 12.2774 6.45589Z" stroke="#254766"/>
<path d="M36 27H36.5V26.5V21.5V21H36H12H11.5V21.5V26.5V27H12H36ZM14.2775 9.44928C17.1554 7.52636 20.5388 6.5 24 6.5C28.6413 6.5 33.0925 8.34374 36.3744 11.6256C39.6562 14.9075 41.5 19.3587 41.5 24C41.5 27.4612 40.4736 30.8446 38.5507 33.7225C36.6278 36.6003 33.8947 38.8433 30.697 40.1679C27.4993 41.4924 23.9806 41.839 20.5859 41.1637C17.1913 40.4885 14.0731 38.8218 11.6256 36.3744C9.17822 33.9269 7.51151 30.8087 6.83627 27.4141C6.16102 24.0194 6.50758 20.5007 7.83212 17.303C9.15665 14.1053 11.3997 11.3722 14.2775 9.44928Z" stroke="#254766"/>
</svg>';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "../vendor/autoload.php";

session_start();
require_once "../db_connection.inc.php";
require_once "../includes/repondre_avis.php";
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
        $liste_avis = $dbh->query($query)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($liste_avis as $avis) {
            if (!$avis['blacklist']) {
                afficher_avis($avis);
        ?>
                <hr style="border: none; border-top: 2px solid var(--navy-blue); margin: 20px; margin-left: 0px;">
            <?php
            } else if ($avis['blacklist'] && (offre_appartient($_SESSION['identifiant'], $avis['idoffre']) || avis_appartient($avis['idavis']))) {
                afficher_avis($avis);
            ?>
                <hr style="border: none; border-top: 2px solid var(--navy-blue); margin: 20px; margin-left: 0px;">
    <?php
            }
        }
    } catch (PDOException $e) {
        die("Couldn't fetch comments : " . $e->getMessage());
    }
}

function get_acc_name($id)
{
    $ret = "";
    global $dbh;

    $query = "SELECT pseudo FROM " . NOM_SCHEMA . "." . VUE_MEMBRE . " WHERE idcompte = '" . $id . "';";
    try {
        $ret = $dbh->query($query)->fetch();
    } catch (PDOException $e) {
        die("Couldn't fetch account username : " . $e->getMessage());
    }

    return $ret[0];
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

function est_premium($idoffre)
{
    $ret = false;
    global $dbh;

    // Requête pour vérifier si l'offre est premium
    $query = "SELECT * FROM " . NOM_SCHEMA . "." . NOM_TABLE_OFFRE . " WHERE idoffre = '" . $idoffre . "' AND abonnement = 'Premium';";
    $stmt = $dbh->prepare($query);
    $stmt->execute();

    // Récupérer la réponse
    $reponse = $stmt->fetchColumn();

    if ($reponse != 0) $ret = true;

    return $ret;
}

function mail_signalement($idavis)
{
    $mail = new PHPMailer(true);
    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'moderation.tripenarvor@gmail.com';                     //SMTP username
        $mail->Password   = 'jdqq xxch iihn pdvd ';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        //Recipients
        $mail->setFrom('moderation.tripenarvor@gmail.com', 'admin');
        $mail->addAddress('moderation.tripenarvor@gmail.com', 'admin');     //Add a recipient
        //Content
        $mail->Subject = "Signalement d'avis";
        $mail->Body    = "L'avis " . $idavis . " de l'offre " . $_GET['idoffre'] . " a été signlé par un utilisateur.";
        $mail->send();
        $mail->smtpClose();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function signalerAvis($idavis)
{
    global $dbh;

    try {
        $query = "UPDATE " . NOM_SCHEMA . "." . NOM_TABLE_AVIS . "
              set signale = true where idavis = :idavis";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":idavis", $idavis);
        $stmt->execute();
        mail_signalement($idavis);
    } catch (PDOException $e) {
        die("Couldn't set review report to true : " . $e->getMessage());
    }
}

if (isset($_POST['idavis_signaler'])) {
    signalerAvis($_POST['idavis_signaler']);
    echo "<script>createToast('blacklisterAvis')</script>";
}

/*
 * prend en parametre l'id de l'avis a blacklist et la date a laquelle l'avis sera de-blacklist (format : "AAAA-MM-JJ hh:mm:ss")
 * set l'attribut blacklist de l'avis a true
 * set la date de deblacklist a $timeunblacklist
 */
function blacklist_avis($idavis, $timeunblacklist)
{
    global $dbh;
    $query = "UPDATE " . NOM_SCHEMA . "." . VUE_AVIS . " SET blacklist = true, timeunblacklist = :timeunblacklist WHERE idavis = :idavis;";
    $timeunblacklist = gmdate("Y-m-d H:i:s", strtotime($timeunblacklist));

    try {
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":timeunblacklist", $timeunblacklist);
        $stmt->bindParam(":idavis", $idavis);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "<script>alert('L'avis n'a pas pu etre blacklist)</script>";
        echo "<script>Console.log('" . $e->getMessage() . "')</script>";
    }
}


/*
 * prend en parametre l'id de l'offre sur lequel on souhaite connaitre le nombre de jeton de blacklist
 * retourne le nombre de jeton de blacklist disponible
 * retourne false si il n'y en a pas
 */
function count_blacklist($idoffre)
{
    global $dbh;

    $query = "SELECT blacklistdispo FROM " . NOM_SCHEMA . "." . NOM_TABLE_OFFRE . " WHERE idoffre = :idoffre";
    try {
        $stmt = $dbh->prepare($query);
        $ret = $stmt->execute([':idoffre' => $idoffre]);
    } catch (PDOException $e) {
        die("Couldn't fetch blacklist tokens : " . $e->getMessage());
    }

    return $ret;
}

if (isset($_POST['idavis_blacklister'])) {
    $selected_date = $_POST['date'] . " " . $_POST['time'];
    if (count_blacklist($_POST['idoffre']) > 0) blacklist_avis($_POST['idavis_blacklister'], $selected_date);
    else echo "<script>alert('Vous n'avez plus de jeton de blacklist')</script>";
    echo "<script>alert('avis blacklisté')</script>";
}


/*
 * prend en argument un array contenant toutes les informations d'un avis
 * affiche un div DISABLED_DISLIKE esentant l'avis
 */
function afficher_avis($avis)
{
    global $dbh;
    $date_visite = getdate(strtotime($avis['datevisite']));
    ?>
    <div class="avis">
        <?php if ($avis['blacklist']) { ?>
            <h2>BLACKLIST</h2>
        <?php } ?>
        <section>
            <p><?php echo get_acc_name($avis['idcompte']) ?></p>
        </section>
        <div class="avis-header">
            <section class="avis-titre">
                <h2 class="note_avis"> <?php echo $avis['noteavis'] . "/5"; ?> </h2>
                <h1 class="titre_avis"><?php echo $avis['titre']; ?></h1>
            </section>
            <section class="avis-infos" style="display: flex; flex-direction: column;">
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

        $query = "SELECT blacklist FROM " . NOM_SCHEMA . "." . NOM_TABLE_AVIS . " WHERE idavis = :idavis";
        $stmt = $dbh->prepare($query);
        $stmt->execute([':idavis' => $idAvis]);
        $blacklist = $stmt->fetch(PDO::FETCH_ASSOC);

        $isBlacklisted = $blacklist;
        ?>
        <?php
        if (isset($_SESSION['identifiant']) && !$avis['signale'] && !avis_appartient($avis['idavis'])) {
        ?>
            <form id="form-signaler" method="post" enctype="multipart/form-data">
                <input type="text" style="display: none" name="idavis_signaler" value="<?php echo $avis['idavis'] ?>">
                <label>
                    <input style="display: none;" type="submit" class="smallButton" value="Signaler">
                    <?php echo REPORT; ?>
                </label>
            </form>
        <?php
        }
        if (isset($_SESSION['identifiant']) && avis_appartient($avis['idavis'])) {
        ?>
            <div>
                <button style="
        display: flex;
        align-items: center;
                background: none;
                color: inherit;
                border: none;
                padding: 0;
                font: inherit;
                cursor: pointer;
                outline: inherit;
            " type="button" onclick="modifier_avis(this, <?php echo '\'' . $avis['idavis'] . '\', \'' . $avis['idoffre'] . '\''; ?>)" class="modifier">
                    <?php echo EDIT;  ?>
                </button>
            </div>
            <section style="display: flex; align-items: center; justify-content: space-between; max-width: 200px;">
            <?php
        }
        if (offre_appartient($_SESSION['identifiant'], $avis['idoffre']) && est_premium($avis['idoffre']) && count_blacklist($avis['idoffre'])) {
            ?>
                <button onclick="ouvrirPopup()" data-avis-id="<?php echo $idAvis; ?>">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <?php echo $isBlacklisted ? DISABLED_BLACKLIST : ENABLED_BLACKLIST; ?>
                    </svg>
                </button>
                <form class="popup" id="popup" method="post" enctype="multipart/form-data">
                    <div>
                        <input type="text" style="display: none" name="idavis_blacklister" value="<?php echo $avis['idavis'] ?>">
                        <input type="text" style="display: none" name="idoffre" value="<?php echo $avis['idoffre'] ?>">
                        <p>Date de dé-blacklistage :</p>
                        <input id="bl_date" type="date" name="date">
                        <input id="bl_time" type="time" name="time" step="1">
                    </div>
                    <div>
                        <button id="annuler" type="button" class="smallButton" onclick="fermerPopup()">Annuler</button>
                        <input id="blacklister" type="submit" class="smallButton" value="blacklister" onclick="ajustement_blacklist()">
                    </div>
                </form>

                <script>
                    function ouvrirPopup() {
                        ajustement_blacklist();
                        document.getElementById("popup").style.display = "block";
                    }

                    function fermerPopup() {
                        document.getElementById("popup").style.display = "none";
                    }

                    function ajustement_blacklist() {
                        let bl_date = document.getElementById("bl_date");
                        let bl_time = document.getElementById("bl_time");
                        let maintenant = new Date();

                        if (bl_date.value == "") {
                            let annee = maintenant.getFullYear() + 1;
                            let mois = maintenant.getMonth() + 1;
                            let jour = maintenant.getDate();

                            if (mois < 10) { // ajout d'un 0 devant le mois lorsqu'il est inférieur à 10 pour se conformer au format date
                                mois = `0${mois}`;
                            }

                            if (jour < 10) { // ajout d'un 0 devant le jour lorsqu'il est inférieur à 10 pour se conformer au format date
                                jour = `0${jour}`;
                            }

                            bl_date.value = `${annee}-${mois}-${jour}`;
                        }
                        if (bl_time.value == "") {
                            let heure = maintenant.getHours();
                            let minute = maintenant.getMinutes();

                            if (heure < 10) { // ajout d'un 0 devant l'heure lorsqu'elle est inférieur à 10 pour se conformer au format time
                                heure = `0${heure}`;
                            }

                            if (minute < 10) { // ajout d'un 0 devant les minutes lorsqu'elles sont inférieurs à 10 pour se conformer au format time
                                minute = `0${minute}`;
                            }

                            bl_time.value = `${heure}:${minute}:00`;
                        }
                    }
                </script>
            <?php
        }
        if (est_membre($_SESSION["identifiant"])) {
            ?>
                <section id="like" style="display: flex; align-items: center; margin: 10px; justify-content: space-between; max-width: 200px;">
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
                <section id="like" style="display: flex; align-items: center; margin: 10px; justify-content: space-between; max-width: 200px;">
                    <button disabled style="
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
                            <?php echo ENABLED_LIKE; ?>
                        </svg>
                        <h2 id="nb_like" style="margin-left: 10px; color: var(--navy-blue);"><?php echo $avis['nblike'] ?> </h2>
                    </button>
                    <button disabled style="
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
                            <?php echo ENABLED_DISLIKE; ?>
                        </svg>
                        <h2 id="nb_dislike" style="margin-left: 10px; color: var(--navy-blue);"><?php echo $avis['nbdislike'] ?> </h2>
                    </button>
                </section>
            <?php
        }
            ?>
            </section>
            <?php if ($avis['reponse']): ?>
                <div class="reponse">
                    <h3>Réponse :</h3>
                    <p><?php echo htmlspecialchars($avis['reponse']); ?></p>
                    <script>
                        console.log("La reponse: <?php echo $avis['reponse']; ?>");
                    </script>
                </div>
            <?php endif;
            if (can_repondre($idAvis)) {
                afficher_form_reponse($avis['idavis'], $avis['idoffre']);
            }
            ?>
    </div>
<?php
}

// Permet de savoir si une réponse existe pour un avis donné
function reponse_existe($idAvis)
{
    global $dbh;

    try {
        // Requête pour vérifier si une réponse existe pour l'avis donné
        $query = "SELECT reponse FROM sae._avis WHERE idavis = :idavis";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':idavis', $idAvis, PDO::PARAM_STR);
        $stmt->execute();

        // Récupérer la réponse
        $reponse = $stmt->fetchColumn();


        // Vérifier si la réponse est NULL, vide ou égale à "[null]"
        if ($reponse === null || $reponse === '' || $reponse === '[null]') {
            return false; // Aucune réponse valide
        } else {
            return true; // Une réponse existe
        }
    } catch (PDOException $e) {
        // En cas d'erreur, log l'erreur et retourne false
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
        $query = "SELECT idavis FROM " . NOM_SCHEMA . "." . NOM_TABLE_AVIS . " WHERE idavis = :id_avis AND idcompte = :id_compte";
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
