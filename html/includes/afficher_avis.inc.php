<script src="../scripts/aime.js"></script>
<?php

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

function aimeAvis()
{
    //$query=


    echo "onclick=aime()";
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

if (isset($_POST['idavis'])) {
    signalerAvis($_POST['idavis']);
    echo "<script>alert('signalement envoyé')</script>";
}
?>

<?php

/*
 * prend en argument un array contenant toutes les informations d'un avis
 * affiche un div representant l'avis
 */
function afficher_avis($avis)
{
    $date_visite = getdate(strtotime($avis['datevisite']));
?>
    <div class="avis" id="modifier_avis">
        <div class="avis-header">
            <section class="avis-titre">
                <h2 class="note_avis"> <?php echo $avis['noteavis'] . "/5"; ?> </h2> <!-- a modifier avec le bon affichage de la note -->
                <h1 class="titre_avis"><?php echo $avis['titre']; ?></h1>
            </section>
            <section class="avis-infos">
                <h3 class="date_visite"> <?php echo format_date($date_visite); ?> </h3>
                <p class="contexte"> <?php echo $avis['contexte']; ?> </p>
            </section>
            <?php
            if (isset($_SESSION['identifiant']) && !$avis['signale']) {
            ?>
                <section>
                    <form method="post">
                        <input type="text" style="display: none" name="idavis" value="<?php echo $avis['idavis'] ?>">
                        <input type="submit" value="Signaler">
                    </form>
                </section>
            <?php
            }
            ?>
        </div>

        <p class="commentaire"><?php echo $avis['commentaire'] ?></p>
        <?php
        if (isset($_SESSION['identifiant']) && avis_appartient($avis['idavis'])) {
        ?>
            <button type="button" onclick="modifier_avis(this, <?php echo "'" . $avis['idavis'] . "', '" . $avis["idoffre"]; ?>')" class="smallButton modifier">Modifier</button>
        <?php
        }
        if (est_membre($_SESSION["identifiant"])) {
        ?>
            <input type="button" id="pouceHaut" <?php aimeAvis() ?> value="<?php echo $avis["nblike"] ?>👍"></input>
            <?php
            ?><input type="button" id="pouceBas" value="<?php echo $avis["nbdislike"] ?>👎" onclick="aimePas()"></input> <?php
                                                                                                                        } else {
                                                                                                                            ?><input type="button" id="pouceHaut" value="<?php echo $avis["nblike"] ?>👍" disabled></input> <?php
                                                                                                            ?><input type="button" id="pouceBas" value="<?php echo $avis["nbdislike"] ?>👎"></input> <?php
                                                                                                                        }
                                                                                                        ?>
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
