<?php
session_start();
require_once  "db_connection.inc.php";
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

class FunctionException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * @throws FunctionException
 */

if(est_membre($_SESSION["identifiant"])){
    ?> <script>
        window.location = "modification_membre-3.php";
    </script> <?php
}else if(est_prive($_SESSION["identifiant"])){
    $schemaCompte=VUE_PRO_PRIVE;
}else{
    $schemaCompte=VUE_PRO_PUBLIQUE;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //$prenom = substr(trim($_POST['prenom']), 0, 20); 
    //$nom = substr(trim($_POST['nom']), 0, 20); 
    $modif=false;
    $query = "SELECT * FROM " . NOM_SCHEMA . "." . $schemaCompte . " WHERE email = '" . $_SESSION["identifiant"] . "';";
    $row = $dbh->query($query)->fetch();

    $idcompte=$_POST['idcompte'];
    //$raison = substr(trim($_POST['raison']), 0, 40);

    $tel = $_POST['tel'];
    //$mdp = substr(trim($_POST['mdp']), 0, 20);
    $email = substr(trim($_POST['email']), 0, 50);
    $num = substr(trim($_POST['num']), 0, 4);
    $rue = substr(trim($_POST['rue']), 0, 50);
    $ville = substr(trim($_POST['ville']), 0, 30);
    $code = substr(trim($_POST['code']), 0, 5);
    //$iban = substr(trim($_POST['iban']), 0, 5);
    if($tel!=$row["telephone"] || 
        $email!=$row["email"] ||
        $num!=$row["numadressecompte"] || 
        $rue!=$row["ruecompte"] || 
        $ville!=$row["villecompte"] || 
        $code!=$row["codepostalcompte"]){
            $modif=true;
    }
    if (isset($_FILES['photo'])) {
        $user_dir = './images_importees/' . $idcompte;
        if (!file_exists($user_dir)) {
            //mkdir($user_dir, 0755, true);
        }
        $filename = $idcompte . '.png';
        $destination = $user_dir . '/' . $filename;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
            $urlimage = 'images_importees/' . $idcompte . '/' . $filename;
            $modif=true;
    }
    if(!$modif){
        ?> <script>
            alert("Aucune valeur n'a été modifiée");
        window.location = "modification_pro-3.php";
    </script> <?php
        die();
    }
}


    if (strlen($tel) > 10) {
        echo "Le numéro de téléphone doit contenir 10 chiffres.";
        exit;
    }

    if (strlen($code) !== 5) {
        echo "Le code postal doit contenir 5 chiffres.";
        exit;
    }

    //global $dbh;


    try {
        $sql_compte = "UPDATE " . NOM_SCHEMA . "." . $schemaCompte . " 
        SET telephone= :tel, 
            email= :email, 
            numadressecompte= :num, 
            ruecompte= :rue, 
            villecompte= :ville, 
            codepostalcompte= :code_postal, 
            urlimage= :urlimage
        WHERE idcompte= :idcompte;";
        $stmt_compte = $dbh->prepare($sql_compte);
        
        /*
        $stmt_compte->execute([
            ':tel' => $tel,
            ':email' => $email,
            ':num' => $num,
            ':rue' => $rue,
            ':ville' => $ville,
            ':code_postal' => $code,
            ':urlimage' => '/docker/sae/data/html/IMAGES/photoProfileDefault.png',
            ':raison' => $raison,
            ':idcompte' => $idcompte

        ]);
        */

        $stmt_compte->bindParam(':tel', $tel);
        $stmt_compte->bindParam(':email', $email);
        $stmt_compte->bindParam(':num', $num);
        $stmt_compte->bindParam(':rue', $rue);
        $stmt_compte->bindParam(':ville', $ville);
        $stmt_compte->bindParam(':code_postal', $code);
        $stmt_compte->bindParam(':urlimage', $urlimage);
        $stmt_compte->bindParam(':idcompte', $idcompte);

        $stmt_compte->execute();

        if (isset($_FILES['photo'])) {
            $user_dir = './images_importees/' . $idcompte;
            if (!file_exists($user_dir)) {
                //mkdir($user_dir, 0755, true);
            }
            $filename = $idcompte . '.png';
            $destination = $user_dir . '/' . $filename;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                $urlimage = 'images_importees/' . $idcompte . '/' . $filename;
                $_SESSION['photo'] = $urlimage;

                $update_sql = "UPDATE " . NOM_SCHEMA . "._compteprofessionel SET urlimage = :urlimage WHERE idcompte = :idcompte";
                $update_stmt = $dbh->prepare($update_sql);
                $update_stmt->execute([
                    ':urlimage' => $urlimage,
                    ':idcompte' => $idcompte
                ]);
            }
        }

        $_SESSION['identifiant'] = $email;
        
        header('Location: consultation_pro-3.php');
        die();
    } catch (PDOException $e) {
        die("SQL Query error : " . $e->getMessage());
    }



}else{
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
    
    if (est_membre($email)){
        ?> <script>
            window.location = "modification_membre-3.php";
        </script> <?php
    }
    
    if (est_prive($email)){
        $schemaCompte=VUE_PRO_PRIVE;
    }else{
        $schemaCompte=VUE_PRO_PUBLIQUE;
    }
    $queryCompte = 'SELECT * FROM ' . NOM_SCHEMA .'.'. $schemaCompte .' WHERE idcompte = :idcompte';
    $sthCompte = $dbh->prepare($queryCompte);
    $sthCompte->bindParam(':idcompte', $idCompte, PDO::PARAM_STR);
    $sthCompte->execute();
    $count = 5;
    $compte = $sthCompte->fetch(PDO::FETCH_ASSOC);
    
    if ($compte) {
        $email = $compte["email"];
        $num=$compte['numadressecompte'];
        $rue =$compte['ruecompte'];
        $ville = $compte['villecompte'];
        $codePostal = $compte['codepostalcompte'];
        $telephone = $compte['telephone'];
        $denomination = $compte['denomination'];
        $image = $compte['urlimage'];
        //$iban = $compte['iban'];
    } else {    
    ?> <script>
            window.location = "consultation_liste_offres_pro-1.php";
        </script> <?php
                }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/creation.css">
    <title>Modification compte professionnel</title>
</head>

<body>

    <?php require_once 'header_inc.php'; ?>

    <section>
        <h1>Modification du compte professionnel</h1>
        <form action="modification_pro-3.php" method="post" enctype="multipart/form-data">
            <div class="form-container">
                <div id="groupeInput" class="form-left">
                    <!--
                <div class="form-row">
                    <input type="text" class="input-creation" id="prenom" name="prenom" placeholder="Prénom *" required />
                    <input type="text" class="input-creation" id="nom" name="nom" placeholder="Nom *" required />
                </div>
                -->
                    <div class="form-row">
                        <input type="hidden" class="input-creation" id="idcompte" name="idcompte" placeholder="idcompte" value="<?php echo $idCompte ?>" required />
                    </div>
                    <div class="form-row">
                        <input type="text" class="input-creation" id="raison" name="raison" placeholder="Raison sociale *" value="<?php echo $denomination ?>" readonly />
                        <input type="text" class="input-creation" id="tel" name="tel" placeholder="Téléphone *" value="<?php echo $telephone ?>" required />
                    </div>
                    <div class="form-row">
                        <input type="email" class="input-creation" id="email" name="email" placeholder="Adresse mail *" value="<?php echo $email ?>" required />
                    </div>
                    <div class="form-row">
                        <input type="text" class="input-creation" id="num" name="num" placeholder="Num *" value="<?php echo $num ?>" required />
                        <input type="text" class="input-creation" id="rue" name="rue" placeholder="Rue *" value="<?php echo $rue ?>" required />
                    </div>
                    <div class="form-row">
                        <input type="text" class="input-creation" id="ville" name="ville" placeholder="Ville *" value="<?php echo $ville ?>" required />
                        <input type="text" class="input-creation" id="code" name="code" placeholder="Code postal *" value="<?php echo $codePostal ?>" required />
                    </div>
                </div>

                <div class="actions-profil">
                    <div id="photo-profil" class="form-right">
                        <?php if (isset($_SESSION['photo'])) { ?>
                            <img src="<?php echo htmlspecialchars($_SESSION['photo']); ?>" alt="Photo de profil" />
                        <?php } else { ?>
                            <img src="IMAGES/photoProfileDefault.png" alt="Photo de profil" id="photo-profil" />
                        <?php } ?>
                    </div>
                <label class="bouton" for="photo">Importer une image</label>
                <input type="file" id="photo" name="photo" style="display:none;" />
                </br>
                <input class="bouton" id="bouton-modifier" type="submit" value="Valider" />
                <button class="bouton" id="bouton-mdp" type="button" onclick="window.location.href='modification_mdp_pro-3.php'">Modifier mot de passe</button>
                </div>
            </div>
        </form>

    </section>
    <?php require_once "footer_inc.html"; ?>

</body>
<script src="main.js"></script>

</html>
