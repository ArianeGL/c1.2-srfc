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

        // Données de facturation
        $facture_numero = "20250108-001";
        $date_emission = "2025-01-08";
        $date_echeance = "2025-01-15";
        $nom_plateforme = "Plateforme XYZ";
        $adresse_plateforme = "123 Rue des Services, 75000 Paris";
        $nom_professionnel = "Entreprise ABC";
        $adresse_professionnel = "45 Avenue des Professionnels, 75001 Paris";
        $designation_offre = "Abonnement Premium avec options";
        $mois_concerne = "Janvier 2025";

        // Détails des services souscrits
        $abonnement_jours = 30;
        $abonnement_tarif_ht = 3.34;
        $option_a_la_une_semaines = 2;
        $option_a_la_une_tarif_ht = 16.68;
        $option_en_relief_semaines = 4;
        $option_en_relief_tarif_ht = 8.34;

        // Calculs
        $abonnement_total_ht = $abonnement_jours * $abonnement_tarif_ht;
        $abonnement_total_ttc = $abonnement_total_ht * 1.2;
        $option_a_la_une_total_ht = $option_a_la_une_semaines * $option_a_la_une_tarif_ht;
        $option_a_la_une_total_ttc = $option_a_la_une_total_ht * 1.2;
        $option_en_relief_total_ht = $option_en_relief_semaines * $option_en_relief_tarif_ht;
        $option_en_relief_total_ttc = $option_en_relief_total_ht * 1.2;

        $total_ht = $abonnement_total_ht + $option_a_la_une_total_ht + $option_en_relief_total_ht;
        $total_ttc = $total_ht * 1.2;

        // Création du PDF
        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Informations générales
        $pdf->Cell(0, 10, "Numero de facture : $facture_numero", 0, 1);
        $pdf->Cell(0, 10, "Date d'emission : $date_emission", 0, 1);
        $pdf->Cell(0, 10, "Date d'echeance : $date_echeance", 0, 1);
        $pdf->Ln(5);

        // Informations parties
        $pdf->Cell(0, 10, "Emetteur : $nom_plateforme", 0, 1);
        $pdf->MultiCell(0, 10, "Adresse : $adresse_plateforme", 0, 1);
        $pdf->Ln(5);
        $pdf->Cell(0, 10, "Client : $nom_professionnel", 0, 1);
        $pdf->MultiCell(0, 10, "Adresse : $adresse_professionnel", 0, 1);
        $pdf->Ln(10);

        // Designation de l'offre
        $pdf->Cell(0, 10, "Designation de l'offre : $designation_offre", 0, 1);
        $pdf->Cell(0, 10, "Mois concerne : $mois_concerne", 0, 1);
        $pdf->Ln(10);

        // Détails des services
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(100, 10, 'Service', 1);
        $pdf->Cell(30, 10, 'Quantite', 1);
        $pdf->Cell(30, 10, 'HT (\u20ac)', 1);
        $pdf->Cell(30, 10, 'TTC (\u20ac)', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(100, 10, 'Abonnement Premium', 1);
        $pdf->Cell(30, 10, $abonnement_jours . ' jours', 1);
        $pdf->Cell(30, 10, number_format($abonnement_total_ht, 2), 1);
        $pdf->Cell(30, 10, number_format($abonnement_total_ttc, 2), 1);
        $pdf->Ln();

        $pdf->Cell(100, 10, 'Option A la une', 1);
        $pdf->Cell(30, 10, $option_a_la_une_semaines . ' semaines', 1);
        $pdf->Cell(30, 10, number_format($option_a_la_une_total_ht, 2), 1);
        $pdf->Cell(30, 10, number_format($option_a_la_une_total_ttc, 2), 1);
        $pdf->Ln();

        $pdf->Cell(100, 10, 'Option En relief', 1);
        $pdf->Cell(30, 10, $option_en_relief_semaines . ' semaines', 1);
        $pdf->Cell(30, 10, number_format($option_en_relief_total_ht, 2), 1);
        $pdf->Cell(30, 10, number_format($option_en_relief_total_ttc, 2), 1);
        $pdf->Ln();

        // Totaux
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(130, 10, 'Total', 1);
        $pdf->Cell(30, 10, number_format($total_ht, 2), 1);
        $pdf->Cell(30, 10, number_format($total_ttc, 2), 1);
        $pdf->Ln(10);

        // Sortie du PDF
        $pdf->Output('I', 'facture.pdf');
    }
    echo "<script>window.location.href = 'consulter_facture.php?idoffre=$idoffre';</script>";
?>