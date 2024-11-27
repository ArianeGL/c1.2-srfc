<?php
session_start();
require_once "db_connection.inc.php";

try {
    global $dbh;
    // Récupère le tri
    $triOption = isset($_GET['tri']) ? $_GET['tri'] : null;
    $ordreTri = "";
    $ListeTris = ['noteCroissante', 'noteDecroissante'];
    switch ($triOption) {
        case 'noteCroissante':
            $ordreTri = "ORDER BY note ASC"; 
            break;
        case 'noteDecroissante':
            $ordreTri = "ORDER BY note DESC"; 
            break;
        default:
            $ordreTri = ""; 
    }

    /*
    // requete offre
    $query1 = 'SELECT * FROM '.NOM_SCHEMA.'._offre NATURAL JOIN '.NOM_SCHEMA.'._compteProfessionnel';// INNER JOIN NOM_SCHEMA._imageoffre    sae._offre NATURAL JOIN sae._compteProfessionnel
    $sth1 = $dbh->prepare($query1);
    $sth1->execute();
    $requeteOffres = $sth1->fetchAll();
    $requeteOffres = $dbh->query('SELECT * FROM sae._compte', PDO::FETCH_ASSOC);
    print_r($requeteOffres);
    */
} catch (PDOException $e) {
    die("SQL Query failed : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./styles/clop.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
    <script src="main.js"></script>
    <script src="recherche.js"></script>

    <script>
        function loadInfoOffre(idoffre) {
            location.href = `informations_offre-1.php?idoffre=${idoffre}`;
        }

        alert(articles[0].firstElementChild.children[1].innerHTML);

        let clopButtonsRedirige = document.getElementsByClassName("clopButton");
        clopButtonsRedirige[0].addEventListener("click", loadCreaOffre);
        clopButtonsRedirige[1].addEventListener("click", loadSesOffresPro);
        /*
        for (article in articles){
            let nom = document.querySelector();
            article.addEventListener("click",)
        }
        */
    </script>


    <title>PACT</title>
</head>

<body>
    <?php require_once 'header_inc.html'; ?>


    <!-- Main content -->
    <main id="clop">
        <div>
            <p>
                Bonjour <?php echo $_SESSION['identifiant']; ?>, bienvenue sur votre compte professionnel
            </p>
        </div>
        <button onclick="loadCreaOffre()" class="clopButton">Créer une offre</button>
        <button onclick="loadSesOffresPro()" class="clopButton">Afficher vos offres</button>
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
                <select id="SelectionTri" onchange="triOffre()">
                    <option value="" disabled selected>TRIS</option>
                    <option value="noteCroissante">Note (↑)</option>
                    <option value="noteDecroissante">Note (↓)</option>
                 </select>
</div>

<script>
    function triOffre() {
        const optionTri = document.getElementById('SelectionTri').value;
        const urlActuelle = new URL(window.location.href);
        urlActuelle.searchParams.set('tri', optionTri); // Met à jour le paramètre "tri"
        window.location.href = urlActuelle; // Redirige avec la nouvelle URL
    }
</script>


        <div id="clopRangement">
            <?php
             $query1 = 'SELECT * FROM ' . NOM_SCHEMA . '._offre NATURAL JOIN ' . NOM_SCHEMA . '._compteProfessionnel ' . $ordreTri;
            foreach ($dbh->query($query1, PDO::FETCH_ASSOC) as $offre) {
                $requeteCompteAvis['nbavis'] = "";
                /*
                    try {
                        $query3 = "SELECT COUNT(*) AS nbavis FROM ".NOM_SCHEMA."._offre o INNER JOIN ".NOM_SCHEMA."._avis a ON o.idoffre = a.idoffre WHERE idoffre=".$offre['idoffre'];
                        $sth3 = $dbh->prepare($query3);
                        $sth3->execute();
                        $requeteCompteAvis = $sth3->fetchAll();
                    } catch (PDOException $e) {
                        die("SQL Query failed : " . $e->getMessage());
                    }
                    */
            ?>
                <article id="clopArt" onclick="loadInfoOffre('<?php echo $offre['idoffre']; ?>')">
                    <div>
                        <p class="clopCat"><?php echo $offre['categorie']; ?></p>
                        <p class="clopTitre"><?php echo $offre['nomoffre']; ?></p>
                        <p class="clopPrix"><?php echo $offre['prixmin']; ?> &#8364;</p>
                    </div>
                    <div>
                        <!-- <?php echo $offre['urlversimage']; ?> -->
                        <img src="https://photographe-en-herbe.com/wp-content/uploads/2019/03/paysage-montagne-photographe-en-herbe-1024x576.jpg" alt="Nom_image" class="clopArtImg">
                        <div class="clopArtDroite">
                            <p class="clopVille"><?php echo $offre['villeoffre']; ?></p>
                            <p class="clopResume"><?php echo $offre['resume']; ?></p>
                            <div>
                                <img src="notation.png" alt="Systeme notation">
                                <p><?php echo $requeteCompteAvis['nbavis'] ?></p>
                            </div>
                            <p class="clopDeno"><?php echo $offre['denomination']; ?></p>
                        </div>
                    </div>
                </article>
            <?php
            }
            ?>
        </div>

    </main>

    <?php require_once "footer_inc,html"; ?>

</body>

</html>
