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
    <title>PACT - Connexion compte pro</title>
</head>

<body>
    <!-- <?php require_once "header_inc.html"; ?> -->
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
                <!-- <form action="connection_pro-3.php" method="post" enctype="multipart/form-data">
                    <p>Mauvais identifiant et/ou mot de passe</p>
                    <label>Identifiant</label>
                    <input class="champs" type="text" id="identifiant" name="identifiant" value="<?php echo $identifiant ?>" required>
                    <br>
                    <label>Mot de passe</label>
                    <input class="champs mdp" type="password" id="motdepasse" name="motdepasse" value="<?php echo $mdp ?>" required />
                    <a href="">Mot de pass oubli&eacute; ?</a>
                    <br>
                    <input class="smallButton" type="submit" value="Se connecter" name="connexion">
                    <input class="smallButton" type="submit" value="Créer un compte">
                </form> -->
                <script>
                    alert("Mauvais identifiant et/ou mot de passe");
                </script>
                <?php
                } else {
                    echo "Connexion réussie";
                    $_SESSION["identifiant"] = $identifiant;
                    $_SESSION["mdp"] = $mdp; ?> 
                    <script>
                        window.location = "consulter_liste_offres_pro-1.php";
                    </script> 
                <?php }
            } else 
            if (isset($_SESSION["identifiant"])) {?> 
                <script>
                    window.location = "consulter_liste_offres_pro-1.php";
                </script> 
            <?php } ?>
            <form action="connection_pro-3.php" method="post" enctype="multipart/form-data">
                <label>Identifiant</label>
                <input class="champs" type="text" id="identifiant" name="identifiant" value="<?php echo $identifiant ?>" required>
                <br>
                <label>Mot de passe</label>
                <input class="champs mdp" type="password" id="motdepasse" name="motdepasse" value="<?php echo $mdp ?>" required />
                <a href="inscription_pro-1.php">Mot de passe oubli&eacute; ?</a>

                <br>
                <div>
                    <input class="smallButton" type="submit" value="Créer un compte">
                    <input class="smallButton" type="submit" value="Se connecter" name="connexion">
                </div>
            </form>
    </main>

    <?php require_once "footer_inc,html"; ?>

</body>
<script src="main.js"></script>

</html>
