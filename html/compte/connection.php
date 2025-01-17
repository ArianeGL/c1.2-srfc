<?php
session_start();
require_once "../db_connection.inc.php";
require_once "../includes/consts.inc.php";

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

<?php require_once "../includes/consts.inc.php"?>

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

        if (isset($_POST["identifiant"])) {
            $identifiant = $_POST["identifiant"];
            $mdp = $_POST["motdepasse"];

            $queryCompte = "SELECT COUNT(*) 
                            FROM " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . " 
                            WHERE email = :email AND motdepasse = :mdp";
            $sthCompte = $dbh->prepare($queryCompte);
            $sthCompte->bindParam(':email', $identifiant, PDO::PARAM_STR);
            $sthCompte->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $sthCompte->execute();

            if ($sthCompte->fetchColumn() != false) {
                $connexion = true;
            }

            if (!$connexion) {
                $attempt++; ?>
                <form action=<?php echo CONNECTION_COMPTE; ?> method="post" enctype="multipart/form-data">
                    <label>Identifiant</label>
                    <input class="champs" type="text" id="identifiant" name="identifiant" value="<?php echo $identifiant ?>" required>
                    <br>
                    <label>Mot de passe</label>
                    <input class="champs mdp" type="password" id="motdepasse" name="motdepasse" value="<?php echo $mdp ?>" required>
                    <a href="inscription_pro-1.php">Mot de passe oubli&eacute; ?</a>
                    <br>
                    <input class="smallButton" type="submit" value="Se connecter" name="connexion">
                    <div>
                        <hr style="border: none; border-top: 2px solid var(--navy-blue); margin: 20px; margin-left: 0px;">
                        <button class="smallButton" onclick="window.location.href='./creation.html'">Créer un compte</button>
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

                echo '<script>window.location.href ="' . LISTE_OFFRES . '" ;</script>';
            }
        }

        if (isset($_SESSION["identifiant"])) {
            echo '<script>window.location.href ="' . LISTE_OFFRES . '" ;</script>';
        } else if ($attempt == 0) { ?>
            <form action=<?php echo CONNECTION_COMPTE; ?> method="post" enctype="multipart/form-data">
                    <label>Identifiant</label>
                    <input class="champs" type="text" id="identifiant" name="identifiant" value="<?php echo $identifiant ?>" required>
                    <br>
                    <label>Mot de passe</label>
                    <input class="champs mdp" type="password" id="motdepasse" name="motdepasse" value="<?php echo $mdp ?>" required>
                    <a href="inscription_pro.php">Mot de passe oubli&eacute; ?</a>
                    <br>
                    <input class="smallButton" type="submit" value="Se connecter" name="connexion">
                    <div>
                        <hr style="border: none; border-top: 2px solid var(--navy-blue); margin: 20px; margin-left: 0px;">
                        <button class="smallButton" onclick="window.location.href='./creation.html'">changer en txt avec <\a> pour creer compte</button>
                    </div>
                </form>
        <?php } ?>
    </main>
</body>
<script src="../includes/main.js"></script>
</html>
