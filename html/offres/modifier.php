<?php
require_once "../includes/consts.inc.php";
session_start();
require_once("../db_connection.inc.php");
$idoffre = $_GET["idoffre"];
global $dbh;
const IMAGE_DIR = "../images_importee/";

function get_categorie($id)
{
    global $dbh;

    $query = "SELECT categorie FROM " . NOM_SCHEMA . "." . NOM_TABLE_OFFRE . " WHERE idoffre = '" . $id . "';";
    $categorie = $dbh->query($query)->fetch(PDO::FETCH_ASSOC)['categorie'];

    switch ($categorie) {
        case "Activite":
            return VUE_ACTIVITE;
            break;
        case "Restauration":
            return VUE_RESTO;
            break;
        case "Parc attraction":
            return VUE_PARC_ATTRACTIONS;
            break;
        case "Visite":
            return VUE_VISITE;
            break;
        case "Spectacle":
            return VUE_SPECTACLE;
            break;
    }
}

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
        <input type="text" name="age" placeholder="Age requis (tout public par defaut)" value="<?php echo $age; ?>">
        <input type="text" name="duree" required placeholder="Durée de l'activité *" value="<?php echo $duree; ?>">
    <?php
    } else if ($categorie == "Restauration") {
        $tags = get_tags_resto();

        //fetching values
        $query = "SELECT gammeprix FROM " . NOM_SCHEMA . "." . VUE_RESTO . " WHERE idoffre = '" . $id . "';";
        $gamme = $dbh->query($query)->fetch(PDO::FETCH_ASSOC)["gammeprix"];

        $query = "SELECT nomtag FROM " . NOM_SCHEMA . "." . VUE_TAGS_RE . " WHERE idoffre = '" . $id . "';";
        $tagre = $dbh->query($query)->fetch(PDO::FETCH_ASSOC)["nomtag"];
    ?>
        <input type="text" name="gammeprix" maxlength="3" required placeholder="Gamme de prix * (€ ou €€ ou €€€)" value="<?php echo $gamme ?>">

        <select name="tagre" id="tagre" required>
            <?php
            foreach ($tags as $tag) { ?>
                <option value="<?php echo $tag; ?>" <?php if ($tag = $tagre) echo "selected"; ?>><?php echo $tag; ?></option>
            <?php
            }
            ?>
        </select>
    <?php
    } else if ($categorie == "Visite") {
        //fetching values
        $query = "SELECT estguidee FROM " . NOM_SCHEMA . "." . VUE_VISITE . " WHERE idoffre = '" . $id . "';";
        $guidee = $dbh->query($query)->fetch(PDO::FETCH_ASSOC)["estguidee"];

        $query = "SELECT dureevisite FROM " . NOM_SCHEMA . "." . VUE_VISITE . " WHERE idoffre = '" . $id . "';";
        $duree = $dbh->query($query)->fetch(PDO::FETCH_ASSOC)["dureevisite"];
    ?>
        <fieldset id="guidee">
            <legend>La visite est elle guidée</legend>

            <input type="radio" name="guidee" value="oui" <?php if ($guidee) echo "checked"; ?>>
            <label>Oui</label>

            <input type="radio" name="guidee" value="non" <?php if (!$guidee) echo "checked"; ?>>
            <label>Non</label>
        </fieldset>

        <input type="text" placeholder="Durée de la visite *" name="duree" required value="<?php echo $duree; ?>">
    <?php
    } else if ($categorie == "Parc attraction") {
        //fetching values
        $query = "SELECT ageminparc FROM " . NOM_SCHEMA . "." . VUE_PARC_ATTRACTIONS . " WHERE idoffre = '" . $id . "';";
        $age = $dbh->query($query)->fetch(PDO::FETCH_ASSOC)["ageminparc"];

        $query = "SELECT nbattractions FROM " . NOM_SCHEMA . "." . VUE_PARC_ATTRACTIONS . " WHERE idoffre = '" . $id . "';";
        $nb_attrac = $dbh->query($query)->fetch(PDO::FETCH_ASSOC)["nbattractions"];
    ?>
        <input type=" text" name="age" placeholder="Age requis (tout public par defaut)" value="<?php echo $age; ?>">
        <input type="number" placeholder="Nombre d'attractions *" name="nb_attrac" value="<?php echo $nb_attrac; ?>">
    <?php
    } else if ($categorie == "Spectacle") {
        //fetching values
        $query = "SELECT placesspectacle FROM " . NOM_SCHEMA . "." . VUE_SPECTACLE . " WHERE idoffre = '" . $id . "';";
        $places = $dbh->query($query)->fetch(PDO::FETCH_ASSOC)["placesspectacle"];
        $query = "SELECT dureespectacle FROM " . NOM_SCHEMA . "." . VUE_SPECTACLE . " WHERE idoffre = '" . $id . "';";
        $duree = $dbh->query($query)->fetch(PDO::FETCH_ASSOC)["dureespectacle"];
    ?>
        <input type="text" placeholder="Nombre de places *" name="nb_places" required value="<?php echo $places; ?>">
        <input type="text" placeholder="Durée du spectacle *" name="duree" required value="<?php echo $duree; ?>">
    <?php
    }
}

function update_depend_cate($categorie, $id)
{
    global $dbh;

    if ($categorie == VUE_ACTIVITE) {
        $query = "UPDATE " . NOM_SCHEMA . "." . VUE_ACTIVITE . " SET agerequis = :age, dureeactivite = :duree WHERE idoffre = :id;";
        $stmt = $dbh->prepare($query);
        $age = $_POST['age'];
        $duree = $_POST['duree'];
        $stmt->bindParam(":age", $age);
        $stmt->bindParam(":duree", $duree);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $stmt = null;
    } else if ($categorie == VUE_RESTO) {
        $tags = get_tags_resto();
        $query = "UPDATE " . NOM_SCHEMA . "." . VUE_RESTO . " SET gammeprix = :gamme WHERE idoffre = :id;";
        $stmt = $dbh->prepare($query);
        $gamme = $_POST['gammeprix'];
        $stmt->bindParam(":gamme", $gamme);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $stmt = null;

        $tag = $_POST['tagre'];
        $query = "UPDATE " . NOM_SCHEMA . "." . VUE_TAGS_RE . " SET nomtag = :tag WHERE idoffre = :id;";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":tag", $tag);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $stmt = null;
    } else if ($categorie == VUE_SPECTACLE) {
        $query = "UPDATE " . NOM_SCHEMA . "." . VUE_SPECTACLE . " SET placesspectacle = :nb_places, dureespectacle = :duree WHERE idoffre = :id;";
        $stmt = $dbh->prepare($query);
        $nb_places = $_POST['nb_places'];
        $duree = $_POST['duree'];
        $stmt->bindParam(":nb_places", $nb_places);
        $stmt->bindParam(":duree", $duree);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $stmt = null;
    } else if ($categorie == VUE_VISITE) {
        $query = "UPDATE " . NOM_SCHEMA . "." . VUE_VISITE . " SET estguidee = :guidee, dureevisite = :duree WHERE idoffre = :id;";
        $stmt = $dbh->prepare($query);
        $guidee = $_POST['guidee'] == "oui";
        $duree = $_POST['duree'];
        $stmt->bindParam(":guidee", $guidee, PDO::PARAM_BOOL);
        $stmt->bindParam(":duree", $duree);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $stmt = null;
    } else if ($categorie == VUE_PARC_ATTRACTIONS) {
        $query = "UPDATE " . NOM_SCHEMA . "." . VUE_PARC_ATTRACTIONS . " SET nbattractions = :nb, ageminparc = :age WHERE idoffre = :id;";
        $stmt = $dbh->prepare($query);
        $nb = $_POST['nb_attrac'];
        $age = $_POST['age'];
        $stmt->bindParam(":nb", $nb);
        $stmt->bindParam(":age", $age);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $stmt = null;
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
                if (explode("/", $upload_url)[2] != ".") {
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

            if ($post_datedebut == "") {
                $post_datedebut = NULL;
            }

            if ($post_datefin == "") {
                $post_datefin = NULL;
            }

            if (get_categorie($idoffre) == VUE_RESTO) {
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

            if (get_categorie($idoffre) == VUE_RESTO) {
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

        try {

            $query_envoie = "UPDATE " . NOM_SCHEMA . "." . get_categorie($idoffre) . "
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
            update_depend_cate(get_categorie($idoffre), $idoffre);
        } catch (PDOException $e) {
            die($e);
        }




        header('Status: 301 Moved Permanently', false, 301);
        header('Location: liste.php?idoffre=' . $post_idoffre);
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
        <html lang="en" id="modification_offre">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="./includes/style.css">

            <title>Modifer votre offre - PACT</title>
            <script src="../scripts/image_preview.js"></script>
        </head>

        <body>
            <?php require_once HEADER; ?>
            <!-- Main content -->
            <main id="top">
                <h1>Modifier une offre</h1>
                <form action="modifier.php?idoffre=<?php echo $idoffre ?>" method="POST" enctype="multipart/form-data" id="modifier_offre">
                    <div class="element_form row-form">

                        <label for="titre">Titre : </label>
                        <input name="titre" type="text" id="titre" value="<?php echo $titre ?>">


                        <label for="catégorie">Catégorie : </label>
                        <select name="categorie" id="categorie" disabled>
                            <option value="activite" <?php if (!strcmp($categorie, "Activite")) echo "selected" ?>>Activité</option>
                            <option value="restauration" <?php if (!strcmp($categorie, "Restauration")) echo "selected" ?>>Restauration</option>
                            <option value="visite" <?php if (!strcmp($categorie, "Visite")) echo "selected" ?>>Visite</option>
                            <option value="parc_attractions" <?php if (!strcmp($categorie, "Parc attraction")) echo "selected" ?>>Parc d'attractions</option>
                            <option value="spectacle" <?php if (!strcmp($categorie, "Spectacle")) echo "selected" ?>>Spectacle</option>
                        </select>

                    </div>
                    <div id="depends_select" class="element_form row_form">
                        <?php depends_category($categorie, $idoffre); ?>
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
            <?php require_once FOOTER; ?>

        </body>
        <script src="../includes/main.js"></script>

        </html>
<?php
    }
} else {
    echo ("vous n'avez pas le droit de modifier cette page");
}
?>
