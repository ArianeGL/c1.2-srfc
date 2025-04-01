<?php
require '../vendor/autoload.php';
require_once("../db_connection.inc.php");
require_once("../includes/consts.inc.php");

use OTPHP\TOTP;

$email = $_SESSION['identifiant'];

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
$stmt->bindParam(':email', $_SESSION['identifiant']);

$stmt->execute();



?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../includes/style.css  ">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

        <title>PACT</title>
        <script src="../scripts/image_preview.js"></script>
</head>
<body>
    <?php require_once HEADER; ?>
    <div id="qrCodeOtp">
        <p>Scannez le QR Code avec une application d'authentification à 2 facteurs</p>
        <?php 
            $uri = $totp->getProvisioningUri(); 
            $qrcode_url = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($uri) . "&size=200x200";

            echo "<img src='$qrcode_url' alt='QR Code' id='qrCode'></br>";
        ?>
        <button class="smallButton" onclick="window.location='../offres/liste?toast=creaCompte.php'">Retour</button> 
    </div>
</body>