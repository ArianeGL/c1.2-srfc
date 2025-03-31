<?php
require_once "../db_connection.inc.php";
require_once "../includes/consts.inc.php";
session_start();

/*
 * retourne l'id du compte connecté
 * envoie une PDOException en cas d'erreur
 */
function get_account_id()
{
    global $dbh;

    try {
        $query = "SELECT idcompte FROM " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . " WHERE email = '" . $_SESSION['identifiant'] . "';";
        $id = $dbh->query($query)->fetch();
    } catch (PDOException $e) {
        throw $e;
    }

    return $id['idcompte'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../includes/style.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
            crossorigin="" />

        <!-- Make sure you put this AFTER Leaflet's CSS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin="">
        </script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
        <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
        <title>PACT</title>
    </head>

    <body>
        <?php require_once HEADER ?>
        <section id="delete_acc_confirm">
            <h2>Êtes-vous certain de vouloir supprimer votre compte ? Cette action est irréversible.</h2>
            <div style="display: flex;">
                <button class="redButton" onclick="javascript:location.href='../includes/supprimer_compte.inc.php?act=suppr'">Supprimer</button>
                <button class="smallButton" onclick="javascript:location.href='../compte/consultation_membre.php'">Annuler</button>
            </div>
        </section>
    </body>

    </html>
<?php
} else if (isset($_GET['act'])) {
    $query = "DELETE FROM " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE_MEMBRE . " WHERE idcompte = :id";
    $id = get_account_id();
    global $dbh;

    try {
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        die("Failed to delete account : " . $e->getMessage());
    }

    session_destroy();
    echo "<script>location.href='../offres/liste.php'</script>";
}
