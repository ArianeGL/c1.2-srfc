<?php
require_once("db_connection.inc.php");

require('fpdf.php');

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

$title = isset($idoffre) ? 'Facture de l\'offre ' . $idoffre : 'Facture d\'Offre';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Add dynamic content
$pdf->Cell(40, 10, $title);
$pdf->Ln();
$pdf->Cell(40, 10, 'This is a sample PDF file.');

// Ensure no output before this line
$pdf->Output('D', 'sample.pdf');
?>
<!-- redirect to the constulter_facture-2.php page -->
<script>
    window.location.href = "consulter_facture-2.php?idoffre=<?php echo $idoffre; ?>";
</script>