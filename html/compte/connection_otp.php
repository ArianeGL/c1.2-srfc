<?php
    session_start();
    require_once("../db_connection.inc.php");
    require_once("../includes/consts.inc.php");
    require '../vendor/autoload.php';
    use OTPHP\TOTP;
    
    
    print_r($_POST);
    if (isset($_POST['otp'])) {
        $totp = TOTP::create($post["secret"]);
        if($totp->verify($_POST['otp'])){
            echo "Code valide";
            echo "<script> window.location.href(". LISTE_OFFRES .") </script>";
        }else{
            echo "Code invalide";
            ?>
        <form action=<?php echo CONNECTION_OTP; ?> method="post" enctype="multipart/form-data">
            <label>Identifiant</label>
            <input class="champs" type="text" id="otp" name="otp" placeholder="Code authentification à 2 facteurs" required  style="width: 200px;">
            <br>
            <input class="smallButton" type="submit" value="Se connecter" name="connexion" style="width: 200px;">
        </form>
        <?php
        }
        
    } else {
        $totp = TOTP::create($post["secret"]);
        $query = "select urlotp from ". NOM_SCHEMA .".". NOM_TABLE_COMPTE ."
        where email = :email";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':email',$_SESSION['identifiant']);
        $stmt->execute();
        
        $secret = $stmt->fetch(PDO::FETCH_ASSOC)["urlotp"];
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