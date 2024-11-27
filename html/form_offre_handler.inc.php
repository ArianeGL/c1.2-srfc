<?php
session_start();

//$id_compte = "Co-0007"; //test, ligne a supprimer

require_once "db_connection.inc.php";

const IMAGE_DIR = "./images_importees/";

class FunctionException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

function get_account_id()
{
    global $dbh;

    try {
        $query = "SELECT idcompte FROM " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . " WHERE email = '" . $_SESSION['identifiant'] . "';";
        $id = $dbh->query($query)->fetch();
    } catch (PDOException $e) {
        throw $e;
    }

    return $id['idcompte'];
}

/**
 * @throws FunctionException
 */
function generate_id()
{
    global $dbh;
    $id_base = "Of-";
    $count_query = "SELECT COUNT(*) FROM " . NOM_SCHEMA . "." . NOM_TABLE_OFFRE . ";";
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

function upload_carte($id)
{
    //preparer le dossier d'upload de l'image
    $destUrl = IMAGE_DIR . $id . "/carte";
    mkdir($destUrl, 0777, true);

    //recuperer le fichier a upload
    $file = $_FILES['carte'];
    $ext = explode('/', $file['type'])[1];
    $upload_name = explode('/', $file['tmp_name'])[2];
    $upload_url = $destUrl . "/" . $upload_name . '.' . $ext;

    move_uploaded_file($file['tmp_name'], $upload_url);
    return $upload_url;
}

function upload_plan($id)
{
    //preparer le dossier d'upload de l'image
    $destUrl = IMAGE_DIR . $id . "/plan";
    mkdir($destUrl, 0777, true);

    //recuperer le fichier a upload
    $file = $_FILES['plan'];
    $ext = explode('/', $file['type'])[1];
    $upload_name = explode('/', $file['tmp_name'])[2];
    $upload_url = $destUrl . "/" . $upload_name . '.' . $ext;

    move_uploaded_file($file['tmp_name'], $upload_url);
    return $upload_url;
}

function upload_images_offre($url, $id)
{
    global $dbh;
    $query = "INSERT INTO " . NOM_SCHEMA . "." . "_imageoffre(urlversimage, idoffre) VALUES (:url, :id)";
    $stmt = $dbh->prepare($query);

    $stmt->bindParam(":url", $url);
    $stmt->bindParam(":id", $id);

    $stmt->execute();
}

function bind_type_offre($stmt)
{
    if (isset($_POST['type'])) {

        switch ($_POST['type']) {
            case 'premium':
                $premium = true;
                $standard = false;
                break;
            case 'standard':
                $standard = true;
                $premium = false;
                break;
        }
        $stmt->bindParam(":estpremium", $premium, PDO::PARAM_BOOL);
        $stmt->bindParam(":eststandard", $standard, PDO::PARAM_BOOL);
    } else {
        $stmt->bindParam(":estpremium", false, PDO::PARAM_BOOL);
        $stmt->bindParam(":eststandard", false, PDO::PARAM_BOOL);
    }
}

function bind_option($stmt)
{
    if (isset($_POST['opt'])) {
        switch ($_POST['opt']) {
            case 'relief':
                $relief = true;
                $une = false;
                break;
            case 'une':
                $relief = false;
                $une = true;
                break;
            default:
                $relief = false;
                $une = false;
        }
        $stmt->bindParam(":relief", $relief, PDO::PARAM_BOOL);
        $stmt->bindParam(":une", $une, PDO::PARAM_BOOL);
    } else {
        $stmt->bindParam(":relief", false, PDO::PARAM_BOOL);
        $stmt->bindParam(":une", false, PDO::PARAM_BOOL);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['identifiant'])) {
    $id_compte = get_account_id();
    $titre = $_POST['titre'];
    $categorie = $_POST['categorie'];
    $num_addresse = $_POST['num_addresse'];
    $rue_addresse = $_POST['rue_addresse'];
    $ville = $_POST['ville'];
    $code_postal = $_POST['code_postal'];
    $resume = $_POST['resume'];
    $description = $_POST['description'];
    $telephone = $_POST['telephone'];
    $site_web = $_POST['site_web'];

    try {
        $id = generate_id();
    } catch (FunctionException $e) {
        die("ID generation failed : " . $e->getMessage());
    }

    try {
        global $dbh;

        switch ($categorie) {
            case "activite":
                $age = $_POST['age'];
                $duree = $_POST['duree'];

                $query = "INSERT INTO " . NOM_SCHEMA . "." . VUE_ACTIVITE . "(idOffre, idCompte, categorie, nomOffre, numAdresse, rueOffre, villeOffre, codePostalOffre, resume, description, estpremium, eststandard, alaune, enrelief, agerequis, dureeactivite) VALUES (:id, :id_compte, :categorie, :titre, :numAdresse, :rue, :ville, :codePost, :resume, :description, :estpremium, :eststandard, :une, :relief, :age, :duree);";
                $stmt = $dbh->prepare($query);

                $stmt->bindParam(":id", $id);
                $stmt->bindParam(":id_compte", $id_compte);
                $stmt->bindParam(":categorie", $categorie);
                $stmt->bindParam(":titre", $titre);
                $stmt->bindParam(":numAdresse", $num_addresse);
                $stmt->bindParam(":rue", $rue_addresse);
                $stmt->bindParam(":ville", $ville);
                $stmt->bindParam(":codePost", $code_postal);
                $stmt->bindParam(":resume", $resume);
                $stmt->bindParam(":description", $description);
                bind_type_offre($stmt);
                bind_option($stmt);
                $stmt->bindParam(":age", $age);
                $stmt->bindParam(":duree", $duree);

                $stmt->execute();
                $stmt = null;

                $categorie = VUE_ACTIVITE;
                break;
            case "restauration":
                $url_carte = upload_carte($id);
                $gammeprix = $_POST['gammeprix'];

                $query = "INSERT INTO " . NOM_SCHEMA . "." . VUE_RESTO . "(idOffre, idCompte, categorie, nomOffre, numAdresse, rueOffre, villeOffre, codePostalOffre, resume, description, estpremium, eststandard, alaune, enrelief, urlverscarte, gammeprix) VALUES (:id, :id_compte, :categorie, :titre, :numAdresse, :rue, :ville, :codePost, :resume, :description, :estpremium, :eststandard, :une, :relief, :url_carte, :gammeprix);";
                $stmt = $dbh->prepare($query);

                $stmt->bindParam(":id", $id);
                $stmt->bindParam(":id_compte", $id_compte);
                $stmt->bindParam(":categorie", $categorie);
                $stmt->bindParam(":titre", $titre);
                $stmt->bindParam(":numAdresse", $num_addresse);
                $stmt->bindParam(":rue", $rue_addresse);
                $stmt->bindParam(":ville", $ville);
                $stmt->bindParam(":codePost", $code_postal);
                $stmt->bindParam(":resume", $resume);
                $stmt->bindParam(":description", $description);
                bind_type_offre($stmt);
                bind_option($stmt);
                $stmt->bindParam(":url_carte", $url_carte);
                $stmt->bindParam(":gammeprix", $gammeprix);

                $stmt->execute();
                $stmt = null;

                $categorie = VUE_RESTO;
                break;
            case "visite":
                if ($_POST['guidee'] == "oui") $guidee = TRUE;
                else $guidee = FALSE;
                $duree = $_POST['duree'];

                $query = "INSERT INTO " . NOM_SCHEMA . "." . VUE_VISITE . "(idOffre, idCompte, categorie, nomOffre, numAdresse, rueOffre, villeOffre, codePostalOffre, resume, description, estpremium, eststandard, alaune, enrelief, estguidee, dureevisite) VALUES (:id, :id_compte, :categorie, :titre, :numAdresse, :rue, :ville, :codePost, :resume, :description, :estpremium, :eststandard, :une, :relief, :guidee, :dureevisite);";
                $stmt = $dbh->prepare($query);

                $stmt->bindParam(":id", $id);
                $stmt->bindParam(":id_compte", $id_compte);
                $stmt->bindParam(":categorie", $categorie);
                $stmt->bindParam(":titre", $titre);
                $stmt->bindParam(":numAdresse", $num_addresse);
                $stmt->bindParam(":rue", $rue_addresse);
                $stmt->bindParam(":ville", $ville);
                $stmt->bindParam(":codePost", $code_postal);
                $stmt->bindParam(":resume", $resume);
                $stmt->bindParam(":description", $description);
                bind_type_offre($stmt);
                bind_option($stmt);
                $stmt->bindParam(":guidee", $guidee, PDO::PARAM_BOOL);
                $stmt->bindParam(":dureevisite", $duree);

                $stmt->execute();
                $stmt = null;

                $categorie = VUE_VISITE;
                break;
            case "parc_attractions":
                $age = $_POST['age'];
                $nb_attrac = $_POST['nb_attrac'];
                $url_plan = upload_plan($id);

                $query = "INSERT INTO " . NOM_SCHEMA . "." . VUE_PARC_ATTRACTIONS . "(idOffre, idCompte, categorie, nomOffre, numAdresse, rueOffre, villeOffre, codePostalOffre, resume, description, estpremium, eststandard, alaune, enrelief, ageminparc, nbattractions, urlversplan) VALUES (:id, :id_compte, :categorie, :titre, :numAdresse, :rue, :ville, :codePost, :resume, :description, :estpremium, :eststandard, :une, :relief, :age, :nb_attrac, :url_plan);";
                $stmt = $dbh->prepare($query);

                $stmt->bindParam(":id", $id);
                $stmt->bindParam(":id_compte", $id_compte);
                $stmt->bindParam(":categorie", $categorie);
                $stmt->bindParam(":titre", $titre);
                $stmt->bindParam(":numAdresse", $num_addresse);
                $stmt->bindParam(":rue", $rue_addresse);
                $stmt->bindParam(":ville", $ville);
                $stmt->bindParam(":codePost", $code_postal);
                $stmt->bindParam(":resume", $resume);
                $stmt->bindParam(":description", $description);
                bind_type_offre($stmt);
                bind_option($stmt);
                $stmt->bindParam(":age", $age);
                $stmt->bindParam(":nb_attrac", $nb_attrac);
                $stmt->bindParam(":url_plan", $url_plan);

                $stmt->execute();
                $stmt = null;

                $categorie = VUE_PARC_ATTRACTIONS;
                break;
            case "spectacle":
                $nb_places = $_POST['nb_places'];
                $duree = $_POST['duree'];

                $query = "INSERT INTO " . NOM_SCHEMA . "." . VUE_SPECTACLE . "(idOffre, idCompte, categorie, nomOffre, numAdresse, rueOffre, villeOffre, codePostalOffre, resume, description, estpremium, eststandard, alaune, enrelief, placesspectacle, dureespectacle) VALUES (:id, :id_compte, :categorie, :titre, :numAdresse, :rue, :ville, :codePost, :resume, :description, :estpremium, :eststandard, :une, :relief, :nb_places, :duree);";

                $stmt = $dbh->prepare($query);

                $stmt->bindParam(":id", $id);
                $stmt->bindParam(":id_compte", $id_compte);
                $stmt->bindParam(":categorie", $categorie);
                $stmt->bindParam(":titre", $titre);
                $stmt->bindParam(":numAdresse", $num_addresse);
                $stmt->bindParam(":rue", $rue_addresse);
                $stmt->bindParam(":ville", $ville);
                $stmt->bindParam(":codePost", $code_postal);
                $stmt->bindParam(":resume", $resume);
                $stmt->bindParam(":description", $description);
                bind_type_offre($stmt);
                bind_option($stmt);
                $stmt->bindParam(":nb_places", $nb_places);
                $stmt->bindParam(":duree", $duree);

                $stmt->execute();
                $stmt = null;

                $categorie = VUE_SPECTACLE;
                break;

            default:
                $dbh->query("DELETE FROM " . NOM_SCHEMA . "." . NOM_TABLE_OFFRE . " WHERE idOffre='" . $id . "';"); //supprime le resultat de l'insert effectué plus tot en cas d'echec
                die("couldn't fetch the category selected by the user");
        }
        echo "data imported";

        //importe les images
        $destUrl = IMAGE_DIR . $id;
        mkdir($destUrl, 0777, true);
        $files = $_FILES['images_offre'];
        $file_count = count($files['name']);
        for ($i = 0; $i < $file_count; $i++) {
            $ext = explode('/', $files['type'][$i])[1];
            $upload_name = explode('/', $files['tmp_name'][$i])[2];
            $upload_url = $destUrl . "/" . $upload_name . '.' . $ext;
            upload_images_offre($upload_url, $id);
            move_uploaded_file($files['tmp_name'][$i], $upload_url);
        }


        //rediriger vers consultation avec les infos
        echo "<script>location.href='./informations_offre-1.php?idoffre=" . $id . "'</script>";
?>
<?php
    } catch (PDOException $e) {
        die("SQL Query failed : " . $e->getMessage());
    }
} else {
    echo "<script> location.href='./connection_pro-3.php'</script>";
    die();
}
