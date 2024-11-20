<?php
require_once 'db_connection.inc.php';
global $dbh;
echo "<pre>";
print_r($_GET);
echo "</pre>";

if (isset($_GET['idoffre'])) {
    $idOffre = $_GET['idoffre'];

    if (isset($_GET['action']) && $_GET['action'] === 'mettreHorsLigne') {
        try {

            $checkSql = "SELECT COUNT(*) FROM " . NOM_SCHEMA . "." . VUE_PARC_ATTRACTIONS . " WHERE idoffre = :idoffre";
            $checkStmt = $dbh->prepare($checkSql);
            $checkStmt->execute(['idoffre' => $idOffre]);
            $count = $checkStmt->fetchColumn();
            print_r($count);

            if ($count > 0) {
                $sql = "UPDATE " . NOM_SCHEMA . "." . VUE_PARC_ATTRACTIONS . " SET enligne = false WHERE idoffre = :idoffre";
                $stmt = $dbh->prepare($sql);
            }

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
    } elseif (isset($_GET['action']) && $_GET['action'] === 'annuler') {
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
    <link rel="stylesheet" href="styles/consultation.css">

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

    <form class="pop-up" method="GET" action="informations_offre-1.php">
        <input type="hidden" name="idoffre" value="<?php echo htmlspecialchars($idOffre); ?>">
        <button type="submit" class="big-button" id="bouton-supprimer" name="action" value="mettreHorsLigne">Mettre hors-ligne</button>
        <button type="submit" class="big-button" id="bouton-modifier" name="action" value="annuler">Annuler</button>
    </form>
</body>

</html>
