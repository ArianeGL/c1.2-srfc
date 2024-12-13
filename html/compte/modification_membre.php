<?php
session_start();
require_once "../db_connection.inc.php";
require_once "../includes/verif_connection.inc.php";

require_once "../includes/consts.inc.php";

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
    $schemaCompte=VUE_MEMBRE;
}else{
    ?> <script>
        window.location = "modification_pro-3.php";
    </script> <?php
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modif=false;
    $query = "SELECT * FROM " . NOM_SCHEMA . "." . $schemaCompte . " WHERE email = '" . $_SESSION["identifiant"] . "';";
    $row = $dbh->query($query)->fetch();
    
    $prenom = substr(trim($_POST['prenom']), 0, 20); 
    $nom = substr(trim($_POST['nom']), 0, 20); 
    $pseudo = substr(trim($_POST['pseudo']), 0, 20); 
    $idcompte=$_POST['idcompte'];
    $tel = $_POST['tel'];
    $email = substr(trim($_POST['email']), 0, 50);
    $num = substr(trim($_POST['num']), 0, 4);
    $rue = substr(trim($_POST['rue']), 0, 50);
    $ville = substr(trim($_POST['ville']), 0, 30);
    $code = substr(trim($_POST['code']), 0, 5);
    if( $pseudo!=$row["pseudo"] || 
        $prenom!=$row["prenommembre"] || 
        $nom!=$row["nommembre"] || 
        $tel!=$row["telephone"] || 
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
            mkdir($user_dir, 0755, true);
        }
        $filename = $idcompte . '.png';
        $destination = $user_dir . '/' . $filename;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
            $urlimage = 'images_importees/' . $idcompte . '/' . $filename;
            $modif=true;
        }
        if(!$modif){
            ?>
            <script>
                alert("Aucune valeur n'a été modifiée");
                window.location.href = "modification_membre.php";
            </script> 
            <?php
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

    try {
        $sql_compte = "UPDATE " . NOM_SCHEMA . "." . $schemaCompte . " 
        SET telephone= :tel, 
            email= :email, 
            numadressecompte= :num, 
            ruecompte= :rue, 
            villecompte= :ville, 
            codepostalcompte= :code_postal, 
            urlimage= :urlimage,
            nommembre= :nom,
            prenommembre= :prenom,
            pseudo= :pseudo
        WHERE idcompte= :idcompte;";
        $stmt_compte = $dbh->prepare($sql_compte);

        $stmt_compte->bindParam(':tel', $tel);
        $stmt_compte->bindParam(':email', $email);
        $stmt_compte->bindParam(':num', $num);
        $stmt_compte->bindParam(':rue', $rue);
        $stmt_compte->bindParam(':ville', $ville);
        $stmt_compte->bindParam(':code_postal', $code);
        $stmt_compte->bindParam(':urlimage', $urlimage);
        $stmt_compte->bindParam(':nom', $nom);
        $stmt_compte->bindParam(':prenom', $prenom);
        $stmt_compte->bindParam(':pseudo', $pseudo);
        $stmt_compte->bindParam(':idcompte', $idcompte);

        $stmt_compte->execute();

        if (isset($_FILES['photo'])) {
            $user_dir = './images_importees/' . $idcompte;
            if (!file_exists($user_dir)) {
                mkdir($user_dir, 0755, true);
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
        ?>
        <script>
            window.location.href = "consultation_membre.php";
        </script> 
        <?php
        die();
    } catch (PDOException $e) {
        die("SQL Query error : " . $e->getMessage());
    }

} else {
    if (isset($_SESSION['identifiant']) && valid_account()) {
        $email = $_SESSION['identifiant'];
        $requeteCompte = $dbh->prepare("SELECT idcompte, email FROM sae._compte WHERE email = '" . $email."';"); //, PDO::FETCH_ASSOC
        $requeteCompte->execute();
        $idCompte = $requeteCompte->fetch(PDO::FETCH_ASSOC)["idcompte"];
    
    } 
    else {
        ?>
        <script>
            window.location = "connection_membre.php";
        </script>
        <?php
    }
    
    if (!est_membre($email)){
        ?> 
        <script>
            window.location = "modification_pro.php";
        </script> 
        <?php
    }

    $queryCompte = 'SELECT * FROM ' . NOM_SCHEMA .'.'. $schemaCompte .' WHERE idcompte = :idcompte';
    $sthCompte = $dbh->prepare($queryCompte);
    $sthCompte->bindParam(':idcompte', $idCompte, PDO::PARAM_STR);
    $sthCompte->execute();
    $count = 5;
    $compte = $sthCompte->fetch(PDO::FETCH_ASSOC);
    
    if ($compte) {
        $prenom = $compte["prenommembre"];
        $nom = $compte["nommembre"];
        $email = $compte["email"];
        $num=$compte['numadressecompte'];
        $rue =$compte['ruecompte'];
        $ville = $compte['villecompte'];
        $codePostal = $compte['codepostalcompte'];
        $telephone = $compte['telephone'];
        $pseudo = $compte['pseudo'];
        $image = $compte['urlimage'];
    } else {    
    ?> 
    <script>
        window.location = "consultation_liste_offres_cli-1.php";
    </script>
    <?php
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/creation.css">
    <title>Modification de votre compte - PACT</title>
</head>

<body>

    <?php require_once HEADER; ?>

    <section>
        <h1>Modification de votre Compte</h1>
        <form action="modification_membre.php" method="post" enctype="multipart/form-data">
            <div class="form-container">
                <div id="groupeInput" class="form-left">
                    <section class="form-row">
                        <input type="text" class="input-creation" id="prenom" name="prenom" placeholder="Prénom *" value="<?php echo $prenom ?>" required />
                        <input type="text" class="input-creation" id="nom" name="nom" placeholder="Nom *" value="<?php echo $nom ?>" required />
                    </section>
                    <section class="form-row">
                        <input type="hidden" class="input-creation" id="idcompte" name="idcompte" placeholder="idcompte" value="<?php echo $idCompte ?>" required />
                    </section>
                    <section class="form-row">
                    <input type="text" class="input-creation" id="pseudo" name="pseudo" placeholder="Pseudo *" value="<?php echo $pseudo ?>" required />
                        <input type="text" class="input-creation" id="tel" name="tel" placeholder="Téléphone *" value="<?php echo $telephone ?>" required />
                    </section>
                    <section class="form-row">
                        <input type="email" class="input-creation" id="email" name="email" placeholder="Adresse mail *" value="<?php echo $email ?>" required />
                    </section>
                    <section class="form-row">
                        <input type="text" class="input-creation" id="num" name="num" placeholder="Num *" value="<?php echo $num ?>" required />
                        <input type="text" class="input-creation" id="rue" name="rue" placeholder="Rue *" value="<?php echo $rue ?>" required />
                    </section>
                    <section class="form-row">
                        <input type="text" class="input-creation" id="ville" name="ville" placeholder="Ville *" value="<?php echo $ville ?>" required />
                        <input type="text" class="input-creation" id="code" name="code" placeholder="Code postal *" value="<?php echo $codePostal ?>" required />
                    </section>
                </div>

                <div class="actions-profil">
                    <div id="photo-profil" class="form-right">
                        <?php if (isset($_SESSION['photo'])) { ?>
                            <img src="<?php echo htmlspecialchars($_SESSION['photo']); ?>" alt="Photo de profil" />
                        <?php } else { ?>
                            <img src=<?php echo PHOTO_PROFILE_DEFAULT?> alt="Photo de profil" id="photo-profil" />
                        <?php } ?>
                    </div>
                <label class="bouton" for="photo">Importer une image</label>
                <input type="file" id="photo" name="photo" style="display:none;" />
                </br>
                <input class="bouton" id="bouton-modifier" type="submit" value="Valider" />
                <button class="bouton" id="bouton-mdp" type="button" onclick="window.location.href='modification_mdp_membre.php'">Modifier mot de passe</button>
                </div>
            </div>
        </form>

    </section>
    <?php require_once FOOTER; ?>

</body>
<script src="../includes/main.js"></script>
</html>
