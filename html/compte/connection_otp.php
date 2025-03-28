<?php
    session_start();
    require_once("../db_connection.inc.php");
    require_once("../includes/consts.inc.php");
    require '../vendor/autoload.php';
    use OTPHP\TOTP;
    
    $query = "SELECT urlotp FROM ". NOM_SCHEMA .".". NOM_TABLE_COMPTE ."
    WHERE email = :id and motdepasse = :mdp";
    
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id', $_SESSION['identifiant']);
    $stmt->bindParam('mdp', $_SESSION['mdp']);
    $stmt->execute();
    
    $secret = $stmt->fetch(PDO::FETCH_ASSOC)['urlotp'];

    $totp = TOTP::create($secret);
    print_r($totp->now());
    
    print_r($_POST);
    if (isset($_POST['otp'])) {
        if($totp->verify($_POST['otp'])){
            echo "Code valide";
            $_SESSION["identifiant"] = $identificateur;
            $_SESSION["mdp"] = $mdp;
            echo '<script> window.location = "'. LISTE_OFFRES .'" </script>'    ;
        }else{
            echo "Code invalide";
            ?>
        <form action=<?php echo CONNECTION_OTP; ?> method="post" enctype="multipart/form-data">
            <label>Identifiant</label>
            <input class="champs" type="text" id="otp" name="otp" placeholder="Code authentification à 2 facteurs" required  style="width: 200px;">
            <input type="hidden" name="secret" value=<?php echo $secret ?>
            <br>
            <input class="smallButton" type="submit" value="Se connecter" name="connexion" style="width: 200px;">
        </form>
        <?php
        }
        
    } else {
        ?>
    <form action=<?php echo CONNECTION_OTP; ?> method="post" enctype="multipart/form-data">
        <label>Identifiant</label>
        <input class="champs" type="text" id="otp" name="otp" placeholder="Code authentification à 2 facteurs" required  style="width: 200px;">
        <input type="hidden" name="secret" value=<?php echo $secret ?>
        <br>
        <input class="smallButton" type="submit" value="Se connecter" name="connexion" style="width: 200px;">
    </form>
    <?php
    }    

?>