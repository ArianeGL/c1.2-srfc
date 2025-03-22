<?php
require 'vendor/autoload.php';

use OTPHP\TOTP;

$totp = TOTP::create();
$totp->setLabel('MonSiteWeb');
$totp->setIssuer('MonSiteWeb');

$qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($totp->getProvisioningUri());

echo "<p>Scannez ce QR Code avec Votre application :</p>";
echo "<img src='$qrCodeUrl'>";
?>
