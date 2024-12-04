<?php
require_once("db_connection.inc.php");

session_start();

function generate_id()
{
    global $dbh;
    $id_base = "Fa-";
    $count_query = "SELECT COUNT(*) FROM " . NOM_SCHEMA . "." . VUE_FACTURE . ";";
    try {
        $count = $dbh->query($count_query)->fetchColumn(); // recupere le nombre d'offre deja existante
    } catch (PDOException $e) {
        die("SQL Query in generate_id() failed : " . $e->getMessage());
    }
    $count++;

    switch (strlen((string)$count)) {
        case 1:
            return $id_base . "000" . strval($count);
            break;
        case 2:
            return $id_base . "00" . strval($count);
            break;
        case 3:
            return $id_base . "0" . strval($count);
            break;
        case 4:
            return $id_base . strval($count);
            break;
        default:
            throw new FunctionException("Couldn't generate id");
    }
}

if (isset($_GET['idoffre'])) {
    $idoffre = $_GET['idoffre'];
    $queryOffer = 'SELECT idcompte FROM ' . NOM_SCHEMA . '._offre WHERE idoffre = :offerId';
    $sthOffer = $dbh->prepare($queryOffer);
    $sthOffer->bindParam(':offerId', $idoffre, PDO::PARAM_STR);
    $sthOffer->execute();
    $offer = $sthOffer->fetch(PDO::FETCH_ASSOC);

    if ($offer) {
        $idcompte = $offer['idcompte'];
        $queryCompte = 'SELECT email FROM ' . NOM_SCHEMA . '._compte WHERE idcompte = :idcompte';
        $sthCompte = $dbh->prepare($queryCompte);
        $sthCompte->bindParam(':idcompte', $idcompte, PDO::PARAM_STR);
        $sthCompte->execute();
        $compte = $sthCompte->fetch(PDO::FETCH_ASSOC);
        if($compte){
            if($compte["email"]==$_SESSION["identifiant"]){
                $moisDavant=date('n')-1;
                if ($moisDavant==0){
                    $moisDavant=12;
                }
                $queryFacture = 'SELECT idfacture FROM ' . NOM_SCHEMA . '.'.VUE_FACTURE.' WHERE idoffre = :idoffre and moisprestation = :moisDavant';
                $sthFacture = $dbh->prepare($queryFacture);
                $sthFacture->bindParam(':idoffre', $idoffre, PDO::PARAM_STR);
                $sthFacture->bindParam(':moisDavant', $moisDavant, PDO::PARAM_STR);
                $sthFacture->execute();
                $facture = $sthFacture->fetch(PDO::FETCH_ASSOC);
                if($facture){  
                    $idfacture=$facture["idfacture"];
                }else{
                    $idfacture=generate_id();
                    $queryFacture = 'INSERT INTO ' . NOM_SCHEMA . '.'.VUE_FACTURE.' (idoffre,idfacture) 
                    VALUES(:idoffre,:idfacture)';
                    $sthFacture = $dbh->prepare($queryFacture);
                    $sthFacture->bindParam(':idoffre', $idoffre, PDO::PARAM_STR);
                    $sthFacture->bindParam(':idfacture', $idfacture, PDO::PARAM_STR);
                    $sthFacture->execute();
                    $facture = $sthFacture->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $queryFacture = 'SELECT * FROM ' . NOM_SCHEMA . '.'.VUE_FACTURE.' WHERE idoffre = :idoffre and moisprestation = :moisDavant';
    $sthFacture = $dbh->prepare($queryFacture);
    $sthFacture->bindParam(':idoffre', $idoffre, PDO::PARAM_STR);
    $sthFacture->bindParam(':moisDavant', $moisDavant, PDO::PARAM_STR);
    $sthFacture->execute();
    $facture = $sthFacture->fetch(PDO::FETCH_ASSOC);
    if($facture){  
        $idfacture=$facture["idfacture"];
        $datefacture=$facture["datefacture"];
        $idoffre=$facture["idoffre"];
        $moisprestation=$facture["moisprestation"];
        $echeancereglement=$facture["echeancereglement"];
        $nbjoursenligne=$facture["nbjoursenligne"];
        $abonnementht=$facture["abonnementht"];
        $abonnementttc=$facture["abonnementttc"];
        $optionht=$facture["optionht"];
        $optionttc=$facture["optionttc"];
        $totalht=$facture["totalht"];
        $totalttc=$facture["totalttc"];

        echo "idfacture : ".$idfacture;
        ?><br><?php
        echo "datefacture : ".$datefacture;
        ?><br><?php
        echo "idoffre : ".$idoffre;
        ?><br><?php
        echo "moisprestation : ".$moisprestation;
        ?><br><?php
        echo "echeancereglement : ".$echeancereglement;
        ?><br><?php
        echo "nbjoursenligne : ".$nbjoursenligne;
        ?><br><?php
        echo "abonnementht : ".$abonnementht;
        ?><br><?php
        echo "abonnementttc : ".$abonnementttc;
        ?><br><?php
        echo "optionht : ".$optionht;
        ?><br><?php
        echo "optionttc : ".$optionttc;
        ?><br><?php
        echo "totalht : ".$totalht;
        ?><br><?php
        echo "totalttc : ".$totalttc;
        ?><br><?php
    }
    ?>
</body>
</html>