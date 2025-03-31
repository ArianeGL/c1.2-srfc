<?php
require_once("../db_connection.inc.php");
require_once("../includes/consts.inc.php");
session_start();
$idoffre = $_GET["idoffre"];

global $idoffre;

/**prend en paramètre la session 
 * renvoie un booléen : vrai si l'offre appartient à la session, false sinon
 */
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

function getNomDuMois($numero_du_mois)
{
    switch ($numero_du_mois) {
        case 1:
            return "Janvier";
        case 2:
            return "Février";
        case 3:
            return "Mars";
        case 4:
            return "Avril";
        case 5:
            return "Mai";
        case 6:
            return "Juin";
        case 7:
            return "Juillet";
        case 8:
            return "Août";
        case 9:
            return "Septembre";
        case 10:
            return "Octobre";
        case 11:
            return "Novembre";
        case 12:
            return "Décembre";
        default:
            return "Numéro de mois invalide"; // Gestion des cas hors 1-12
    }
}
/*
    prend en paramètre l'id d'une facture
    retourne l'année du mois de la facture
*/
function getAnnee($idfacture)
{
    global $dbh;

    try {
        $query_annee = "SELECT extract(YEAR FROM datefacture - 31) FROM " . NOM_SCHEMA . "." . VUE_FACTURE . "
                        WHERE idfacture = 'Fa-0001';";
        $stmt = $dbh->prepare($query_annee);
        //$stmt->bindParam(":id",$idfacture);
        $stmt->execute();
        $rep = $stmt->fetch(PDO::FETCH_ASSOC);

        return $rep;
    } catch (PDOException $e) {
        die($e);
    }
}

if (offre_appartient($_SESSION['identifiant'])) {
    try {
        $query_idfacture = "SELECT idfacture,datefacture,echeancereglement,totalttc,moisprestation FROM " . NOM_SCHEMA . "." . VUE_FACTURE . "
                        WHERE idoffre = :id ORDER BY datefacture DESC;";
        $stmt = $dbh->prepare($query_idfacture);
        $stmt->bindParam(":id", $idoffre);
        $stmt->execute();
        $res = $stmt->fetchall();

        $query_nomoffre = "SELECT nomoffre FROM " . NOM_SCHEMA . "." . NOM_TABLE_OFFRE . "
                        WHERE idoffre = :id;";
        $stmt = $dbh->prepare($query_idfacture);
        $stmt->bindParam(":id", $idoffre);
        $stmt->execute();
        $nomoffre = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print_r($e);
    }

    if ($_SESSION["identifiant"])
?>
    <!DOCTYPE html>
    <html lang="en" id="liste_facture">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../includes/style.css">

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
        <?php require_once HEADER; ?>
        <h1> Liste des factures pour l'offre <?php echo $idoffre ?></h1>
        <div class=liste>
            <?php foreach ($res as $line) { ?>
                <div class=facture>
                    <section>
                        <h1> Facture du mois de <?php echo getNomDuMois($line["moisprestation"]);
                                                echo " " . getAnnee($line["idfacture"])["extract"] ?></h1>
                        <p> Date de la facture : <?php echo $line["datefacture"]; ?> </h2> <!-- a modifier avec le bon affichage de la note -->
                    </section>
                    <section>
                        <h3> Échéance du règlement : <?php echo $line["echeancereglement"]; ?> </h3>
                        <p> Prix TTC : <?php echo $line["totalttc"]; ?> €</p>
                    </section>
                    <button class=smallButton onclick="window.location.href = 'generate_pdf.php?idfacture=<?php echo ($line['idfacture']) ?>'" ;>Afficher la facture</button>
                </div>
            <?php } ?>
        </div>
        <?php require_once FOOTER; ?>
    </body>

    </html>
<?php } else { ?>
    <p> vous n'avez pas accès à cette page</p>
    <button onclick="window.location.href = '../index.html';"> retour à l'accueil</button>
<?php } ?>
