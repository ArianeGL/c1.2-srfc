<?php
/*
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
$pdf->Output('D', 'sample.pdf');*/
?>

<?php
require('fpdf.php');

// Sample data variables
// $logo = 'path_to_logo.png'; // Replace with the path to your logo image
$titre = 'TITRE';
$date = 'DATE';
$pact = "PACT\nEmailPact\n3 Rue Edouard Branly\n22300 LANNION";
$denomination = "denomination";
$email = "email";
$type_abonnement = "type d'abonnement";
$address_line1 = "addresseline 1";
$address_line2 = "addresseline 2";
$idfacture = "12345";
$nbjoursenligne = "30";
$totalttc = "200.00 €";
$abonnementht = "150.00 €";
$abonnementttc = "180.00 €";
$optionht = "10.00 €";
$optionttc = "12.00 €";
$totalht = "160.00 €";
$tva = "20%";

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();

// LOGO
// $pdf->Image($logo, 10, 10, 30); // (file, x, y, width)
$pdf->SetFont('Arial', 'B', 16);

// TITLE
$pdf->SetXY(50, 10);
$pdf->Cell(110, 10, $titre, 0, 0, 'C'); // Title in the center
$pdf->SetXY(160, 10);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(30, 10, $date, 0, 0, 'R'); // Date on the right

// PACT Details
$pdf->SetXY(10, 40);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(80, 5, $pact); // Multiline text

// Customer Details
$pdf->SetXY(120, 40);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, $denomination, 0, 1, 'L');
$pdf->Cell(0, 5, $email, 0, 1, 'L');
$pdf->Cell(0, 5, $type_abonnement, 0, 1, 'L');
$pdf->Cell(0, 5, $address_line1, 0, 1, 'L');
$pdf->Cell(0, 5, $address_line2, 0, 1, 'L');

// FACTURE HEADER TABLE
$pdf->SetXY(10, 70);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(90, 10, 'FACTURE', 1, 0, 'C');
$pdf->Cell(50, 10, 'NOMBRE DE JOURS EN LIGNE', 1, 0, 'C');
$pdf->Cell(50, 10, 'TOTAL TTC', 1, 1, 'C'); // Last cell moves to a new line

// FACTURE DATA ROW
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(90, 10, $idfacture, 1, 0, 'C');
$pdf->Cell(50, 10, $nbjoursenligne, 1, 0, 'C');
$pdf->Cell(50, 10, $totalttc, 1, 1, 'C'); // Last cell moves to a new line

// DETAILS HEADER
$pdf->Ln(10); // Line break
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'DETAILS', 0, 1, 'C');

// DETAILS TABLE
$pdf->SetFont('Arial', '', 10);

// First row
$pdf->Cell(90, 8, "Nombre de jours en ligne", 1);
$pdf->Cell(90, 8, $nbjoursenligne, 1, 1, 'C');

// Second row
$pdf->Cell(90, 8, "Abonnement HT", 1);
$pdf->Cell(90, 8, $abonnementht, 1, 1, 'C');

// Third row
$pdf->Cell(90, 8, "Abonnement TTC", 1);
$pdf->Cell(90, 8, $abonnementttc, 1, 1, 'C');

// Fourth row
$pdf->Cell(90, 8, "Option HT", 1);
$pdf->Cell(90, 8, $optionht, 1, 1, 'C');

// Fifth row
$pdf->Cell(90, 8, "Option TTC", 1);
$pdf->Cell(90, 8, $optionttc, 1, 1, 'C');

// Sixth row
$pdf->Cell(90, 8, "Total HT", 1);
$pdf->Cell(90, 8, $totalht, 1, 1, 'C');

// Seventh row
$pdf->Cell(90, 8, "TVA", 1);
$pdf->Cell(90, 8, $tva, 1, 1, 'C');

// Eighth row
$pdf->Cell(90, 8, "Total TTC", 1);
$pdf->Cell(90, 8, $totalttc, 1, 1, 'C');

// Output the PDF
$pdf->Output('D', 'facture.pdf');
?>



<!-- redirect to the constulter_facture-2.php page -->
<script>
    window.location.href = "consulter_facture-2.php?idoffre=<?php echo $idoffre; ?>";
</script>