<?php
session_start();
require_once "../db_connection.inc.php";
require_once "../includes/verif_connection.inc.php";

require_once "../includes/consts.inc.php";

print_r($_POST);

if (isset($_SESSION['identifiant']) && valid_account()) {
    if($_POST["otp"] == "on"){
        echo "<script>window.location.href='creation_otp.php'</script>";
    }else{
        die();
        echo "<script>window.location.href='./consultation_pro.php'</script>";
    }
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
function generate_id()
{
    global $dbh;
    $id_base = "Co-";
    $count_query = "SELECT COUNT(*) FROM " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . ";";
    try {
        $count = $dbh->query($count_query)->fetchColumn(); // recupere le nombre d'offre deja existante
    } catch (PDOException $e) {
        die("SQL Query in generate_id() failed : " . $e->getMessage());
    }
    $count++;

    switch (strlen((string)$count)) {
        case 1:
            return $id_base . "000" . strval($count);
            break;
        case 2:
            return $id_base . "00" . strval($count);
            break;
        case 3:
            return $id_base . "0" . strval($count);
            break;
        case 4:
            return $id_base . strval($count);
            break;
        default:
            throw new FunctionException("Couldn't generate id");
    }
}



try {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $raison = substr(trim($_POST['raison']), 0, 40);
        $tel = $_POST['tel'];
        $mdp = substr(trim($_POST['mdp']), 0, 20);
        $email = substr(trim($_POST['email']), 0, 50);
        $num = substr(trim($_POST['num']), 0, 4);
        $rue = substr(trim($_POST['rue']), 0, 50);
        $ville = substr(trim($_POST['ville']), 0, 30);
        $code = substr(trim($_POST['code']), 0, 5);
        $rib = substr(trim($_POST['rib']), 0, 5);


        if (strlen($tel) > 10) {
            echo "Le numéro de téléphone doit contenir 10 chiffres.";
            exit;
        }

        if (strlen($code) !== 5) {
            echo "Le code postal doit contenir 5 chiffres.";
            exit;
        }

        global $dbh;

        try {
            $idcompte = generate_id();
        } catch (FunctionException $e) {
            die("ID generation failed : " . $e->getMessage());
        }
        if(!isset($_SESSION['identifiant'])){
            try {
                if ($rib != null) {
                    $schemaCompte = VUE_PRO_PRIVE;
                } else {
                    $schemaCompte = VUE_PRO_PUBLIQUE;
                }
                $sql_compte = "INSERT INTO " . NOM_SCHEMA . "." . $schemaCompte . "(idcompte, telephone, motdepasse, email, numadressecompte, ruecompte, villecompte, codepostalcompte, urlimage, denomination) 
                VALUES (:idcompte, :tel, :mdp, :email, :num, :rue, :ville, :code_postal, :urlimage, :raison)";
                $stmt_compte = $dbh->prepare($sql_compte);
                $stmt_compte->execute([
                    ':idcompte' => $idcompte,
                    ':tel' => $tel,
                    ':mdp' => $mdp,
                    ':email' => $email,
                    ':num' => $num,
                    ':rue' => $rue,
                    ':ville' => $ville,
                    ':code_postal' => $code,
                    ':urlimage' => PHOTO_PROFILE_DEFAULT,
                    ':raison' => $raison

                ]);

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
            
            if($_POST["otp"] == "on"){
                echo "<script>window.location.href='creation_otp.php'</script>";
            }else{
            die();
            echo "<script>window.location.href='./consultation_pro.php'</script>";
            }
            exit();
        } catch (PDOException $e) {
            die("SQL Query error : " . $e->getMessage());
        }
        $_SESSION['identifiant'] = $email;
    }
}
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr" id="creation_compte">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../includes/style.css">
    <title>Création compte professionnel - PACT</title>
</head>

<body>

    <?php include HEADER; ?>

    <main>
    <section>
        <h1>Création du compte professionnel</h1>
        <form action="creation_pro.php" method="post" enctype="multipart/form-data">
            <div class="form-container">
                <div id="groupeInput" class="form-left">
                    <div class="form-row">
                        <input type="text" class="input-creation" id="raison" name="raison" placeholder="Raison sociale *" required />
                        <input type="text" class="input-creation" id="tel" name="tel" placeholder="Téléphone *" required />
                    </div>
                    <div class="form-row">
                        <input type="password" class="input-creation" id="mdp" name="mdp" placeholder="Mot de passe *" required />
                        <input type="password" class="input-creation" id="confmdp" name="confmdp" placeholder="Confirmation mdp *" required />
                    </div>
                    <div class="form-row">
                        <input type="email" class="input-creation" id="email" name="email" placeholder="Adresse mail *" required />
                    </div>
                    <div class="form-row">
                        <input type="text" class="input-creation" id="num" name="num" placeholder="Num *" required />
                        <input type="text" class="input-creation" id="rue" name="rue" placeholder="Rue *" required />
                    </div>
                    <div class="form-row">
                        <input type="text" class="input-creation" id="ville" name="ville" placeholder="Ville *" required />
                        <input type="text" class="input-creation" id="code" name="code" placeholder="Code postal *" required />
                    </div>

                    <div class="form-row">
                        <input type="text" class="input-creation" id="rib" name="rib" placeholder="RIB" />
                    </div>
                    <div class="form-row">
                            <label class="bouton-info" for="communication">Activer l'authentification à deux facteurs</label>
                            <input type="checkbox" class="input-creation" id="otp" name="otp" />
                    </div>
                </div>
                <div id="form-photo">
                    <div id="photo-profil" class="form-right">
                        <?php if (isset($_SESSION['photo'])) { ?>
                            <img src="<?php echo htmlspecialchars($_SESSION['photo']); ?>" alt="Photo de profil" />
                        <?php } else { ?>
                            <img src=<?php echo PHOTO_PROFILE_DEFAULT?> alt="Photo de profil" id="photo-profil" />
                        <?php } ?>
                    </div>
                    <label class="smallButton" for="photo">Importer une image</label>
                    <input type="file" id="photo" name="photo" style="display:none;" />
                </div>
            </div>


            <div id="form-footer">
                <p id="obligation">* Obligatoire</p>
                <input class="button" id="bouton-modifier" type="submit" value="Valider" />
                <div id="accepte">
                    <div class="position-checkbox">
                        <input type="checkbox" value="communication" id="communication">
                        <label class="bouton-info" for="communication">J'accepte de recevoir des communications commerciales</label>
                    </div>
                    <br />
                    <div class="position-checkbox">
                        <input type="checkbox" value="condition" id="condition" required>
                        <label class="bouton-info" for="condition">J'accepte les conditions générales d’utilisation</label>
                    </div>
                </div>
            </div>
        </form>
    </section>
    </main>
    <?php require_once FOOTER; ?>

</body>
<script src="../includes/main.js"></script>
</html>
