<?php
session_start();

require '../vendor/autoload.php';
require_once("../db_connection.inc.php");
require_once("../includes/consts.inc.php");

use OTPHP\TOTP;

$email = $_SESSION['identifiant_otp'];
// Générer un secret unique pour un utilisateur
$totp = TOTP::create(); 
$totp->setLabel($email);
$totp->setIssuer('SRFC');

$secret = $totp->getSecret(); // Secret unique

//insertion dans la bdd
$query = "update ".NOM_SCHEMA.".".NOM_TABLE_COMPTE."
    set urlotp = :secret,
    otp = true
    where email = :email;";

$stmt = $dbh->prepare($query);
$stmt->bindParam(':secret', $secret);
$stmt->bindParam(':email', $_SESSION['identifiant_otp']);

$stmt->execute();

$_SESSION["identifiant"] = $_SESSION["identifiant_otp"];
$_SESSION["mdp"] = $_SESSION["mdp_otp"];
?>
<!DOCTYPE html>
<html lang="fr" id="creation_otp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../includes/style.css">
    <title>Création compte membre - PACT</title>
</head>
<body>
    <?php require_once HEADER ?>
    <div id="divQRCode">
    <p>Scannez ce QR code avec une application d'authentifiaction à deux facteurs</p>
    <?php 
    $uri = $totp->getProvisioningUri(); 
    $qrcode_url = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($uri) . "&size=200x200";
    ?>
    <img id="QRCode" src=" <?php echo $qrcode_url ?>" alt="QR Code"></br>
    <button class="smallButton" onclick="window.location='../offres/liste.php'">Retour</button>
    </div>
</body>
</html> 