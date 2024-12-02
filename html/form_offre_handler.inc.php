<?php
session_start();

//$id_compte = "Co-0007"; //test, ligne a supprimer

require_once "db_connection.inc.php";

const IMAGE_DIR = "./images_importees/";
const DAY_TO_SEC = 86400;
const NB_SEM_OPTION = 4;

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
    try {
        if (isset($_POST['type'])) {

            switch ($_POST['type']) {
                case 'premium':
                    $abo = "Premium";
                    break;
                case 'standard':
                    $abo = "Standard";
                    break;
            }
        } else $abo = "Gratuit";
        $stmt->bindParam(":abo", $abo);
    } catch (PDOException $e) {
        die("SQL query error : " . $e->getMessage());
    }
}

function bind_option($id)
{
    if (isset($_POST['opt'])) {
        global $dbh;
        try {
            $query = "INSERT INTO " . NOM_SCHEMA . "." . NOM_TABLE_SOUSCRI_OPTION . "(idoffre, option, nbsemaine, semainelancement) VALUES(:id, :option, :nbsem, :startsem)";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam("id", $id);

            switch ($_POST['opt']) {
                case 'relief':
                    $option = "En relief";
                    break;
                case 'une':
                    $option = "A la une";
                    break;
                default:
                    $stmt = null;
            }

            if ($stmt != null) {
                $stmt->bindParam(":option", $option);
                $today = getdate();

                if ($today['weekday'] != "Monday") {
                    $startsem = $today[0] + ((7 - $today[0]) + 1) * DAY_TO_SEC; //date du lundi suivant le jour actuel
                } else $startsem = $today[0];

                $nb_sem = NB_SEM_OPTION;
                $stmt->bindParam(":nbsem", $nb_sem, PDO::PARAM_INT);
                $startsem = date("Y-m-d", $startsem);
                $stmt->bindparam(":startsem", $startsem);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            die("SQL query error : " . $e->getMessage());
        }
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

                $query = "INSERT INTO " . NOM_SCHEMA . "." . VUE_ACTIVITE . "(idOffre, idCompte, categorie, nomOffre, numAdresse, rueOffre, villeOffre, codePostalOffre, resume, description, abonnement,  agerequis, dureeactivite) VALUES (:id, :id_compte, :categorie, :titre, :numAdresse, :rue, :ville, :codePost, :resume, :description, :abo,  :age, :duree);";
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
                $stmt->bindParam(":age", $age);
                $stmt->bindParam(":duree", $duree);

                $stmt->execute();
                bind_option($id);
                $stmt = null;

                $categorie = VUE_ACTIVITE;
                break;
            case "restauration":
                $url_carte = upload_carte($id);
                $gammeprix = $_POST['gammeprix'];

                $query = "INSERT INTO " . NOM_SCHEMA . "." . VUE_RESTO . "(idOffre, idCompte, categorie, nomOffre, numAdresse, rueOffre, villeOffre, codePostalOffre, resume, description, abonnement,  urlverscarte, gammeprix) VALUES (:id, :id_compte, :categorie, :titre, :numAdresse, :rue, :ville, :codePost, :resume, :description, :abo,  :url_carte, :gammeprix);";
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
                $stmt->bindParam(":url_carte", $url_carte);
                $stmt->bindParam(":gammeprix", $gammeprix);

                $stmt->execute();
                bind_option($id);
                $stmt = null;

                $categorie = VUE_RESTO;
                break;
            case "visite":
                if ($_POST['guidee'] == "oui") $guidee = TRUE;
                else $guidee = FALSE;
                $duree = $_POST['duree'];

                $query = "INSERT INTO " . NOM_SCHEMA . "." . VUE_VISITE . "(idOffre, idCompte, categorie, nomOffre, numAdresse, rueOffre, villeOffre, codePostalOffre, resume, description, abonnement,  estguidee, dureevisite) VALUES (:id, :id_compte, :categorie, :titre, :numAdresse, :rue, :ville, :codePost, :resume, :description, :abo,  :guidee, :dureevisite);";
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
                $stmt->bindParam(":guidee", $guidee, PDO::PARAM_BOOL);
                $stmt->bindParam(":dureevisite", $duree);

                $stmt->execute();
                bind_option($id);
                $stmt = null;

                $categorie = VUE_VISITE;
                break;
            case "parc_attractions":
                $age = $_POST['age'];
                $nb_attrac = $_POST['nb_attrac'];
                $url_plan = upload_plan($id);

                $query = "INSERT INTO " . NOM_SCHEMA . "." . VUE_PARC_ATTRACTIONS . "(idOffre, idCompte, categorie, nomOffre, numAdresse, rueOffre, villeOffre, codePostalOffre, resume, description, abonnement,  ageminparc, nbattractions, urlversplan) VALUES (:id, :id_compte, :categorie, :titre, :numAdresse, :rue, :ville, :codePost, :resume, :description, :abo,  :age, :nb_attrac, :url_plan);";
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
                $stmt->bindParam(":age", $age);
                $stmt->bindParam(":nb_attrac", $nb_attrac);
                $stmt->bindParam(":url_plan", $url_plan);

                $stmt->execute();
                bind_option($id);
                $stmt = null;

                $categorie = VUE_PARC_ATTRACTIONS;
                break;
            case "spectacle":
                $nb_places = $_POST['nb_places'];
                $duree = $_POST['duree'];


                $query = "INSERT INTO " . NOM_SCHEMA . "." . VUE_SPECTACLE . "(idOffre, idCompte, categorie, nomOffre, numAdresse, rueOffre, villeOffre, codePostalOffre, resume, description, abonnement,  placesspectacle, dureespectacle) VALUES (:id, :id_compte, :categorie, :titre, :numAdresse, :rue, :ville, :codePost, :resume, :description, :abo,  :nb_places, :duree);";

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
                $stmt->bindParam(":nb_places", $nb_places);
                $stmt->bindParam(":duree", $duree);

                $stmt->execute();
                bind_option($id);
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
