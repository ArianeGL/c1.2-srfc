<?php
session_start();

function est_pro(): bool
{
    $pro = false;
    global $dbh;

    try {
        $query = "SELECT COUNT(*) FROM " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE_PRO . " NATURAL JOIN " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . " WHERE email = :email;";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":email", $_SESSION['identifiant']);
        $stmt->execute();

        if ($stmt->fetchColumn() != false) {
            $pro = true;
        }
    } catch (PDOException $e) {
        die("PDO Query error : " . $e->getMessage());
    }

    return $pro;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./favicon.ico">
</head>
<body>
    <header>
        <div id="homeButtonID" class="homeButton">
            <img src="./IMAGES/LOGO-SRFC.webp" alt="HOME PAGE" height="80%" style="margin-left: 5%; margin-right: 5%;">
            <h2>PACT</h2>
            <p id="slogan" class="">Des avis qui comptent, des voyages qui marquent.</p>
        </div>
        <div>
            <div class="container">
                <button onclick="window.location.href='./consulter_liste_offres_cli-1.php'" class="buttons header-button1">
                    <h4>Offres</h4>
                </button>
                
                <button style="display: none;" class="buttons header-button2">
                    <h4>Factures</h4>
                </button>
                
                <button style="display: none;" class="buttons header-button2">
                    <h4>R&eacute;cent</h4>
                </button>

                <button class="buttons header-button3" onclick="window.location.href='<?php if(!isset($_SESSION['identifiant'])) { echo "./connection-3.php"; }else if (est_pro()) { echo "./creation_compte_pro-3.php"; }else { echo "./creation_compte_membre-3.php"; }?>'">
                    <h4>Compte</h4>
                </button>
            </div>
            <div class="indicator">
                <div id="div1"></div>
                <!-- <div id="div2"></div> -->
                <div id="div3"></div>
            </div>
        </div>
    </header>
</body>
</html>
