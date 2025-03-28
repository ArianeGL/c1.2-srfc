<?php
session_start();
require_once "../db_connection.inc.php";
require_once "../includes/consts.inc.php";

use OTPHP\TOTP;

function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

function est_pro(): bool
{
    $pro = false;
    global $dbh;

    try {
        $query = "SELECT * FROM " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE_PRO . " NATURAL JOIN " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . " WHERE email = :email;";
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
<html lang="fr" id="connection">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../includes/style.css">
    <title>PACT - Se Connecter</title>
</head>

<body>
    <main>
        <?php
        global $dbh;

        $connexion = false;
        $attempt = 0;
        $isotp = false;

        if (isset($_POST["identificateur"])) {
            $identificateur = $_POST["identificateur"];
            $mdp = $_POST["motdepasse"];

            $queryCompte = "SELECT COUNT(*) 
                            FROM " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . " 
                            WHERE email = :email AND motdepasse = :mdp";
            $sthCompte = $dbh->prepare($queryCompte);
            $sthCompte->bindParam(':email', $identificateur, PDO::PARAM_STR);
            $sthCompte->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $sthCompte->execute();


            if ($sthCompte->fetchColumn() != false) {
                $connexion = true;
            }

            $querry_otp = "SELECT urlotp,otp FROM ". NOM_SCHEMA .".". NOM_TABLE_COMPTE ." 
                            WHERE email = :email AND motdepasse = :mdp";
            $sthotp = $dbh->prepare($querry_otp);
            $sthotp->bindParam(':email', $identificateur);
            $sthotp->bindParam(':mdp', $mdp);
            $sthotp->execute();

            $isotp = $sthotp->fetch(PDO::FETCH_ASSOC)["otp"];
            //print_r($isotp);

            if (!$connexion) {
                $attempt++; ?>
                <form action=<?php echo CONNECTION_COMPTE; ?> method="post" enctype="multipart/form-data">
                    <label>Identifiant</label>
                    <input class="champs" type="text" id="identificateur" name="identificateur" value="<?php echo $identificateur; ?>" required>
                    <br>
                    <label>Mot de passe</label>
                    <input class="champs mdp" type="password" id="motdepasse" name="motdepasse" value="<?php echo $mdp; ?>" required>
                    <a href="inscription_pro-1.php">Mot de passe oubli&eacute; ?</a>
                    <br>
                    <input class="smallButton" type="submit" value="Se connecter" name="connexion">
                    <div>
                        <hr style="border: none; border-top: 2px solid var(--navy-blue); margin: 20px; margin-left: 0px;">
                        <button class="smallButton" onclick="window.location.href='./creation.html'">Créer un compte</button>
                    </div>
                </form>
                <script>
                    alert("Mauvais identificateur et/ou mot de passe");
                </script>
            <?php
            } else {
                if(!$isotp){
                    echo "Connexion réussie";
                    $_SESSION["identifiant"] = $identificateur;
                    $_SESSION["mdp"] = $mdp;
                }else{
                    $_SESSION["identifiant_otp"] =$identificateur;
                    $_SESSION["mdp_otp"]=$mdp;
                    echo '<script>window.location.href ="' . CONNECTION_OTP . '"</script>';
                }
            }
        }

        if (isset($_SESSION["identifiant"])) {
            if ($isotp){
                echo '<script>window.location.href ="' . CONNECTION_OTP . '"</script>';
            }
            else{
                echo '<script>window.location.href ="' . CONSULTATION_MEMBRE . '" ;
                    console.log("'. $_SESSION['identifiant'] .'");
                </script>';
            }
        } else if ($attempt == 0) { ?>
            <form action=<?php echo CONNECTION_COMPTE; ?> method="post" enctype="multipart/form-data">
                <label>Identifiant</label>
                <input class="champs" type="text" id="identificateur" name="identificateur" value="<?php echo $identificateur ?>" required  style="width: 200px;">
                <br>
                <label>Mot de passe</label>
                <input class="champs mdp" type="password" id="motdepasse" name="motdepasse" value="<?php echo $mdp ?>" required  style="width: 200px;">
                <a href="inscription_pro.php">Mot de passe oubli&eacute; ?</a>
                <br>
                <input class="smallButton" type="submit" value="Se connecter" name="connexion" style="width: 200px;">
                <div style="display: flex; justify-content: space-between; align-items: center; width: 100px">
                    <a href="./creation.html" style="text-wrap: nowrap;">Cr&eacute;er son compte</a>
                </div>
            </form>
        <?php } ?>
    </main>
</body>
<script src="../includes/main.js"></script>

</html>
