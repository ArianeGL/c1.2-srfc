<?php
    session_start();
    require_once("../db_connection.inc.php");
    require_once("../includes/consts.inc.php");
    require '../vendor/autoload.php';
    use OTPHP\TOTP;

    try{
    $query = "SELECT urlotp FROM ". NOM_SCHEMA .".". NOM_TABLE_COMPTE ."
    WHERE email = :id and motdepasse = :mdp";
    
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id', $_SESSION['identifiant_otp']);
    $stmt->bindParam(':mdp', $_SESSION['mdp_otp']);
    $stmt->execute();
    
    $secret = $stmt->fetch(PDO::FETCH_ASSOC)["urlotp"];
    //$secret = $stmt->fetchall();
    }catch(PDOException $e){
        die($e);
    }

    print_r("secret : ".$secret. "</br>");
    try{
        $totp = TOTP::createFromSecret($secret);
    }catch(Exception $e){
        die($e);
    }        
    //  $totp->setLabel("test");
    print_r($totp->now());
    /*
    
    //$uri = $totp->getProvisioningUri(); 
    //$qrcode_url = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($uri) . "&size=200x200";
    //echo "<img src='$qrcode_url' alt='QR Code'></br>";
    }catch(Exception $e){
        die($e);
    }*/
    

    print_r($_SESSION);
    echo "</br>";
    print_r($_POST["otp"]);
    $code = $_POST["otp"];
    print_r("code : ". $code);
    if (isset($_POST['otp'])) {
        if($totp->verify($code)){
            echo "Code valide";
            $_SESSION["identifiant"] = $identificateur;
            $_SESSION["mdp"] = $mdp;
            echo '<script> window.location = "'. LISTE_OFFRES .'" </script>'    ;
        }else{
            echo "Code invalide";
            ?>
        <form action=<?php echo CONNECTION_OTP; ?> method="post">
            <label>Identifiant</label>
            <input class="champs" type="text" id="otp" name="otp" placeholder="Code authentification à 2 facteurs" required  style="width: 200px;">
            <input type="hidden" name="secret" value="<?php echo htmlspecialchars($secret) ?>">
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