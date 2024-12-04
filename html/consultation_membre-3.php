<?php
session_start();
require_once "db_connection.inc.php";
global $dbh;

require_once "verif_connection.inc.php";

function est_membre($email)
{
    $ret = false;
    global $dbh;

    $query = "SELECT * FROM " . NOM_SCHEMA . "." . VUE_MEMBRE . " WHERE email = '" . $email . "';";
    $row = $dbh->query($query)->fetch();

    if (isset($row['pseudo'])) $ret = true;

    return $ret;
}

function est_prive($email)
{
    $ret = false;
    global $dbh;

    $query = "SELECT * FROM " . NOM_SCHEMA . "." . VUE_PRO_PRIVE . " WHERE email = '" . $email . "';";
    $row = $dbh->query($query)->fetch();

    if (isset($row['iban'])) $ret = true;

    return $ret;
}

print_r ($_SESSION['identifiant']);
if (isset($_SESSION['identifiant']) && valid_account()) {
    $email = $_SESSION['identifiant'];
    $requeteCompte = $dbh->prepare("SELECT idcompte, email FROM sae._compte WHERE email = '" . $email."';"); //, PDO::FETCH_ASSOC
    $requeteCompte->execute();
    //$idCompte = $requeteCompte['idcompte'];
    $idCompte = $requeteCompte->fetch(PDO::FETCH_ASSOC)["idcompte"];

}else{
    ?> <script>
        window.location = "connection_pro-3.php";
    </script> <?php
            }

if (!est_membre($email)){
    ?> <script>
        window.location = "consultation_pro-3.php";
    </script> <?php
}
$schemaCompte=VUE_MEMBRE;

$queryCompte = 'SELECT * FROM ' . NOM_SCHEMA .'.'. $schemaCompte .' WHERE idcompte = :idcompte';
$sthCompte = $dbh->prepare($queryCompte);
$sthCompte->bindParam(':idcompte', $idCompte, PDO::PARAM_STR);
$sthCompte->execute();
//$count = $sthCompte->fetchColumn();
$count = 5;
$compte = $sthCompte->fetch(PDO::FETCH_ASSOC);

if ($compte) {
    //$row = $rows[0];
    $email = $compte["email"];
    $adresse = $compte['numadressecompte'] . " " . $compte['ruecompte'];
    $ville = $compte['villecompte'];
    $codePostal = $compte['codepostalcompte'];
    $telephone = $compte['telephone'];
    $nom = $compte['nommembre'];
    $prenom = $compte['prenommembre'];
    $pseudo = $compte['pseudo'];
    $image = $compte['urlimage'];
} else {    
?> <script>
        window.location = "consultation_liste_offres_cli-1.php";
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
                <h1>Bonjour, <?php echo htmlspecialchars($pseudo); ?></h1>
            </div>
            <div class="profile-row">
                <form>

                    <div id="code-postal-ville">
                        <div class="input-group">
                            <input type="text" id="nom" value="<?php echo htmlspecialchars($nom) ?>" readonly>
                        </div>

                        <div class="input-group">
                            <input type="text" id="prenom" value="<?php echo htmlspecialchars($prenom) ?>" readonly>
                        </div>
                    </div>

                    <div class="input-group">
                        <input type="text" id="courriel" value="<?php echo htmlspecialchars($email) ?>" readonly>
                    </div>

                    <div class="input-group">
                        <input type="text" id="telephone" value="<?php echo htmlspecialchars($telephone) ?>" readonly>
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
                </form>

                <div class="actions-profil">
                    <img src="<?php echo htmlspecialchars($image) ?>" alt="Photo de profil" class="photo-profil">
                    <button id="bouton-modifier" type="button" onclick="window.location.href='modification_membre-3.php'">Modifier informations</button>
                    <button id="bouton-supprimer" type="button">Supprimer le compte</button>
                    <form action="deco.php" method="post" enctype="multipart/form-data">
                        <input id="bouton-supprimer" type="submit" value="Se déconnecter">
                    </form>
                </div>
            </div>

        </section>
    </main>
    <?php require_once "footer_inc.html"; ?>

</body>
<script src="main.js"></script>

</html>
