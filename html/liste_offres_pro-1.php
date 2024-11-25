<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/header_footer.css">
    <link rel="stylesheet" href="./styles/liste_offres_pro.css">
    <link rel="stylesheet" href="./styles/recherche.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

    <script src="main.js"></script>
    <script src="recherche.js"></script>
    
    <title>PACT - Mes Offres</title>
</head>

<body>
    <?php require_once 'header_inc.html'; ?>

    <main id="top">
        <h1>Mes Offres</h1>
        <button type="button" class="button-creer">
            <h4>Cr&eacute;er une offre</h4>
        </button>
        <?php
        session_start();

        function get_account_id()
        {
            global $dbh;

            $query = "SELECT idcompte FROM " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . " WHERE email = '" . $_SESSION['identifiant'] . "';";
            $id = $dbh->query($query)->fetch();

            return $id['idcompte'];
        }

        const IMAGE_DIR = "./images_importees/";

        require_once "db_connection.inc.php";
        global $dbh;
        require_once "verif_connection.inc.php";

        //$_SESSION['id'] = "Co-0004"; //test, ligne a supprimer
        if (isset($_SESSION['identifiant']) && valid_account()) {
            $id_compte = get_account_id();
            $query = "SELECT * FROM " . NOM_SCHEMA . "." . NOM_TABLE_OFFRE . " WHERE idcompte = '" . $id_compte . "';";

            try {
                $offres = $dbh->query($query)->fetchAll();
            } catch (PDOException $e) {
                die("SQL Query error : " . $e->getMessage());
            }

            ?> 
            <div class="barre_recherche">
            <input type="text" id="rechercheOffre" placeholder="Rechercher offre..." onkeyup="rechercheOffre()">
            <div class="filtre">
                <!-- A ajouter dans le select pour les filtres : id="SelectionFiltre" onchange="filtreOffre()" -->
                <select>
                    <option value="" disabled selected>FILTRES</option>
                    <option value="categorie1">Filtre 1</option>
                    <option value="categorie2">Filtre 2</option>
                </select>
            </div>
            <div class="tri">
                <!-- A ajouter dans le select pour les tris : id="SelectionTri" onchange="triOffre()" -->
                <select>
                    <option value="" disabled selected>TRIS</option>
                    <option value="">Tri 1</option>
                    <option value="">Tri 2</option>
                </select>
            </div>
        </div>
        <div id="listeOffres">
    <?php foreach ($offres as $offre) { ?>
        <div class="offre">
            <div class="upper_row">
                <p><?php echo $offre['categorie']; ?></p>
                <p class="nom_offre"><?php echo $offre['nomoffre']; ?></p>
            </div>
            <?php
            $image = scandir(IMAGE_DIR . $offre['idoffre'])[2]; // Récupère la première image du dossier contenant les images de l'offre
            ?>
            <div class="lower_row">
                <img src="<?php echo IMAGE_DIR . $offre['idoffre'] . "/" . $image; ?>" alt="apercu de l'offre">
                <div class="info">
                    <p><?php echo $offre['villeoffre']; ?></p>
                    <button class="button-facture" type="button">Factures</button>
                </div>
                <p><?php echo $offre['resume']; ?></p>
            </div>
        </div>
    <?php } ?>
</div>
<?php
        } else {
            echo "<script>location.href='./1_connexionPro.php'</script>";
        }
        ?>
    </main>


    <?php require_once "footer_inc,html"; ?>
</body>

</html>
