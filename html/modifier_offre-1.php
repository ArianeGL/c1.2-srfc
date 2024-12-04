<?php
session_start();
require_once("db_connection.inc.php");
$idoffre = $_GET["idoffre"];
global $dbh;
const IMAGE_DIR = "images_importee/";

function get_tags_resto(): array
{
    global $dbh;
    $tags = array();

    $query = "SELECT * FROM " . NOM_SCHEMA . "." . NOM_TABLE_TAGSRE . ";";
    $tags = $dbh->query($query)->fetchAll(PDO::FETCH_COLUMN, 0);

    return $tags;
}

function depends_category($categorie, $id)
{
    global $dbh;

    if ($categorie == "Activite") {
        //fetching values
        $query = "SELECT agerequis FROM " . NOM_SCHEMA . "." . VUE_ACTIVITE . " WHERE idoffre = '" . $id . "';";
        $age = $dbh->query($query)->fetch(PDO::FETCH_ASSOC)["agerequis"];

        $query = "SELECT dureeactivite FROM " . NOM_SCHEMA . "." . VUE_ACTIVITE . " WHERE idoffre = '" . $id . "';";
        $duree = $dbh->query($query)->fetch(PDO::FETCH_ASSOC)["dureeactivite"];

?>
        <input type="text" name="age" placeholder="Age requis (tout public par defaut)">
        <input type="text" name="duree" required placeholder="Durée de l'activité *">
    <?php
    } else if ($categorie == "restauration") {
        $tags = get_tags_resto();
    ?>
        <input type="text" name="gammeprix" maxlength="3" required placeholder="Gamme de prix * (€ ou €€ ou €€€)">

        <select name="tagre" id="tagre" required>
            <?php
            foreach ($tags as $tag) { ?>
                <option value="<?php echo $tag; ?>"><?php echo $tag; ?></option>
            <?php
            }
            ?>
        </select>

        <img alt="" src="" id="carte_preview">
        <label for="carte" class="smallButton">Votre carte</label>
        <input type="file" name="carte" id="carte" accept="image/*" required onchange="preview(carte_preview)">
    <?php
    } else if ($categorie == "visite") {
    ?>
        <fieldset id="guidee">
            <legend>La visite est elle guidée</legend>

            <input type="radio" name="guidee" value="oui">
            <label>Oui</label>

            <input type="radio" name="guidee" value="non">
            <label>Non</label>
        </fieldset>

        <input type="text" placeholder="Durée de la visite *" name="duree" required>
    <?php
    } else if ($categorie == "parc_attractions") {
    ?>
        <input type="text" name="age" placeholder="Age requis (tout public par defaut)">
        <input type="number" placeholder="Nombre d'attractions *" name="nb_attrac">


        <img alt="" src="" id="plan_preview">
        <label for="plan" class="smallButton">Plan du parc</label>
        <input type="file" name="plan" id="plan" accept="image/*" required onchange="preview(plan_preview)">
    <?php
    } else if ($categorie == "spectacle") {
    ?>
        <input type="text" placeholder="Nombre de places *" name="nb_places *" required>
        <input type="text" placeholder="Durée du spectacle *" name="duree" required>
    <?php
    }
}

function upload_images_offre($url, $id)
{
    global $dbh;
    $query = "INSERT INTO " . NOM_SCHEMA . "." . "_imageoffre(urlimage, idoffre) VALUES (:url, :id)";
    $stmt = $dbh->prepare($query);

    $stmt->bindParam(":url", $url);
    $stmt->bindParam(":id", $id);

    $stmt->execute();
}

function offre_appartient($compte): bool
{
    global $dbh;
    $appartient = false;
    $idOffre = $_GET["idoffre"];

    try {
        $query_compte = "SELECT email FROM " . NOM_SCHEMA . "." . NOM_TABLE_OFFRE . "
                INNER JOIN " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . " on " . NOM_TABLE_COMPTE . ".idcompte = " . NOM_TABLE_OFFRE . ".idcompte
                WHERE " . NOM_TABLE_OFFRE . ".idoffre = :idoffre;";
        $stmt_compte = $dbh->prepare($query_compte);
        $stmt_compte->bindParam(':idoffre', $idOffre);
        $stmt_compte->execute();
        $res = $stmt_compte->fetch(PDO::FETCH_ASSOC)["email"];
        if ($res == $compte) $appartient = true;
    } catch (PDOException $e) {
        die("SQL Query error : " . $e->getMessage());
    }

    return $appartient;
}

if (offre_appartient($_SESSION['identifiant'])) {
    if (isset($_POST["titre"])) {
        try {
            $query_image = "SELECT urlimage FROM " . NOM_SCHEMA . "._offre
                    INNER JOIN _imageoffre ON _offre.idoffre = _imageoffre.idoffre
                    WHERE _offre.idoffre = '$idoffre'";
            $stmt_image = $dbh->prepare($query_image);
            $stmt_image->execute();
            $url_image = $stmt_image->fetchall();

            foreach ($url_image as $value) {
                if ($_POST["supprimer" . str_replace(".", "_", $value[0])] == "oui") {
                    $query_delete_image = "DELETE FROM " . NOM_SCHEMA . "._imageoffre
                WHERE urlimage = :url ;";
                    $stmt_delete_image = $dbh->prepare($query_delete_image);
                    $stmt_delete_image->bindParam(':url', $value[0]);
                    $stmt_delete_image->execute();
                }
            }

            $destUrl = IMAGE_DIR . $idoffre;
            mkdir($destUrl, 0777, true);
            $files = $_FILES['images_offre'];
            $file_count = count($files['name']);
            for ($i = 0; $i < $file_count; $i++) {
                $ext = explode('/', $files['type'][$i])[1];
                $upload_name = explode('/', $files['tmp_name'][$i])[sizeof(explode('/', $files['tmp_name'][$i])) - 1];
                $upload_url = $destUrl . "/" . $upload_name . '.' . $ext;
                if ($upload_url != "images_importee\Of-0002\.") {
                    upload_images_offre($upload_url, $idoffre);
                    move_uploaded_file($files['tmp_name'][$i], $upload_url);
                }
            }


            $string_tags = $_POST["tags"];
            $post_tags = explode(",", $string_tags);


            $post_titre = $_POST['titre'];
            $post_numadresse = $_POST['numadresse'];
            $post_rueoffre = $_POST['rueoffre'];
            $post_ville = $_POST['ville'];
            $post_code_postal = $_POST['code_postal'];
            $post_resume = $_POST['resume'];
            $post_idoffre = $_POST['idoffre'];
            $post_prixmin = $_POST['prixmin'];
            $post_datedebut = $_POST['datedebut'];
            $post_datefin = $_POST['datefin'];
            $post_categorie = $_POST['categorie'];

            if ($post_datedebut == "") {
                $post_datedebut = NULL;
            }

            if ($post_datefin == "") {
                $post_datefin = NULL;
            }

            if ($post_categorie == "restauration") {
                $query_delete_tags = "DELETE FROM " . NOM_SCHEMA . "._tagpourrestauration
                            WHERE idoffre = '" . $post_idoffre . "';";
                $stmt_delete_tags = $dbh->prepare($query_delete_tags);
                $stmt_delete_tags->execute();
            } else {
                $query_delete_tags = "DELETE FROM " . NOM_SCHEMA . "._tagpouroffre
                            WHERE idoffre = '" . $post_idoffre . "';";
                $stmt_delete_tags = $dbh->prepare($query_delete_tags);
                $stmt_delete_tags->execute();
            }

            if ($post_categorie == "restauration") {
                foreach ($post_tags as $value) {
                    if (trim($value) != "") {
                        $query_envoie_tags = "INSERT INTO " . NOM_SCHEMA . ".tagre (nomtag,idoffre)
                                VALUES(:nomtag, :idoffre);";
                        $stmt_envoie_tags = $dbh->prepare($query_envoie_tags);
                        $stmt_envoie_tags->bindParam(':nomtag', $value);
                        $stmt_envoie_tags->bindParam(':idoffre', $post_idoffre);
                        $stmt_envoie_tags->execute();
                    }
                }
            } else {
                foreach ($post_tags as $key => $value) {
                    if (trim($value) != "") {
                        $query_envoie_tags = "INSERT INTO " . NOM_SCHEMA . ".tagof(nomtag,idoffre)
                                VALUES(:nomtag, :idoffre);";
                        $stmt_envoie_tags = $dbh->prepare($query_envoie_tags);
                        $stmt_envoie_tags->bindParam(':nomtag', $value);
                        $stmt_envoie_tags->bindParam(':idoffre', $post_idoffre);
                        $stmt_envoie_tags->execute();
                    }
                }
            }
        } catch (PDOException $e) {
            print_r($e);
            die();
        }
        //print_r($_POST["categorie"]."|");

        switch ($_POST["categorie"]) {
            case "activite":
                $table = "activite";
                break;
            case "visite":
                $table = "visite";
                break;
            case "parc_attraction";
                $table = "parcattraction";
                break;
            case "spectacle":
                $table = "spectacle";
                break;
            case "restauration":
                $table = "restauration";
                break;
            default:
                echo ("erreur sur la catégorie");
        }

        try {

            $query_envoie = "UPDATE " . NOM_SCHEMA . "." . $table . "
                    SET nomoffre = :post_titre,
                        numadresse = :post_numadresse,
                        rueoffre = :post_rueoffre,
                        villeoffre = :post_ville,
                        codepostaloffre = :post_code_postal,
                        resume = :post_resume,
                        prixmin = :post_prixmin,
                        datedebut = :post_datedebut,
                        datefin = :post_datefin
                        WHERE idoffre = '" . $post_idoffre . "';";
            $stmt_envoie = $dbh->prepare($query_envoie);
            $stmt_envoie->bindParam(':post_titre', $post_titre);
            $stmt_envoie->bindParam(':post_numadresse', $post_numadresse);
            $stmt_envoie->bindParam(':post_rueoffre', $post_rueoffre);
            $stmt_envoie->bindParam(':post_ville', $post_ville);
            $stmt_envoie->bindParam(':post_code_postal', $post_code_postal);
            $stmt_envoie->bindParam(':post_resume', $post_resume);
            $stmt_envoie->bindParam(':post_prixmin', $post_prixmin);
            $stmt_envoie->bindparam(':post_datedebut', $post_datedebut);
            $stmt_envoie->bindparam(':post_datefin', $post_datefin);
            $stmt_envoie->execute();
        } catch (PDOException $e) {
            print_r($e);
            die();
        }




        header('Status: 301 Moved Permanently', false, 301);
        header('Location: informations_offre-1.php?idoffre=' . $post_idoffre);
        exit();
    } else {

        try {


            $query_titre = "SELECT nomoffre FROM " . NOM_SCHEMA . "._offre
        WHERE idoffre = '$idoffre'";
            $stmt_titre = $dbh->prepare($query_titre);
            $stmt_titre->execute();
            $titre = $stmt_titre->fetch(PDO::FETCH_ASSOC)["nomoffre"];

            $query_tags = "SELECT nomtag FROM " . NOM_SCHEMA . ".tagof
        WHERE idoffre = '$idoffre'";
            $stmt_tags = $dbh->prepare($query_tags);
            $stmt_tags->execute();
            $tags = $stmt_tags->fetchall();

            if ($tags == "") {
                print_r("idoffre = $idoffre ;   pas de tags");
            }

            $query_categorie = "SELECT categorie FROM " . NOM_SCHEMA . "._offre
    WHERE idoffre = '$idoffre'";
            $stmt_categorie = $dbh->prepare($query_categorie);
            $stmt_categorie->execute();
            $categorie = $stmt_categorie->fetch(PDO::FETCH_ASSOC)["categorie"];

            $query_numadresse = "SELECT numadresse FROM " . NOM_SCHEMA . "._offre
    WHERE idoffre = '$idoffre'";
            $stmt_numadresse = $dbh->prepare($query_numadresse);
            $stmt_numadresse->execute();
            $numadresse = $stmt_numadresse->fetch(PDO::FETCH_ASSOC)["numadresse"];


            $query_rueoffre = "SELECT rueoffre FROM " . NOM_SCHEMA . "._offre
    WHERE idoffre = '$idoffre'";
            $stmt_rueoffre = $dbh->prepare($query_rueoffre);
            $stmt_rueoffre->execute();
            $rueoffre = $stmt_rueoffre->fetch(PDO::FETCH_ASSOC)["rueoffre"];


            $query_ville = "SELECT villeoffre FROM " . NOM_SCHEMA . "._offre
    WHERE idoffre = '$idoffre'";
            $stmt_ville = $dbh->prepare($query_ville);
            $stmt_ville->execute();
            $ville = $stmt_ville->fetch(PDO::FETCH_ASSOC)["villeoffre"];


            $query_codepostal = "SELECT codepostaloffre FROM " . NOM_SCHEMA . "._offre
    WHERE idoffre = '$idoffre'";
            $stmt_codepostal = $dbh->prepare($query_codepostal);
            $stmt_codepostal->execute();
            $codepostal = $stmt_codepostal->fetch(PDO::FETCH_ASSOC)["codepostaloffre"];

            $query_resume = "SELECT resume FROM " . NOM_SCHEMA . "._offre
    WHERE _offre.idoffre = '$idoffre'";
            $stmt_resume = $dbh->prepare($query_resume);
            $stmt_resume->execute();
            $resume = $stmt_resume->fetch(PDO::FETCH_ASSOC)["resume"];

            $query_description = "SELECT description FROM " . NOM_SCHEMA . "._offre
    WHERE _offre.idoffre = '$idoffre'";
            $stmt_description = $dbh->prepare($query_description);
            $stmt_description->execute();
            $description = $stmt_description->fetch(PDO::FETCH_ASSOC)["description"];

            $query_prixmin = "SELECT prixmin FROM " . NOM_SCHEMA . "._offre
    WHERE _offre.idoffre = '$idoffre'";
            $stmt_prixmin = $dbh->prepare($query_prixmin);
            $stmt_prixmin->execute();
            $prixmin = $stmt_prixmin->fetch(PDO::FETCH_ASSOC)["prixmin"];

            $query_datedebut = "SELECT datedebut FROM " . NOM_SCHEMA . "._offre
    WHERE _offre.idoffre = '$idoffre'";
            $stmt_datedebut = $dbh->prepare($query_datedebut);
            $stmt_datedebut->execute();
            $datedebut = $stmt_datedebut->fetch(PDO::FETCH_ASSOC)["datedebut"];

            $query_datefin = "SELECT datefin FROM " . NOM_SCHEMA . "._offre
    WHERE _offre.idoffre = '$idoffre'";
            $stmt_datefin = $dbh->prepare($query_datefin);
            $stmt_datefin->execute();
            $datefin = $stmt_datefin->fetch(PDO::FETCH_ASSOC)["datefin"];


            $query_image = "SELECT urlimage FROM " . NOM_SCHEMA . "._offre
                    INNER JOIN _imageoffre ON _offre.idoffre = _imageoffre.idoffre
                    WHERE _offre.idoffre = '$idoffre'";
            $stmt_image = $dbh->prepare($query_image);
            $stmt_image->execute();
            $url_image = $stmt_image->fetchall();

            /*foreach($url_image as $value){
        print_r($value[0]);
    }
    die();*/
        } catch (PDOException $e) {
            print_r($e);
            die();
        }
    ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="./styles/style_modifier_offre.css">

            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

            <title>PACT</title>
            <script src="./image_preview.js"></script>
        </head>

        <body>
            <?php require_once 'header_inc.html'; ?>
            <!-- Main content -->
            <main id="top">
                <h1>Modifier une offre</h1>
                <form action="modifier_offre-1.php?idoffre=<?php echo $idoffre ?>" method="POST" enctype="multipart/form-data" id="modifier_offre">
                    <div class="element_form row-form">

                        <label for="titre">Titre : </label>
                        <input name="titre" type="text" id="titre" value="<?php echo $titre ?>">


                        <label for="catégorie">Catégorie : </label>
                        <select name="categorie" id="categorie" disabled>
                            <option value="activite" <?php if (!strcmp($categorie, "activite")) echo "selected" ?>>Activité</option>
                            <option value="restauration" <?php if (!strcmp($categorie, "restauration")) echo "selected" ?>>Restauration</option>
                            <option value="visite" <?php if (!strcmp($categorie, "visite")) echo "selected" ?>>Visite</option>
                            <option value="parc_attractions" <?php if (!strcmp($categorie, "parc_attraction")) echo "selected" ?>>Parc d'attractions</option>
                            <option value="spectacle" <?php if (!strcmp($categorie, "spectacle")) echo "selected" ?>>Spectacle</option>
                        </select>

                    </div>
                    <div id="depends_select">
                        <?php depends_category($categorie); ?>
                    </div>

                    </div>
                    <!--<textarea name="tags" id="tags"><?php //echo $tags 
                                                        ?></textarea>-->
                    <div class="element_form row-form">

                        <label for="code_postal">Code postal : </label>
                        <input name="code_postal" type="numeric" id="code_postal" value="<?php echo $codepostal ?>" required maxlenght="5">


                        <label for="ville">Ville : </label>
                        <input name="ville" type="text" id="ville" value="<?php echo $ville ?>">

                    </div>
                    <div class="element_form row-form">

                        <label for="numadresse">Numéro de rue : </label>
                        <input name="numadresse" type="text" id="adresse" value="<?php echo $numadresse ?>">


                        <label for="rueoffre">Rue : </label>
                        <input name="rueoffre" type="text" id="rueoffre" value="<?php echo $rueoffre ?>">

                    </div>
                    <div class="element_form row-form">

                        <label for="prixmin">prix minimal : </label>
                        <input type="numeral" id="prixmin" name="prixmin" value="<?php echo $prixmin ?>" required>

                    </div>
                    <div class="element_form row-form">

                        <label for="datedebut">Date de debut</label>
                        <input type="date" id="datedebut" name="datedebut" value="<?php echo $datedebut ?>">


                        <label for="datefin">Date de fin</label>
                        <input type="date" id="datefin" name="datefin" value="<?php echo $datefin ?>">

                    </div>
                    <div class="element_form row_form"
                        <label for="resume">Resumé</label>
                        <input type="text" name="resume" id="resume" value="<?php echo $resume ?>">
                    </div>

                    <label for="description">Description</label>
                    <textarea name="description" id="description"><?php echo $description; ?></textarea>

                    <div class="element_form row_form"
                        <label for="tags">Tags</label>
                        <input type="text" name="tags" id="tags" value=" <?php foreach ($tags as $key => $value) echo $value[0] . ","; ?>">
                    </div>


                    <div class="boutonimages">
                        <label for="images_offre">Importer des images</label>
                        <input type="file" id="images_offre" name="images_offre[]" multiple>
                        <input type="hidden" name="idoffre" value="<?php echo $idoffre; ?>">
                    </div>

                    <table>
                        <tr>
                            <td>Photo</td>
                            <td>Supprimer</td>
                        </tr>
                        <?php foreach ($url_image as $value) { ?>
                            <tr>
                                <td><img src="<?php echo $value[0] ?>" alt="<?php echo $value[0] ?>"></td>
                                <td>
                                    <input type="radio" id="Oui" name="supprimer<?php echo $value[0] ?>" value="oui" />
                                    <label for="supprimer<?php echo $value[0] ?>">Oui</label>
                                    <input type="radio" id="Non" name="supprimer<?php echo $value[0] ?>" value="non" checked />
                                    <label for="supprimer<?php echo $value[0] ?>">Non</label>

                                </td>
                            </tr>
                        <?php }; ?>
                    </table>

                    <div id="divVal">
                        <button type="submit" id="bnVal">Valider</button>
                    </div>
                </form>
            </main>
            <?php require_once "footer_inc.html"; ?>

        </body>
        <script src="main.js"></script>

        </html>
<?php
    }
} else {
    echo ("vous n'avez pas le droit de modifier cette page");
}
?>
