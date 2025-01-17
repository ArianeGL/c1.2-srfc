<?php
require_once("../includes/fpdf/fpdf.php");
require_once("../db_connection.inc.php");

session_start();

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Facture', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function AddMonthTable($month, $year, $start_date, $end_date) {
        $this->SetFont('Arial', '', 10);
        $this->Ln(10);
        $this->Cell(0, 10, "Calendrier des jours en ligne pour $month/$year", 0, 1, 'C');
    
        $table_width = 15 * 7;
        $this->SetX((210 - $table_width) / 2);
    
        $days_in_month = (new DateTime("$year-$month-01"))->format('t');
    
        $day_count = 0;
    
        // Prepare JavaScript debug output
        // echo "<script>";
        for ($day = 1; $day <= $days_in_month; $day++) {
            $status = 'Hors ligne';
            if ($day <= 10) {
                $current_date = strtotime("$year-$month-0$day");
            }
            else {
                $current_date = strtotime("$year-$month-$day");
            }
            // echo "alert('$year-$month-$day $current_date');";
    
            // Add debugging information
            // echo "alert('Processing day: $day (Current date: " . date('Y-m-d', $current_date) . ")');";
    
            foreach ($start_date as $index => $start) {
                $start_ts = strtotime($start);
                $end_ts = strtotime($end_date[$index]);
                // echo "alert('start day: $start_ts Current date: $current_date end day: $end_ts');";
    
                if ($current_date >= $start_ts && $current_date <= $end_ts) {
                    $status = 'En ligne';
                    break;
                }
            }
    
            $color = ($status == 'En ligne') ? [0, 255, 0] : [255, 0, 0];
            // echo "alert('Day $day: Status: $status | Color: " . implode(',', $color) . "');";
    
            $this->SetFillColor($color[0], $color[1], $color[2]);
            $this->Cell(15, 7, $day, 1, 0, 'C', true);
    
            $day_count++;
    
            if ($day_count % 7 == 0) {
                $this->Ln();
                $this->SetX((210 - $table_width) / 2);
            }
        }
    
        if ($day_count % 7 != 0) {
            $remaining_cells = 7 - ($day_count % 7);
            for ($i = 0; $i < $remaining_cells; $i++) {
                $this->Cell(15, 7, '', 1, 0, 'C');
            }
            $this->Ln();
        }
        // echo "</script>";
    }
}

if (isset($_GET['idfacture'])) {
    $queryFacture = 'SELECT * FROM ' . NOM_SCHEMA . '._historique 
                    NATURAL JOIN ' . NOM_SCHEMA . '.facture 
                    WHERE EXTRACT(MONTH FROM datedebut) = moisprestation 
                    AND idfacture = :idfacture';
    $sthFacture = $dbh->prepare($queryFacture);
    $sthFacture->bindParam(':idfacture', $_GET['idfacture'], PDO::PARAM_STR);
    $sthFacture->execute();
    $facture = $sthFacture->fetchAll(PDO::FETCH_ASSOC);
    
    $queryInfoCompl = 'SELECT * FROM ' . NOM_SCHEMA . '._offre 
                    NATURAL JOIN ' . NOM_SCHEMA . '.compteprofessionnelprive 
                    WHERE idoffre = :idoffre';
    $sthInfoCompl = $dbh->prepare($queryInfoCompl);
    $sthInfoCompl->bindParam(':idoffre', $facture[0]["idoffre"], PDO::PARAM_STR);
    $sthInfoCompl->execute();
    $infoCompl = $sthInfoCompl->fetch(PDO::FETCH_ASSOC);

    $queryFactureYear = 'SELECT EXTRACT(YEAR FROM CURRENT_DATE - 31)';
    $sthFactureYear = $dbh->prepare($queryFactureYear);
    $sthFactureYear->execute();
    $year = $sthFactureYear->fetch(PDO::FETCH_ASSOC);

    $queryOption = 'SELECT DISTINCT option.*, _historiqueoption.prixoption FROM ' . NOM_SCHEMA . '.option NATURAL JOIN ' . NOM_SCHEMA . '._historiqueoption WHERE idoffre=:idoffre';
    $sthOption = $dbh->prepare($queryOption);
    $sthOption->bindParam(':idoffre', $facture[0]["idoffre"], PDO::PARAM_STR);
    $sthOption->execute();
    $option = $sthOption->fetchAll(PDO::FETCH_ASSOC);
}
else {
    // echo "<script>alert('Aucune facture disponible.');</script>";
    // echo "<script>window.location.href = 'consulter_facture.php?idoffre=$idoffre';</script>";
}

if($facture != false){  
    $idoffre=$facture[0]["idoffre"];
    $idfacture=$facture[0]["idfacture"];

    //gets every datedebut from facture
    $date_debut=array_column($facture, "datedebut");
    $date_fin=array_column($facture, "datefin");
    
    $date_facture=$facture[0]["datefacture"];
    $moisprestation=$facture[0]["moisprestation"];
    $echeancereglement=$facture[0]["echeancereglement"];
    $nbjoursenligne=$facture[0]["nbjoursenligne"];
    $abonnement_ht=$facture[0]["abonnementht"];
    $abonnement_ttc=$facture[0]["abonnementttc"];
    $option_ht=$facture[0]["optionht"];
    $option_ttc=$facture[0]["optionttc"];
    $total_ht=$facture[0]["totalht"];
    $total_ttc=$facture[0]["totalttc"];
    
    $nomCompte=$infoCompl["denomination"];
    $adresseCompte=$infoCompl["numadressecompte"] . " " . $infoCompl["ruecompte"] . ", " . $infoCompl["codepostalcompte"] . " " . $infoCompl["villecompte"];
    
    // Données de facturation
    $facture_numero = $idfacture;
    $date_emission = $date_facture;
    $date_echeance = $echeancereglement;
    
    $nom_plateforme = "PACT";
    $adresse_plateforme = "3 Rue Edouard Branly, 22300 Lannion";
    
    $nom_professionnel = $nomCompte;
    $adresse_professionnel = $adresseCompte;
    
    $designation_offre = $infoCompl["nomoffre"];
    $mois_concerne = $moisprestation;
    
    $abonnement_jours = $nbjoursenligne;
    $abonnement_tarif_ht = $abonnement_ht;
    
    $option_a_la_une_semaines = 0;
    $option_a_la_une_tarif_ht = 0;
    $option_en_relief_semaines = 0;
    $option_en_relief_tarif_ht = 0;

    foreach ($option as $key => $value) {
        if ($value["option"] == "A la une") {
            $option_a_la_une_semaines = $value["nbsemaine"];
            $option_a_la_une_tarif_ht = $value["prixoption"];
        }
        else if ($value["option"] == "En relief") {
            $option_en_relief_semaines = $value["nbsemaine"];
            $option_en_relief_tarif_ht = $value["prixoption"];
        }
    }
    
    $abonnement_total_ht = $abonnement_ht;
    $abonnement_total_ttc = $abonnement_ttc;
    $option_a_la_une_total_ht = $option_a_la_une_semaines * $option_a_la_une_tarif_ht;
    $option_a_la_une_total_ttc = $option_a_la_une_total_ht * 1.2;
    $option_en_relief_total_ht = $option_en_relief_semaines * $option_en_relief_tarif_ht;
    $option_en_relief_total_ttc = $option_en_relief_total_ht * 1.2;

    
    // Définir la période d'activité
    $start_date = $date_debut;
    $end_date = $date_fin;
    $year = $year["extract"];
    switch ($moisprestation) {
        case 1:
            $mois_concerne = "Janvier " . $year;
            break;
        case 2:
            $mois_concerne = "Février " . $year;
            break;
        case 3:
            $mois_concerne = "Mars " . $year;
            break;
        case 4:
            $mois_concerne = "Avril " . $year;
            break;
        case 5:
            $mois_concerne = "Mai " . $year;
            break;
        case 6:
            $mois_concerne = "Juin " . $year;
            break;
        case 7:
            $mois_concerne = "Juillet " . $year;
            break;
        case 8:
            $mois_concerne = "Août " . $year;
            break;
        case 9:
            $mois_concerne = "Septembre " . $year;
            break;
        case 10:
            $mois_concerne = "Octobre " . $year;
            break;
        case 11:
            $mois_concerne = "Novembre " . $year;
            break;
        case 12:
            $mois_concerne = "Décembre " . $year;
            break;
        default:
            $mois_concerne = "Mois inconnu";
            break;
    }
    $month = $mois_concerne;
}
else {
    // echo "<script>alert('Aucune facture disponible.');</script>";
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
    
    // Définir la période d'activité
    $start_date = "2025-01-05";
    $end_date = "2025-01-20";
    $month = 1; // Janvier
    $year = 2025;
}


// Création du PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Informations générales
$pdf->Cell(0, 10, utf8_decode("Numéro de facture : $facture_numero"), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Date d'émission : $date_emission"), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Date d'échéance : $date_echeance"), 0, 1);
$pdf->Ln(5);

// Informations parties
$pdf->Cell(0, 10, utf8_decode("Émetteur : $nom_plateforme"), 0, 1);
$pdf->MultiCell(0, 10, utf8_decode("Adresse : $adresse_plateforme"), 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, utf8_decode("Client : $nom_professionnel"), 0, 1);
$pdf->MultiCell(0, 10, utf8_decode("Adresse : $adresse_professionnel"), 0, 1);
$pdf->Ln(10);

// Désignation de l'offre
$pdf->Cell(0, 10, utf8_decode("Désignation de l'offre : $designation_offre"), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Mois concerné : $mois_concerne"), 0, 1);
$pdf->Ln(10);

// Détails des services
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 10, utf8_decode('Service'), 1);
$pdf->Cell(30, 10, utf8_decode('Quantité'), 1);
$pdf->Cell(30, 10, utf8_decode('HT'), 1);
$pdf->Cell(30, 10, utf8_decode('TTC'), 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, utf8_decode('Abonnement Premium'), 1);
$pdf->Cell(30, 10, utf8_decode($abonnement_jours . ' jours'), 1);
$pdf->Cell(30, 10, number_format($abonnement_total_ht, 2), 1);
$pdf->Cell(30, 10, number_format($abonnement_total_ttc, 2), 1);
$pdf->Ln();

$pdf->Cell(100, 10, utf8_decode('Option À la une'), 1);
$pdf->Cell(30, 10, utf8_decode($option_a_la_une_semaines . ' semaines'), 1);
$pdf->Cell(30, 10, number_format($option_a_la_une_total_ht, 2), 1);
$pdf->Cell(30, 10, number_format($option_a_la_une_total_ttc, 2), 1);
$pdf->Ln();

$pdf->Cell(100, 10, utf8_decode('Option En relief'), 1);
$pdf->Cell(30, 10, utf8_decode($option_en_relief_semaines . ' semaines'), 1);
$pdf->Cell(30, 10, number_format($option_en_relief_total_ht, 2), 1);
$pdf->Cell(30, 10, number_format($option_en_relief_total_ttc, 2), 1);
$pdf->Ln();

// Totaux
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(130, 10, utf8_decode('Total'), 1);
$pdf->Cell(30, 10, number_format($total_ht, 2), 1);
$pdf->Cell(30, 10, number_format($total_ttc, 2), 1);
$pdf->Ln(10);

$pdf->SetFont('Arial', 'I', 10);
$pdf->MultiCell(0, 10, utf8_decode("*L'abonnement Premium de $abonnement_jours jours correspond à une offre en ligne pour professionnels privés durant cette période."), 0, 1);
// Add the month table, ensuring it's centered
$pdf->AddMonthTable($moisprestation, $year, $start_date, $end_date);


// Sortie du PDF
$pdf->Output('I', 'facture.pdf');
