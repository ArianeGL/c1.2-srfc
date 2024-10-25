<?php /*
session_unset();
session_destroy();*/
session_start();
require_once "db_connection.inc.php";
// POUR LES TESTS: [email] => agnes.pinson@gmail.com [motdepasse] => motdepasse
$query1 = "SELECT email, motdepasse FROM " . NOM_SCHEMA . "._compteProfessionnel NATURAL JOIN " . NOM_SCHEMA . "._compte;";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./styles/connexion.css">
    <link rel="stylesheet" href="style.css">
    <title>PACT - Connexion compte pro</title>
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
                <div id="div3" class="hidden"></div>
            </div>
        </div>
    </header>
    <main>
        <?php
        global $dbh;

        $connexion = 0;
        if (isset($_POST["identifiant"])) {
            $identifiant = $_POST["identifiant"];
            $mdp = $_POST["motdepasse"];
            //$mdp_crypte = md5($mdp);

            $queryCompte = 'SELECT COUNT(*) FROM sae._compte NATURAL JOIN sae._compteProfessionnel WHERE email = :email AND motdepasse = :mdp';
            $sthCompte = $dbh->prepare($queryCompte);
            $sthCompte->bindParam(':email', $identifiant, PDO::PARAM_STR);
            $sthCompte->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $sthCompte->execute();
            $count = $sthCompte->fetchColumn();

            if ($count != 0) {
                $connexion = true;
            }


            if ($connexion == false) { ?>
                <form action="connection_pro-3.php" method="post" enctype="multipart/form-data">
                    <p>Mauvais identifiant et/ou mot de passe</p>
                    <label class="identifiant"> Identifiant</label>
                    <input class="champs" type="text" id="identifiant" name="identifiant" value="<?php echo $identifiant ?>" required>
                    <br />
                    <label class="motdepasse">Mot de passe</label>
                    <input class="champs" type="password" id="motdepasse" name="motdepasse" value="<?php echo $mdp ?>" required />
                    <br />
                    <input class="bouton" type="submit" value="Se connecter" name="connexion">
                    <input class="bouton" type="submit" value="Mot de passe oublié">
                    <input class="bouton" type="submit" value="Créer un compte">
                </form>
            <?php
            } else {
                echo "Connexion réussie";
                $_SESSION["identifiant"] = $identifiant;
                $_SESSION["mdp"] = $mdp;
            ?> <script>
                    window.location = "consulter_liste_offres_pro-1.php";
                </script> <?php  //Ligne a changer
                        }
                    } else if (isset($_SESSION["identifiant"])) {
                            ?> <script>
                window.location = "consulter_liste_offres_pro.php";
            </script> <?php  //Ligne a changer
                    } else { ?>
            <form action="connection_pro-3.php" method="post" enctype="multipart/form-data">
                <label class="identifiant"> Identifiant</label>
                <input type="text" id="identifiant" name="identifiant" required>
                <br />
                <label class="motdepasse">Mot de passe</label>
                <input type="password" id="motdepasse" name="motdepasse" required />
                <br />
                <input class="bouton" type="submit" value="Se connecter" name="connexion">
                <input class="bouton" type="submit" value="Mot de passe oublié">
                <input class="bouton" type="submit" value="Créer un compte" onclick="window.location.href='creation_compte_pro-3.php'">
            </form>
        <?php } ?>

    </main>

    <?php require_once "footer_inc,html"; ?>

</body>
<script src="main.js"></script>

</html>
