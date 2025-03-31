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

    try{
        if(!isset($_POST["otp"])){
            $totp = TOTP::createFromSecret($secret);
            $code_reel = $totp->now(); 
        }
        else{
            $code_reel = $_POST['code_reel'];    
        }
    }catch(Exception $e){
        die($e);
    }        
    //  $totp->setLabel("test");
    //print_r($totp->now());
    
    $code = $_POST["otp"];



    print_r("now_code : ". $code_reel );

    

    if (isset($_POST['otp'])) {
        if($code == $code_reel){
            echo "Code valide";
            $_SESSION["identifiant"] = $_SESSION['identifiant_otp'];
            $_SESSION["mdp"] = $_SESSION["mdp_otp"];
            echo '<script> window.location = "'. LISTE_OFFRES .'" </script>'    ;
        }else{
            echo "Code invalide";
            ?>
        <form action=<?php echo CONNECTION_OTP; ?> method="post">
            <label>Identifiant</label>
            <input class="champs" type="text" id="otp" name="otp" placeholder="Code authentification à 2 facteurs" required  style="width: 200px;">
            <input type="hidden" name="secret" value="<?php echo htmlspecialchars($secret) ?>">
            <input type=hidden name="code_reel" value="<?php echo htmlspecialchars($code_reel) ?>">
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
        <input type="hidden" name="secret" value="<?php echo $secret ?>">
        <input type=hidden name="code_reel" value="<?php echo htmlspecialchars($code_reel) ?>">

        <br>
        <input class="smallButton" type="submit" value="Se connecter" name="connexion" style="width: 200px;">
    </form>
    <?php
    }    

?>