<?php
require_once("../includes/fpdf.php");
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
    $queryOffer = 'SELECT nomoffre, idcompte, abonnement FROM ' . NOM_SCHEMA . '._offre WHERE idoffre = :offerId';
    $sthOffer = $dbh->prepare($queryOffer);
    $sthOffer->bindParam(':offerId', $idoffre, PDO::PARAM_STR);
    $sthOffer->execute();
    $offer = $sthOffer->fetch(PDO::FETCH_ASSOC);

    if ($offer) {
        $nomoffre = $offer['nomoffre'];
        $idcompte = $offer['idcompte'];
        $abonnement = $offer['abonnement'];

        $queryCompte = 'SELECT email, numadressecompte, ruecompte, villecompte, codepostalcompte, denomination FROM ' . NOM_SCHEMA . '.' . VUE_PRO_PRIVE . ' WHERE idcompte = :idcompte';
        $sthCompte = $dbh->prepare($queryCompte);
        $sthCompte->bindParam(':idcompte', $idcompte, PDO::PARAM_STR);
        $sthCompte->execute();
        $compte = $sthCompte->fetch(PDO::FETCH_ASSOC);
        if($compte){
            if($compte["email"]==$_SESSION["identifiant"]){
                $email=$compte["email"];
                $num=$compte["numadressecompte"];
                $rue=$compte["ruecompte"];
                $ville=$compte["villecompte"];
                $cp=$compte["codepostalcompte"];
                $denomination=$compte["denomination"];
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
    <?php
    $queryFacture = 'SELECT * FROM ' . NOM_SCHEMA . '.'.VUE_FACTURE.' WHERE idoffre = :idoffre and moisprestation = :moisDavant';
    $sthFacture = $dbh->prepare($queryFacture);
    $sthFacture->bindParam(':idoffre', $idoffre, PDO::PARAM_STR);
    $sthFacture->bindParam(':moisDavant', $moisDavant, PDO::PARAM_STR);
    $sthFacture->execute();
    $facture = $sthFacture->fetch(PDO::FETCH_ASSOC);
    if($facture){  
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

        $nomoffre = isset($nomoffre) ? (string) $nomoffre : '';
        $idfacture = isset($idfacture) ? (string) $idfacture : '';
        $datefacture = isset($datefacture) ? (string) $datefacture : '';
        $idoffre = isset($idoffre) ? (string) $idoffre : '';
        $moisprestation = isset($moisprestation) ? (string) $moisprestation : '';
        $echeancereglement = isset($echeancereglement) ? (string) $echeancereglement : '';
        $nbjoursenligne = isset($nbjoursenligne) ? (string) $nbjoursenligne : '0';
        $abonnementht = isset($abonnementht) ? (string) $abonnementht : '0.00';
        $abonnementttc = isset($abonnementttc) ? (string) $abonnementttc : '0.00';
        $optionht = isset($optionht) ? (string) $optionht : '0.00';
        $optionttc = isset($optionttc) ? (string) $optionttc : '0.00';
        $totalht = isset($totalht) ? (string) $totalht : '0.00';
        $totalttc = isset($totalttc) ? (string) $totalttc : '0.00';
        $tva = isset($tva) ? (string) $tva : '20%'; // Default to 20% if not set
        $email = isset($email) ? (string) $email : '';
        $num = isset($num) ? (string) $num : '';
        $rue = isset($rue) ? (string) $rue : '';
        $ville = isset($ville) ? (string) $ville : '';
        $cp = isset($cp) ? (string) $cp : '';
        $denomination = isset($denomination) ? (string) $denomination : '';


        $titre = "Facture de l'offre:\n" . $nomoffre;
        $date = $datefacture;
        $pact = "PACT\nvalerian.galle@etudiant.univ-rennes.fr\n3 Rue Edouard Branly\n22300 LANNION";
        $type_abonnement = $abonnement;
        $address_line1 = $num . $rue;
        $address_line2 = $cp . $ville;
        $client = "" . $denomination . "\n" . $email . "\n" . $address_line1 . "\n" . $address_line2;
        $tva = "20%";

        // Create PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // LOGO
        // $pdf->Image($logo, 10, 10, 30); // (file, x, y, width)
        $pdf->SetFont('Arial', 'B', 16);

        // TITLE
        $pdf->SetXY(50, 10);
        $pdf->MultiCell(110, 10, $titre, 'C'); // Title in the center
        $pdf->SetXY(160, 10);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(30, 10, $datefacture, 0, 0, 'R'); // Date on the right

        // PACT Details
        $pdf->SetXY(10, 40);
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(80, 5, $pact); // Multiline text

        // Customer Details
        $pdf->SetXY(120, 40);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Multicell(80, 5, $client, 'L');

        // FACTURE HEADER TABLE
        $pdf->SetXY(10, 70);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'FACTURE', 1, 0, 'C');
        $pdf->Cell(90, 10, 'NOMBRE DE JOURS EN LIGNE', 1, 0, 'C');
        $pdf->Cell(50, 10, 'TOTAL TTC', 1, 1, 'C'); // Last cell moves to a new line

        // FACTURE DATA ROW
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 10, $idfacture, 1, 0, 'C');
        $pdf->Cell(90, 10, $nbjoursenligne, 1, 0, 'C');
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
    }
    echo "<script>window.location.href = 'consulter_facture.php?idoffre=$idoffre';</script>";
?>