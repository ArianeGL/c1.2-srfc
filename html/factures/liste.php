<?php 
require_once("../db_connection.inc.php");
session_start();
$idoffre = $_GET["idoffre"];
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
    <title>Factures</title>
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