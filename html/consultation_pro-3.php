<?php
require_once "db_connection.inc.php";
global $dbh;
session_start();
require_once "verif_connection.inc.php";

if (isset($_SESSION['identifiant']) && valid_account()) {
    $email = $_SESSION['identifiant'];
    $requeteCompte = $dbh->prepare('SELECT idcompte, email FROM sae._compte WHERE email = :email');
    $requeteCompte->bindParam(':email', $email, PDO::PARAM_STR);
    $requeteCompte->execute();
    $result = $requeteCompte->fetch(PDO::FETCH_ASSOC);
    $idCompte = $result['idcompte'];
}

$queryCompte = 'SELECT * FROM ' . NOM_SCHEMA . '._compte NATURAL JOIN ' . NOM_SCHEMA . '._compteProfessionnel WHERE idcompte = :idcompte';
$sthCompte = $dbh->prepare($queryCompte);
$sthCompte->bindParam(':idcompte', $idCompte, PDO::PARAM_STR);
$sthCompte->execute();
$count = $sthCompte->rowCount();

if ($count != 0) {
    $rows = $sthCompte->fetchAll();
    $row = $rows[0];
    $email = $row['email'];
    $adresse = $row['numadressecompte'] . " " . $row['ruecompte'];
    $ville = $row['villecompte'];
    $codePostal = $row['codepostalcompte'];
    $telephone = $row['telephone'];
    $denomination = $row['denomination'];
    $IBAN = $row['iban'];
    $image = $row['urlimage'];
} else {
?> <script>
        window.location = "connection_pro-3.php";
    </script> <?php
            }

                ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./styles/consultation.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

    <title>PACT</title>
</head>

<body>
    <header>
        <div id="homeButtonID" class="homeButton">
            <img src="./IMAGES/LOGO-SRFC.webp" alt="HOME PAGE" height="80%" style="margin-left: 5%; margin-right: 5%;">
            <h2>PACT</h2>
            <p id="slogan" class="sloganHide">Des avis qui comptent, des voyages qui marquent.</p>
        </div>
        <div>
            <div class="container">
                <button class="buttons header-button1">
                    <h4>Offres</h4>
                </button>

                <!-- Button for back office -->
                <button class="buttons header-button2">
                    <h4>Factures</h4>
                </button>

                <!-- Button for front office -->
                <button style="display: none;" class="buttons header-button2">
                    <h4>R&eacute;cent</h4>
                </button>

                <button class="buttons header-button3">
                    <h4>Compte</h4>
                </button>
            </div>
            <div class="indicator">
                <div id="div1" class="hidden"></div>
                <div id="div2" class="hidden"></div>
                <div id="div3" class="seek"></div>
            </div>
        </div>
    </header>
    <main id="box">
        <section class="profile">
            <div class="profile-header">
                <h1>Bonjour, <?php echo htmlspecialchars($denomination); ?></h1>
            </div>
            <div class="profile-row">
                <form>
                    <div class="input-group">
                        <input type="text" id="nom-societe" value="<?php echo htmlspecialchars($denomination) ?>" readonly>
                    </div>

                    <div class="input-group">
                        <input type="text" id="courriel" value="<?php echo htmlspecialchars($email) ?>" readonly>
                    </div>

                    <div class="input-group">
                        <input type="text" id="telephone" value=<?php echo htmlspecialchars($telephone) ?>readonly>
                    </div>

                    <div class="input-group">
                        <input type="text" id="adresse" value="<?php echo htmlspecialchars($adresse) ?>" readonly>
                    </div>

                    <div id="code-postal-ville">
                        <div class="input-group">
                            <input type="text" id="code-postal" value="<?php echo htmlspecialchars($codePostal) ?>" readonly>
                        </div>

                        <div class="input-group">
                            <input type="text" id="ville" value="<?php echo htmlspecialchars($ville) ?>" readonly>
                        </div>
                    </div>

                    <div class="input-group">
                        <input type="text" id="IBAN" value="<?php echo htmlspecialchars($IBAN) ?>" readonly>
                    </div>
                </form>

                <div class="actions-profil">
                    <img src="<?php echo htmlspecialchars($image) ?>" alt="Photo de profil" class="photo-profil">
                    <button id="bouton-modifier" type="button" onclick="window.location.href='modification_pro.php'">Modifier informations</button>
                    <button id="bouton-supprimer" type="button">Supprimer le compte</button>
                </div>
            </div>

        </section>
    </main>
    <?php require_once "footer_inc.html"; ?>

</body>
<script src="main.js"></script>

</html>
