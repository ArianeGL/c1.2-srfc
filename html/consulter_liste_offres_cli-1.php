<?php
session_start();
include('db_connection.inc.php');
try {
    global $dbh;
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
    <script>
        function loadInfoOffre(idoffre) {
            window.location.href = `informations_offre-1.php?idoffre=${idoffre}`;
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
    <header>
        <div id="homeButtonID" class="homeButton">
            <img src="./IMAGES/LOGO-SRFC.webp" alt="HOME PAGE" height="80%" style="margin-left: 5%; margin-right: 5%;">
            <h2>PACT</h2>
            <p id="slogan" class="sloganHide">Des avis qui comptent, des voyages qui marquent.</p>
        </div>
        <div>
            <div class="container">
                <button class="buttons header-button1">
                    <h4>Offres</h4>
                </button>

                <!-- Button for back office -->
                <button class="buttons header-button2">
                    <h4>Factures</h4>
                </button>

                <!-- Button for front office -->
                <button style="display: none;" class="buttons header-button2">
                    <h4>R&eacute;cent</h4>
                </button>

                <button class="buttons header-button3">
                    <h4>Compte</h4>
                </button>
            </div>
            <div class="indicator">
                <div id="div1" class="hidden"></div>
                <div id="div2" class="hidden"></div>
                <div id="div3" class="hidden"></div>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main id="clop">

        <div class="clopRecherche">
            <button class="searchButton"><img src="blabla.png" alt="Rechercher"></button>
            <input id="searchText" placeholder="Rechercher une offre"></input>
            <div id="clopFiltre">
                <button id="filterButton">Filtrer</button>
                <fieldset id="filterOptions">
                    <h3>Par Catégorie :</h3>
                    
                        <?php
                        $query1 = 'SELECT * FROM ' . NOM_SCHEMA . '._offre NATURAL JOIN ' . NOM_SCHEMA . '._compteProfessionnel';
                        if (isset($_GET['categorie'])){
                            if ($_GET['categorie'] !== '' && $_GET['categorie'] !== 'avpsr'){
                                $filtre_cat = "";
                            
                                $categorie = $_GET['categorie'];

                                if (str_contains($categorie,'a')) {
                                    ?>
                                    <label for="activite">
                                        <input type="checkbox" id="activite" name="activite" value="activite" checked />Activit&eacute;</label>
                                    <?php
                                    if ($filtre_cat === ""){
                                        $filtre_cat = " WHERE categorie='Activite'";
                                    } else {
                                        $filtre_cat = $filtre_cat . " OR categorie='Activite'";
                                    }
                                } else {
                                    ?>
                                    <label for="activite">
                                        <input type="checkbox" id="activite" name="activite" value="activite" />Activit&eacute;</label>
                                    <?php
                                }

                                if (str_contains($categorie,'v')) {
                                    ?>
                                    <label for="visite">
                                        <input type="checkbox" id="visite" name="visite" value="visite" checked />Visite</label>
                                    <?php
                                    if ($filtre_cat === ""){
                                        $filtre_cat = " WHERE categorie='Visite'";
                                    } else {
                                        $filtre_cat = $filtre_cat . " OR categorie='Visite'";
                                    }
                                } else {
                                    ?>
                                    <label for="visite">
                                        <input type="checkbox" id="visite" name="visite" value="visite" />Visite</label>
                                    <?php
                                }

                                if (str_contains($categorie,'p')) {
                                    ?>
                                    <label for="parcAttraction">
                                        <input type="checkbox" id="parcAttraction" name="parcAttraction" value="parcAttraction" checked />Parc d'Attraction</label>
                                    <?php
                                    if ($filtre_cat === ""){
                                        $filtre_cat = " WHERE categorie='Parc attraction'";
                                    } else {
                                        $filtre_cat = $filtre_cat . " OR categorie='Parc attraction'";
                                    }
                                } else {
                                    ?>
                                    <label for="parcAttraction">
                                        <input type="checkbox" id="parcAttraction" name="parcAttraction" value="parcAttraction" />Parc d'Attraction</label>
                                    <?php
                                }

                                if (str_contains($categorie,'s')) {
                                    ?>
                                    <label for="spectacle">
                                        <input type="checkbox" id="spectacle" name="spectacle" value="spectacle" checked />Spectacle</label>
                                    <?php
                                    if ($filtre_cat === ""){
                                        $filtre_cat = " WHERE categorie='Spectacle'";
                                    } else {
                                        $filtre_cat = $filtre_cat . " OR categorie='Spectacle'";
                                    }
                                } else {
                                    ?>
                                    <label for="spectacle">
                                        <input type="checkbox" id="spectacle" name="spectacle" value="spectacle" />Spectacle</label>
                                    <?php
                                }

                                if (str_contains($categorie,'r')) {
                                    ?>
                                    <label for="restauration">
                                        <input type="checkbox" id="restauration" name="restauration" value="restauration" checked />Restauration</label>
                                    <?php
                                    if ($filtre_cat === ""){
                                        $filtre_cat = " WHERE categorie='Restauration'";
                                    } else {
                                        $filtre_cat = $filtre_cat . " OR categorie='Restauration'";
                                    }
                                } else {
                                    ?>
                                    <label for="restauration">
                                        <input type="checkbox" id="restauration" name="restauration" value="restauration" />Restauration</label>
                                    <?php
                                }
                            
                                $query1 = $query1 . $filtre_cat;
                            }
                        } else {
                            ?>
                            <label for="activite">
                                <input type="checkbox" id="activite" name="activite" value="activite" />Activit&eacute;</label>
                            <label for="visite">
                                <input type="checkbox" id="visite" name="visite" value="visite" />Visite</label>
                            <label for="parcAttraction">
                                <input type="checkbox" id="parcAttraction" name="parcAttraction" value="parcAttraction" />Parc d'Attraction</label>
                            <label for="spectacle">
                                <input type="checkbox" id="spectacle" name="spectacle" value="spectacle" />Spectacle</label>
                            <label for="restauration">
                                <input type="checkbox" id="restauration" name="restauration" value="restauration" />Restauration</label>
                            <?php
                        }
                        ?>
                </fieldset>
            </div>
            <button class="sortButton">Trier</button>
        </div>

        <div id="clopRangement">
            <?php
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

    <?php require_once "footer_inc.html"; ?>

</body>
<script>
    let bouton_filtre = document.querySelector("#filterButton");
    let champs_filtres = document.querySelector("#filterOptions");
    bouton_filtre.addEventListener("click", hideAndShow);
    
    document.addEventListener('click', (e) => {
        if (!bouton_filtre.contains(e.target) && !champs_filtres.contains(e.target)) {
            champs_filtres.style.display = 'none';
        }
    });

    function hideAndShow(){
        const isVisible = window.getComputedStyle(champs_filtres).display === 'block';
        champs_filtres.style.display = isVisible ? 'none' : 'block';
    }
    var activite = document.querySelector("#activite");
    var visite = document.querySelector("#visite");
    var parc_attraction = document.querySelector("#parcAttraction");
    var spectacle = document.querySelector("#spectacle");
    var restauration = document.querySelector("#restauration");

    activite.addEventListener("click", categorie_filter);
    visite.addEventListener("click", categorie_filter);
    parc_attraction.addEventListener("click", categorie_filter);
    spectacle.addEventListener("click", categorie_filter);
    restauration.addEventListener("click", categorie_filter);

    function categorie_filter(){
        // get changed element
        // refresh and apply change: load(url)
        // get current url: window.location.href

        let cible = 'categorie';

        let url = new URL(window.location.href);
        let params = url.searchParams;

        let categorie_query = "";

        if ((activite.checked)){
            categorie_query += "a";
        }
        if ((visite.checked)){
            categorie_query += "v";
        }
        if ((parc_attraction.checked)){
            categorie_query += "p";
        }
        if ((spectacle.checked)){
            categorie_query += "s";
        }
        if ((restauration.checked)){
            categorie_query += "r";
        }
        if (categorie_query === "" || categorie_query === "avpsr"){
            params.delete(cible);
        } else {
            params.set(cible, categorie_query);
        }
        
        window.location.href = url.toString();
    }
</script>
</html>
