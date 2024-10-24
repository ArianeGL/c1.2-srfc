<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PACT - Cr&eacute;er une offre</title>
    <script src="Main.js"></script>
</head>

<body>
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
