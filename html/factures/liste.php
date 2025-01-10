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
                        WHERE idoffre = :id ORDER BY datefacture DESC;";
                        $stmt = $dbh->prepare($query_idfacture);
                        $stmt->bindParam(":id",$idoffre);
                        $stmt->execute();
                        $res = $stmt->fetchall();

    $query_nomoffre = "SELECT nomoffre FROM ". NOM_SCHEMA .".". TABLE_OFFRE ."
                        WHERE idoffre = :id;";
                        $stmt = $dbh->prepare($query_idfacture);
                        $stmt->bindParam(":id",$idoffre);
                        $stmt->execute();
                        $nomoffre = $stmt->fetch(PDO::FETCH_ASSOC);

    }catch(PDOException $e){
        print_r($e);
    }

    if($_SESSION["identifiant"])
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
        <h1> Liste des factures pour l'offre <?php echo $idoffre ?></h1>
        <div class=liste>
        <?php foreach($res as $line){ ?>
            <div class=facture>
                <section >
                    <h1><?php echo $line["idfacture"]; ?></h1>
                    <p> date de la facture : <?php echo $line["datefacture"]; ?> </h2> <!-- a modifier avec le bon affichage de la note -->
                </section>            
                <section>
                    <h3> échéance du règlement : <?php echo $line["echeancereglement"]; ?> </h3>
                    <p> prix TTC : <?php echo $line["totalttc"]; ?> €</p>
                </section>
                <button onclick="window.location.href = 'consulter_facture.php?idfacture=<?php echo($line['idfacture']) ?>'";>Afficher la facture</button>
            </div>
            <?php } ?>
        </div>
    </body>
    </html>
<?php }else{ ?>
    <p> vous n'avez pas accès à cette page</p>
    <button onclick="window.location.href = '../index.html';"> retour à l'accueil</button>
    <?php } ?>