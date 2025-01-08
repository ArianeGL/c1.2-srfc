<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./styles/form_offre.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

    <script src="../includes/main.js"></script>

    <script src="../scripts/image_preview.js"></script>
    <title>PACT - Cr&eacute;er une offre</title>
</head>

<body>
    <?php
    require_once "verif_connection.inc.php";
    require_once "../includes/consts.inc.php";

    require_once HEADER;

    session_start();

    /*
     * prend en parametre 'email d'un compte
     * retourne true si le compte est privé
     * retourne false sinon
     */
    function est_prive($email)
    {
        $ret = false;
        global $dbh;

        $query = "SELECT * FROM " . NOM_SCHEMA . "." . VUE_PRO_PRIVE . " WHERE email = '" . $email . "';";
        $row = $dbh->query($query)->fetch();

        if (isset($row['iban'])) $ret = true;

        return $ret;
    }

    // retourne dans un array touts les tags de restauration possbiles
    function get_tags_resto(): array
    {
        global $dbh;
        $tags = array();

        $query = "SELECT * FROM " . NOM_SCHEMA . "." . NOM_TABLE_TAGSRE . ";";
        $tags = $dbh->query($query)->fetchAll(PDO::FETCH_COLUMN, 0);

        return $tags;
    }

    if (isset($_SESSION['identifiant']) && valid_account()) {
    ?>
        <main>
            <h1>Cr&eacute;ation d'une offre</h1>
            <form method="post" action="../includes/form_offre_handler.inc.php" enctype="multipart/form-data" id="creation_offre">
                <div id="titre_cate">
                    <input type="text" id="titre" name="titre" placeholder="Titre *" required>
                    <?php if (est_prive($_SESSION['identifiant'])) { ?>
                        <select name="type" id="type">
                            <option value="" disabled selected hidden>Type d'offre *</option>
                            <option value="standard">Standard</option>
                            <option value="premium">Premium</option>
                        </select>
                    <?php } ?>
                </div>
                <select name="categorie" id="categorie" onchange='detect_category(<?php echo json_encode(get_tags_resto()); ?>)'>
                    <option value="" disabled selected hidden class>Cat&eacute;gorie *</option>
                    <option value="activite">Activit&eacute;</option>
                    <option value="restauration">Restauration</option>
                    <option value="visite">Visite</option>
                    <option value="parc_attractions">Parc d'attractions</option>
                    <option value="spectacle">Spectacle</option>
                </select>

                <!-- div contiendra les champs de formulaire qui dependent de la categorie selectionné au dessus-->
                <div id="depends_select"></div>

                <div id="addresse">
                    <input type="text" name="num_addresse" id="num_addresse" placeholder="Num&eacute;ro d'addresse *" required>
                    <input type="text" name="rue_addresse" id="rue_addresse" placeholder="Addresse *" required>
                </div>
                <div id="ville_code">
                    <input type="text" inputmode="numeric" name="code_postal" id="code_postal" placeholder="Code postal *" maxlength="5" required>
                    <input type="text" name="ville" id="ville" placeholder="Ville *" required>
                </div>

                <div id="tel_site">
                    <input type="text" name="telephone" id="telephone" placeholder="T&eacute;l&eacute;phone" maxlength="10">
                    <input type="text" name="site_web" id="site_web" placeholder="Site Web">
                </div>

                <input type="text" name="resume" id="resume" placeholder="R&eacute;sum&eacute; *" required>

                <textarea name="description" id="description" placeholder="Description *" required form="creation_offre"></textarea>

                <?php if (est_prive($_SESSION['identifiant'])) { ?>
                    <select name="opt" id="opt">
                        <option value="no_opt" disabled selected hidden class>Options
                        </option>
                        <option value="no_opt">Pas d'option
                        </option>
                        <option value="relief">En relief
                        </option>
                        <option value="une">&Agrave; la une
                        </option>

                    </select>
                <?php } ?>

                <input type="text" name="tags" id="tags" placeholder="Tags">

                <script src="image_preview.js"></script>
                <img id="image_preview" src="" alt="">
                <div class="boutonimages">
                    <p>Importer vos images</p>
                    <label for="images_offre" class="smallButton">Importer</label>
                    <input type="file" id="images_offre" name="images_offre[]" multiple="multiple" accept="image/*" onchange="preview(image_preview)" required>

                    <input type="submit" name="valider" value="Valider" class="bigButton" id="valider">
                </div>
            </form>
        </main>
    <?php
    } else echo "<script> window.location.href='./connection_pro.php'</script>";
    ?>
    <?php require_once FOOTER; ?>
</body>

</html>
