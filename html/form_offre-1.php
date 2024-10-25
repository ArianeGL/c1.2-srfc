<!doctype html>
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

    <script src="main.js"></script>

    <script src="scripts/image_preview.js"></script>
    <title>PACT - Cr&eacute;er une offre</title>
</head>

<body>
    <?php require_once "header_inc.html"; ?>
    <main>
        <h1>Cr&eacute;ation d'une offre</h1>
        <form method="post" action="form_offre_handler.inc.php" enctype="multipart/form-data" id="creation_offre">
            <input type="text" id="titre" name="titre" placeholder="Titre *" required>
            <select name="categorie" id="categorie" onchange="detect_category()">
                <option value="" disabled selected hidden>Cat&eacute;gorie *</option>
                <option value="activite">Activit&eacute;</option>
                <option value="restauration">R&eacute;stauration</option>
                <option value="visite">Visite</option>
                <option value="parc_attractions">Parc d'attractions</option>
                <option value="spectacle">Spectacle</option>
            </select>
            <br>
            <div id="depends_select"></div>

            <input type="text" name="num_addresse" id="num_addresse" placeholder="Num&eacute;ro d'addresse *" required>
            <input type="text" name="rue_addresse" id="rue_addresse" placeholder="Addresse *" required>
            <br>
            <input type="text" name="ville" id="ville" placeholder="Ville *" required>
            <input type="text" inputmode="numeric" name="code_postal" id="code_postal" placeholder="Code postal *" maxlength="5" required>
            <br>

            <input type="text" name="telephone" id="telephone" placeholder="T&eacute;l&eacute;phone">
            <input type="text" name="site_web" id="site_web" placeholder="Site Web">
            <br>

            <input type="text" name="resume" id="resume" placeholder="R&eacute;sum&eacute; *" required>
            <br>

            <input type="text" name="tags" id="tags" placeholder="Tags">
            <br>

            <script src="scripts/image_preview.js"></script>
            <img id="image_preview" src="" alt="Illustration de l'offre">
            <input type="file" id="images_offre" name="images_offre[]" multiple="multiple" accept="image/*" onchange="preview(image_preview)" required>

            <input type="submit" name="valider" value="Valider">
        </form>
    </main>
</body>

</html>
