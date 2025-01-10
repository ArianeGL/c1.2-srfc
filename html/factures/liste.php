<?php 
require_once("../db_connection.inc.php");
session_start();
$idoffre = $_GET["idoffre"];

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
    try{
    $query_idfacture = "SELECT idfacture,datefacture,echeancereglement,totalttc FROM ". NOM_SCHEMA .".". VUE_FACTURE ."
                        WHERE idoffre = :id;";
                        $stmt = $dbh->prepare($query_idfacture);
                        $stmt->bindParam(":id",$idoffre);
                        $stmt->execute();
                        $res = $stmt->fetchall();

    }catch(PDOException $e){
        print_r($e);
    }

    if($_SESSION[""])
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../styles/listefacture.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

        <script src="../includes/main.js"></script>

        <script src="../scripts/image_preview.js"></script>
        <title>PACT - liste des factures</title>
    </head>
    <body>
        <?php foreach($res as $line){ ?>
        <section>
            <p>numero de facture : <?php print_r($line["idfacture"]) ?> </p>
            <p>date de la facture : <?php print_r($line["datefacture"]) ?></p>
            <p>total ttc : <?php print_r($line["totalttc"]) ?></p>
            <p>echéance du règlement : <?php print_r($line["echeancereglement"]) ?></p>
        </section>
        <?php } ?>
    </body>
    </html>
<?php }else{ ?>
    <p> vous n'avez pas accès à cette page</p>
    <button onclick="window.location.href = '../index.html';"> retour à l'accueil</button>
    <?php } ?>