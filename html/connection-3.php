<?php /*
session_unset();
session_destroy();*/
session_start();
require_once "db_connection.inc.php";
// POUR LES TESTS: [email] => agnes.pinson@gmail.com [motdepasse] => motdepasse
//$query1 = "SELECT email, motdepasse FROM " . NOM_SCHEMA . "._compteProfessionnel NATURAL JOIN " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . ";"; //deprecated
//
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
    <title>PACT - Se Connecter</title>
</head>

<body>
    <main>
        <?php
        global $dbh;

        $connexion = false;
        $attempt = 0;

        if (isset($_POST["identifiant"])) {
            $identifiant = $_POST["identifiant"];
            $mdp = $_POST["motdepasse"];
            //$mdp_crypte = md5($mdp);

            $queryCompte = "SELECT COUNT(*) FROM " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . " WHERE email = :email AND motdepasse = :mdp";
            $sthCompte = $dbh->prepare($queryCompte);
            $sthCompte->bindParam(':email', $identifiant, PDO::PARAM_STR);
            $sthCompte->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $sthCompte->execute();

            if ($sthCompte->fetchColumn() != false) {
                $connexion = true;
            }


            if (!$connexion) {
                $attempt++; ?>
                <form action="connection-3.php" method="post" enctype="multipart/form-data">
                    <label>Identifiant</label>
                    <input class="champs" type="text" id="identifiant" name="identifiant" value="<?php echo $identifiant ?>" required>
                    <br>
                    <label>Mot de passe</label>
                    <input class="champs mdp" type="password" id="motdepasse" name="motdepasse" value="<?php echo $mdp ?>" required />
                    <a href="inscription_pro-1.php">Mot de passe oubli&eacute; ?</a>
                    <br>
                    <div>
                        <button class="smallButton" onclick="window.location.href='./creation_compte.html'">Créer un compte</button>
                        <input class="smallButton" type="submit" value="Se connecter" name="connexion">
                    </div>
                </form>
                <script>
                    alert("Mauvais identifiant et/ou mot de passe");
                </script>
            <?php
            } else {
                echo "Connexion réussie";
                $_SESSION["identifiant"] = $identifiant;
                $_SESSION["mdp"] = $mdp;

                if (est_pro()) {
                    echo '<script>window.location = "consulter_liste_offres_cli-1.php;</script>';
                } else {
                    echo '<script>window.location = "consulter_liste_offres_cli-1.php;</script>';
                }
            }
        }

        if (isset($_SESSION["identifiant"])) {
            echo '<script>window.location = "consulter_liste_offres_cli-1.php";</script>';
        } else if ($attempt == 0) { ?>
            <form action="connection-3.php" method="post" enctype="multipart/form-data">
                <label>Identifiant</label>
                <input class="champs" type="text" id="identifiant" name="identifiant" value="<?php echo $identifiant ?>" required>
                <br>
                <label>Mot de passe</label>
                <input class="champs mdp" type="password" id="motdepasse" name="motdepasse" value="<?php echo $mdp ?>" required />
                <a href="inscription_pro-1.php">Mot de passe oubli&eacute; ?</a>

                <br>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <button class="smallButton" onclick="window.location.href='./creation_compte.html'">Créer un compte</button>
                    <input class="smallButton" type="submit" value="Se connecter" name="connexion">
                </div>
            </form>
        <?php } ?>

    </main>
</body>
<script src="main.js"></script>

</html>
