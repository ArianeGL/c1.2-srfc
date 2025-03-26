<?php
require 'vendor/autoload.php';

use OTPHP\TOTP;

// Générer un secret unique pour un utilisateur
$totp = TOTP::create(); 
$totp->setLabel('MonApplication'); // Nom de votre application
$totp->setIssuer('MaSociete'); // Nom de votre entreprise

$secret = $totp->getSecret(); // Secret unique

// Sauvegarder ce secret en base de données, lié à l'utilisateur
echo "Secret pour cet utilisateur : " . $secret."</br>";

$uri = $totp->getProvisioningUri(); 
$qrcode_url = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($uri) . "&size=200x200";

echo "<img src='$qrcode_url' alt='QR Code'></br>";

$totp = TOTP::create($secret_enregistre); // Récupérer le secret de l'utilisateur

$otp_saisi = $_GET['otp']; // Code OTP saisi par l’utilisateur

if ($totp->verify($otp_saisi)) {
    echo "Code valide";
} else {
    echo "Code invalide";
}

?>