<?php
require_once("db_connection.inc.php");

global $dbh;

if (isset($_GET['idoffre'])) {
    $offerId = $_GET['idoffre'];
    $queryOffer = 'SELECT * FROM ' . NOM_SCHEMA . '._offre WHERE idoffre = :offerId';
    $sthOffer = $dbh->prepare($queryOffer);
    $sthOffer->bindParam(':offerId', $offerId, PDO::PARAM_STR);
    $sthOffer->execute();
    $offer = $sthOffer->fetch(PDO::FETCH_ASSOC);

    if ($offer) {
        $id = $offer['idoffre'];
        $categorie = $offer['categorie'];

        $name = $offer['nomoffre'];
        $description = $offer['description'];
        $resume = $offer['resume'];

        $streetNumber = $offer['numadresse'];
        $streetName = $offer['rueoffre'];
        $city = $offer['villeoffre'];
        $postalCode = $offer['codepostaloffre'];
        $address = $streetNumber . ' ' . $streetName . ', ' . $city . ', ' . $postalCode;

        $minPrice = $offer['prixmin'];

        $dateStart = $offer['datedebut'];
        $dateEnd = $offer['datefin'];

        $isOnline = $offer['enligne'];
        $dateUpload = $offer['datepublication'];
        $dateLastUpdate = $offer['dernieremaj'];
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="./style.css">

            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

            <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;family=Concert+One&display=swap" rel="stylesheet">

            <title>PACT</title>
            <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        </head>

        <body>
            <?php
            require_once "./header_inc.html";
            switch ($categorie) {
                case "Activite":
                    $queryOffreCategorisee = "SELECT * FROM " . NOM_SCHEMA . "." . VUE_ACTIVITE . " WHERE idoffre = :offerId";
                    $sthOffreCategorisee = $dbh->prepare($queryOffreCategorisee);
                    $sthOffreCategorisee->bindParam(':offerId', $offerId, PDO::PARAM_STR);
                    $sthOffreCategorisee->execute();
                    $offreCategorisee = $sthOffreCategorisee->fetch(PDO::FETCH_ASSOC);

                    $ageRequierement = $offreCategorisee['agerequis'];
                    $duration = $offreCategorisee['dureeactivite'];

                    require_once './pages-info-offres/activite.php';

                    break;

                case "Restauration":
                    $queryOffreCategorisee = 'SELECT * FROM ' . NOM_SCHEMA . '.' . VUE_RESTO . ' WHERE idoffre = :offerId';
                    $sthOffreCategorisee = $dbh->prepare($queryOffreCategorisee);
                    $sthOffreCategorisee->bindParam(':offerId', $offerId, PDO::PARAM_STR);
                    $sthOffreCategorisee->execute();
                    $offreCategorisee = $sthOffreCategorisee->fetch(PDO::FETCH_ASSOC);

                    $menuUrl = $offreCategorisee['urlverscarte'];
                    $priceRange = $offreCategorisee['gammeprix'];
                    $breakfast = $offreCategorisee['petitdejeuner'];
                    $lunch = $offreCategorisee['dejeuner'];
                    $dinner = $offreCategorisee['diner'];
                    $drinks = $offreCategorisee['boisson'];
                    $brunch = $offreCategorisee['brunch'];

                    require_once './pages-info-offres/restauration.php';

                    break;

                case "Visite":
                    $queryOffreCategorisee = 'SELECT * FROM ' . NOM_SCHEMA . '.visite WHERE idoffre = :offerId';
                    $sthOffreCategorisee = $dbh->prepare($queryOffreCategorisee);
                    $sthOffreCategorisee->bindParam(':offerId', $offerId, PDO::PARAM_STR);
                    $sthOffreCategorisee->execute();
                    $offreCategorisee = $sthOffreCategorisee->fetch(PDO::FETCH_ASSOC);

                    $duration = $offreCategorisee['dureevisite'];
                    $isGuided = $offreCategorisee['estguidee'];

                    require_once './pages-info-offres/visite.php';

                    break;

                case "Parc attraction":
                    $queryOffreCategorisee = 'SELECT * FROM ' . NOM_SCHEMA . '.' . VUE_PARC_ATTRACTIONS . 'WHERE idoffre = :offerId';
                    $sthOffreCategorisee = $dbh->prepare($queryOffreCategorisee);
                    $sthOffreCategorisee->bindParam(':offerId', $offerId, PDO::PARAM_STR);
                    $sthOffreCategorisee->execute();
                    $offreCategorisee = $sthOffreCategorisee->fetch(PDO::FETCH_ASSOC);

                    $mapUrl = $offreCategorisee['urlversplan'];
                    $nbAttractions = $offreCategorisee['nbattractions'];
                    $ageRequierement = $offreCategorisee['ageminparc'];

                    require_once './pages-info-offres/parc-attraction.php';

                    break;

                case "Spectacle":
                    $queryOffreCategorisee = 'SELECT * FROM ' . NOM_SCHEMA . '.' . VUE_SPECTACLE . ' WHERE idoffre = :offerId';
                    $sthOffreCategorisee = $dbh->prepare($queryOffreCategorisee);
                    $sthOffreCategorisee->bindParam(':offerId', $offerId, PDO::PARAM_STR);
                    $sthOffreCategorisee->execute();
                    $offreCategorisee = $sthOffreCategorisee->fetch(PDO::FETCH_ASSOC);

                    $duration = $offreCategorisee['dureespectacle'];
                    $nbSeats = $offreCategorisee['placesspectacle'];

                    require_once './pages-info-offres/spectacle.php';

                    break;
            }

            require_once "./afficher_avis.inc.php";
            require_once "./footer_inc.html";
            ?>
        </body>

        </html>
        ?>
        <div id="liste_avis">
            <?php
            afficher_liste_avis($offerId);
            ?>
        </div>
<?php
    } else {
        echo "No offer found with the specified ID.";
    }
} else {
    echo "No offer ID provided in the URL.";
}
