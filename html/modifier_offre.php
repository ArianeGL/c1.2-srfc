<?php
require_once("db_connection.inc.php");
$idoffre = $_GET["idoffre"];
global $dbh;

if (isset($_POST["titre"])) {
    $post_titre = $_POST['titre'];
    $post_numadresse = $_POST['numadresse'];
    $post_rueoffre = $_POST['rueoffre'];
    $post_ville = $_POST['ville'];
    $post_code_postal = $_POST['code_postal'];
    $post_resume = $_POST['resume'];
    $post_idoffre = $_POST['idoffre'];

    $query_envoie = "UPDATE " . NOM_SCHEMA . "._offre
                    SET nomoffre = :post_titre,
                        numadresse = :post_numadresse,
                        rueoffre = :post_rueoffre,
                        villeoffre = :post_ville,
                        codepostaloffre = :post_code_postal,
                        resume = :post_resume 
                        WHERE idoffre = '" . $post_idoffre . "';";
    $stmt_envoie = $dbh->prepare($query_envoie);
    $stmt_envoie->bindParam(':post_titre', $post_titre);
    $stmt_envoie->bindParam(':post_numadresse', $post_numadresse);
    $stmt_envoie->bindParam(':post_rueoffre', $post_rueoffre);
    $stmt_envoie->bindParam(':post_ville', $post_ville);
    $stmt_envoie->bindParam(':post_code_postal', $post_code_postal);
    $stmt_envoie->bindParam(':post_resume', $post_resume);
    $stmt_envoie->execute();


    header('Status: 301 Moved Permanently', false, 301);
    header('Location: informations_offre-1.php?idoffre=' . $post_idoffre);
    exit();
} else {



    /*$query_email = "SELECT email FROM ".NOM_SCHEMA."_compte 
                    INNER JOIN sae._offre ON _compte.idcompte = _offre.idoffre
                    WHERE idoffre = '$idoffre''";
    $stmt_email = $dbh->prepare($query_email);
    $email = $stmt_email->execute();
    */
    $query_titre = "SELECT nomoffre FROM " . NOM_SCHEMA . "._offre
                    WHERE idoffre = '$idoffre'";
    $stmt_titre = $dbh->prepare($query_titre);
    $stmt_titre->execute();
    $titre = $stmt_titre->fetch(PDO::FETCH_ASSOC)["nomoffre"];

    /*$query_tags = "SELECT tags FROM ".NOM_SCHEMA."_offre
                    INNER JOIN _tag ON _offre.id = _tag.idoffre
                    WHERE idoffre = '$idoffre'";
    $stmt_tags = $dbh->prepare($query_tags);
    $stmt_tags->execute();
    $tags = $stmt_tags->fetch(PDO::FETCH_ASSOC)["nomtags"];*/

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


    /*$query_telephone = "SELECT telephone FROM ".NOM_SCHEMA."_offre
                        INNER JOIN _compte ON _offre.idcompte = _compte.idcompte
                        WHERE idoffre = '$idoffre'";
    $stmt_telephone = $dbh->prepare($query_telephone);
    $stmt_telephone->execute();
    $telephone = $stmt_telephone->fetch(PDO::FETCH_ASSOC)["telephone"];*/

    /*$query_denomination = "SELECT denomination FROM ".NOM_SCHEMA."_offre
                        INNER JOIN _compteprofessionnel ON _offre.idcompte = _compteprofessionel.idcompte
                        WHERE idoffre = '$idoffre''";
    $stmt_denomination = $dbh->prepare($query_denomintion);
    $denomination = $stmt_denomination->execute();*/

    /*$query_IBAN = "SELECT IBAN FROM ".NOM_SCHEMA."_offre
                    INNER JOIN _professionnelprive ON _offre.idcompte = _professionelprive.idcompte
                    WHERE idoffre = '$idoffre''";
    $stmt_IBAN = $dbh->prepare($query_IBAN);
    $IBAN = $stmt_IBAN->execute();*/

    $query_resume = "SELECT resume FROM " . NOM_SCHEMA . "._offre
                    WHERE _offre.idoffre = '$idoffre'";
    $stmt_resume = $dbh->prepare($query_resume);
    $stmt_resume->execute();
    $resume = $stmt_resume->fetch(PDO::FETCH_ASSOC)["resume"];

    /*
    $query_description = "SELECT description FROM ".NOM_SCHEMA."_offre
                    WHERE _offre.idoffre = '$idoffre'";
    $stmt_description = $dbh->prepare($query_description);
    $stmt_description->execute();
    $description = $stmt_description->fetch(PDO::FETCH_ASSOC)["description"];
    */

    /*$query_image = "SELECT urlversimage FROM ".NOM_SCHEMA."_offre
                    INNER JOIN _imageoffre ON _offre.idoffre = _imageoffre.idoffre
                    WHERE _offre.idoffre = '$idoffre'";
    $stmt_image = $dbh->prepare($query_image);
    $stmt_image->execute();
    $image = $stmt_image->fetch(PDO::FETCH_ASSOC);//["urlversimage"];
    print_r($image);*/
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
        <script src="scripts/image_preview.js"></script>
    </head>

    <body>
        <?php require_once 'header_inc.html'; ?>
        <!-- Main content -->
        <main id="top">
            <h1>Modifier une offre</h1>
            <form action="modifier_offre.php" method="POST" enctype="multipart/form-data" id="modifier_offre">
                <div class="element_form row-form">
                    <label for="titre">Titre : </label>
                    <input name="titre" type="text" id="titre" value="<?php echo $titre ?>">
                    <label for="catégorie">Catégorie : </label>
                    <select name="categorie" id="categorie">
                        <option value="activite" <?php //if(!strcmp($select_categorie,"activite")) echo "selected"
                                                    ?>>Activité</option>
                        <option value="restauration" <?php //if(!strcmp($select_categorie,"restauration")) echo "selected"
                                                        ?>>Réstauration</option>
                        <option value="visite" <?php //if(!strcmp($select_categorie,"visite")) echo "selected"
                                                ?>>Visite</option>
                        <option value="parc_attractions" <?php //if(!strcmp($select_categorie,"parc_attraction")) echo "selected"
                                                            ?>>Parc d'attractions</option>
                        <option value="spectacle" <?php //if(!strcmp($select_categorie,"spectacle")) echo "selected"
                                                    ?>>Spectacle</option>
                    </select>
                </div>
                <!--<textarea name="tags" id="tags"><?php echo $tags ?></textarea>-->
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
                <div class="element_form">

                </div>
                <textarea name="resume" id="resume"><?php echo $resume ?></textarea>
                <div class="boutonimages">
                    <p>Importer une Grille Tarifaire, un Menu et/ou un Plan</p>
                    <label for="fichier" class="smallButton">Importer</label>
                    <input type="file" id="fichier" name="fichier" >
                    <input type="hidden" name="idoffre" value="<?php echo $idoffre; ?>">
                </div>
                <div id="divVal">
                    <button type="submit" id="bnVal" class="bigButton">Valider</button>
                </div>
            </form>
        </main>
        <?php require_once "footer_inc.html"; ?>

    </body>
    <script src="main.js"></script>

    </html>
<?php
}
?>
