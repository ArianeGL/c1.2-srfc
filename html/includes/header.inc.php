<?php
session_start();
require_once "../db_connection.inc.php";

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

function whereToGo(): string // retourne le nom du fichier à appeler pour les comptes
{
    $output = "";
    if (!isset($_SESSION['identifiant'])) {
        $output = "connection.php";
    } else if (est_pro()) {
        $output = "consultation_pro.php";
    } else {
        $output = "consultation_membre.php";
    }
    return $output;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./favicon.ico">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div id="homeButtonID" class="homeButton">
            <img src="../IMAGES/LOGO-SRFC.png" alt="HOME PAGE" height="80%" style="margin-left: 5%; margin-right: 5%;">
            <h2>PACT</h2>
            <p id="slogan" class="">Des avis qui comptent, des voyages qui marquent.</p>
        </div>
        <div>
            <div class="container">
                <button onclick="window.location.href='../offres/liste.php'" class="buttons header-button1">
                    <h4><?php if (est_pro()){echo "Mes ";}?>Offres</h4>
                </button>

                <?php if (est_pro()) { ?>
                <button onclick="window.location.href='../factures/liste.php'" class="buttons header-button2" >
                    <h4>Factures</h4>
                </button>
                <?php } ?>

                <button class="buttons header-button3" onclick="window.location.href='../compte/<?php echo(whereToGo()); ?>'">
                    <h4>Compte</h4>
                </button>
            </div>
            <div class="indicator">
                <div id="div1"></div>
                <?php if (est_pro()) { ?>
                <div id="div2"></div>
                <?php } ?>
                <div id="div3"></div>
            </div>
        </div>
    </header>
</body>
</html>
