<?php
require_once("../db_connection.inc.php");
require_once "../includes/consts.inc.php";

session_start();

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
            <title><?php echo $name; ?></title>
            <link rel="stylesheet" href="./styles/infos-offres.css">
            <link rel="icon" type="image/x-icon" href="favicon.ico">

            <script src="../includes/main.js"></script>
        </head>

        <body>
            <?php require_once HEADER;
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
                    require_once './crea_avis.php';

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
                    require_once './crea_avis_re.php';

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
                    require_once './crea_avis.php';

                    break;

                case "Parc attraction":
                    $queryOffreCategorisee = 'SELECT * FROM ' . NOM_SCHEMA . '.' . VUE_PARC_ATTRACTIONS . ' WHERE idoffre = :offerId';
                    $sthOffreCategorisee = $dbh->prepare($queryOffreCategorisee);
                    $sthOffreCategorisee->bindParam(':offerId', $offerId, PDO::PARAM_STR);
                    $sthOffreCategorisee->execute();
                    $offreCategorisee = $sthOffreCategorisee->fetch(PDO::FETCH_ASSOC);

                    $mapUrl = $offreCategorisee['urlversplan'];
                    $nbAttractions = $offreCategorisee['nbattractions'];
                    $ageRequierement = $offreCategorisee['ageminparc'];

                    require_once './pages-info-offres/parc-attraction.php';
                    require_once './crea_avis.php';

                    break;

                case "Spectacle":
                    $queryOffreCategorisee = 'SELECT * FROM sae.spectacle WHERE idoffre = :offerId';
                    $sthOffreCategorisee = $dbh->prepare($queryOffreCategorisee);
                    $sthOffreCategorisee->bindParam(':offerId', $offerId, PDO::PARAM_STR);
                    $sthOffreCategorisee->execute();
                    $offreCategorisee = $sthOffreCategorisee->fetch(PDO::FETCH_ASSOC);
    
                    $duration = $offreCategorisee['dureespectacle'];
                    $nbSeats = $offreCategorisee['placesspectacle'];
    
                    require_once './pages-info-offres/spectacle.php';
                    require_once './crea_avis.php';
    
                    break;
            }
            require_once "../includes/afficher_avis.inc.php";
            ?>
            <?php
            if (can_post($offerId)) {
                afficher_form_avis($offerId);
            }
            ?>
            <div>
                <hr style="border: none; border-top: 2px solid var(--navy-blue); margin: 20px; margin-left: 0px;">
                <?php afficher_liste_avis($offerId); ?>
            </div>
            </main>
            <?php require_once FOOTER; ?>
        </body>

        </html>
<?php
    } else {
        echo "No offer found with the specified ID.";
    }
} else {
    echo "No offer ID provided in the URL.";
}
