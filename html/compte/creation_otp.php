<?php
require '../vendor/autoload.php';
require_once("../db_connection.inc.php");
require_once("../includes/header.inc.php");

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

echo "Secret pour cet utilisateur : " . $secret."</br>";

$uri = $totp->getProvisioningUri(); 
$qrcode_url = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($uri) . "&size=200x200";

echo "<img src='$qrcode_url' alt='QR Code'></br>";

?>  