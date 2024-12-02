<?php 
require_once  "db_connection.inc.php";


$code = rand(0,99999);
$dest = '';
$objet = '';
$message = '';

mail($dest,$objet,$message);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styles/modification_mdp.css">

    <title>Modification du mot de passe</title>
</head>
<body>
    <main>
        <h1>Entrez le code de confirmation envoyé par mail</h1>
        <h6> Nous vous avons envoyé un code à l'adresse suivante <a href="#"> <?php echo htmlspecialchars($dest) ?> </a></h6>
        
        <div id="selection_mdp">
            <input type="text" maxlength="1" name="un" id="un" data-next="deux">
            <input type="text" maxlength="1" name="deux" id="deux" data-next="trois">
            <input type="text" maxlength="1" name="trois" id="trois" data-next="quatre">
            <input type="text" maxlength="1" name="quatre" id="quatre" data-next="cinq">
            <input type="text" maxlength="1" name="cinq" id="cinq" data-next="cinq">
        </div>

        <div> 
            <p> Cliquez ici pour renvoyer un code <a href="#"> </p>
        </div>
        <button type="button" class="button"> Valider </button>
        <button type="button" class="button"> Annuler </button>

        <div id="redirection">
            <h3> Code valide </h3>
            <p> Redirection en cours</p>
        </div>
    </main>
<script src="modification_mdp.js"> </script> 
</body>
</html>