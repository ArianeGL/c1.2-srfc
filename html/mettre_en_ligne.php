<?php
session_start();
require_once 'db_connection.inc.php';
global $dbh;


$idOffre = $_GET["idoffre"];
try{
$query_compte = "SELECT email FROM ".NOM_SCHEMA."._offre
                INNER JOIN sae._compte on _compte.idcompte = _offre.idcompte
                WHERE _offre.idoffre = :idoffre";
$stmt_compte = $dbh->prepare($query_compte);
$stmt_compte->bindParam(':idoffre',$idOffre);
$stmt_compte->execute();
$compte = $stmt_compte->fetch(PDO::FETCH_ASSOC)["email"];


$query_estenligne = "SELECT enligne FROM ". NOM_SCHEMA ."._offre
                    WHERE _offre.idoffre = :idoffre";
$stmt_estenligne = $dbh->prepare($query_estenligne);
$stmt_estenligne->bindParam(':idoffre',$idOffre);
$stmt_estenligne->execute();
$estenligne = $stmt_estenligne->fetch(PDO::FETCH_ASSOC)["enligne"];
}catch(PDOException $e){
    print_r($e);
}

if($compte == $_SESSION["identifiant"]){
if(!$estenligne){

try{
$query_categorie = "SELECT categorie FROM " . NOM_SCHEMA . "._offre
    WHERE idoffre = '$idOffre'";
    $stmt_categorie = $dbh->prepare($query_categorie);
    $stmt_categorie->execute();
    $categorie = $stmt_categorie->fetch(PDO::FETCH_ASSOC)["categorie"];
}catch(PDOException $e){
    print_r($e);
}


if (isset($_POST['idoffre'])) {
    $idOffre = $_POST['idoffre'];

    if ($_POST["action"] == "mettreEnLigne") {
        try {

            //if ($count > 0) {
                $sql = "UPDATE " . NOM_SCHEMA . "." . $categorie . " SET enligne = true WHERE idoffre = '". $idOffre ."';";
                $stmt = $dbh->prepare($sql);
            //}

            // Exécution de la requête avec le paramètre
            $success = $stmt->execute();

            // Cas de réussite
            if ($success) {
                echo "<p>L'offre a été mise en ligne avec succès.</p>";
            } else {
                echo "<p>Erreur lors de la mise en ligne de l'offre.</p>";
            }
        } catch (PDOException $e) {
            echo "<p>Erreur lors de la mise en ligne de l'offre : " . $e->getMessage() . "</p>";
        }
        header("Location: informations_offre-1.php?idoffre=". $idOffre);
    } elseif (isset($_GET['action']) && $_GET['action'] === 'annuler') {
        header("Location: informations_offre-1.php?idoffre=". $idOffre);
        exit;
    }else{
        print_r("Erreur d'action");
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="styles/consultation.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
    <title>Confirmation de mise en ligne</title>
</head>

<body>
    <p id="red-text">Attention ! L’offre ne sera plus visible sur le site</p>
    <p>Voulez vous vraiment mettre l’offre en ligne ?</p>

    <form class="pop-up" method="POST" action="mettre_en_ligne.php?idoffre=<?php echo $idOffre ?>">
        <input type="hidden" name="idoffre" value="<?php echo htmlspecialchars($idOffre); ?>">
        <button type="submit" class="big-button" id="bouton-supprimer" name="action" value="mettreEnLigne">Mettre en ligne</button>
        <button type="submit" class="big-button" id="bouton-modifier" name="action" value="annuler">Annuler</button>
    </form>
</body>

</html>
<?php }else{
    header("Location: mettre_hors_ligne.php?idoffre=". $idOffre);
}
} 
else{
    print_r("impossible de mettre cette offre en ligne avec ce compte : $compte");
}?>