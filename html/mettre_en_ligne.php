<?php
include 'connect_params.php';


if (isset($_POST['idoffre'])) {
    $idOffre = $_POST['idoffre'];
    
    if (isset($_POST['action']) && $_POST['action'] === 'mettreHorsLigne') {
        try {

            $sql = "UPDATE _offre SET horsLigne = true WHERE idoffre = :idoffre";
            $stmt = $pdo->prepare($sql);
            
            // Exécution de la requête avec le paramètre
            $success = $stmt->execute(['idoffre' => $idOffre]);
            
            // Cas de réussite
            if ($success) {
                echo "<p>L'offre a été mise hors-ligne avec succès.</p>";
            } else {
                echo "<p>Erreur lors de la mise hors-ligne de l'offre.</p>";
            }
        } catch (PDOException $e) {
            echo "<p>Erreur lors de la mise hors-ligne de l'offre : " . $e->getMessage() . "</p>";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'annuler') {
        header("Location: information_offre-1.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
    <title>Confirmation de mise hors-ligne</title>
</head>
<body>
    <p id="red-text">Attention ! L’offre sera visible de tous sur le site</p>
    <p>Voulez vous vraiment mettre l’offre en ligne ?</p>

    <form class="pop-up" method="POST" action="information_offre-1.php">
        <input type="hidden" name="idOffre" value="<?php echo htmlspecialchars($idOffre); ?>">
        <button type="submit" class="big-button"id="bouton-supprimer" name="action" value="mettreHorsLigne">Mettre hors-ligne</button>
        <button type="submit" class="big-button"id= "bouton-modifier" name="action" value="annuler">Annuler</button>
    </form>
</body>
</html>
