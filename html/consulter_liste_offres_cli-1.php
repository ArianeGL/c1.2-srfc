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
    <?php require_once 'header_inc.php'; ?>

    <!-- Main content -->
    <main>

        <nav>
            <div>
                <svg height="30px" fill="#254766" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 385.00 385.00" xml:space="preserve" stroke="#000000" stroke-width="0.00385" transform="rotate(0)"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#9ABBDBCCCCCC" stroke-width="0.77"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M342.598,42.402C315.254,15.058,278.899-0.001,240.229,0c-0.002,0,0,0-0.002,0c-38.666,0-75.025,15.06-102.368,42.402 c-27.343,27.344-42.402,63.7-42.402,102.37c0,26.388,7.018,51.696,20.161,73.801L10.252,323.938C3.642,330.55,0,339.34,0,348.69 c0,9.35,3.641,18.14,10.252,24.75l1.307,1.307C18.17,381.359,26.96,385,36.311,385s18.14-3.641,24.751-10.252l105.365-105.366 c22.104,13.144,47.413,20.161,73.801,20.161c38.67,0,75.026-15.059,102.37-42.402C369.942,219.798,385,183.442,385,144.772 C385,106.102,369.943,69.747,342.598,42.402z M43.384,357.07c-1.89,1.89-4.402,2.93-7.074,2.93c-2.671,0-5.183-1.041-7.073-2.93 l-1.308-1.309c-1.889-1.889-2.93-4.4-2.93-7.071c0-2.673,1.041-5.185,2.93-7.074l102.489-102.488 c2.369,2.748,4.849,5.421,7.44,8.013c2.591,2.592,5.265,5.072,8.013,7.44L43.384,357.07z M324.92,229.463 c-22.622,22.622-52.7,35.08-84.691,35.08c-31.992,0-62.069-12.458-84.69-35.08c-22.622-22.622-35.081-52.699-35.08-84.69 c0-31.993,12.458-62.07,35.08-84.692s52.698-35.081,84.69-35.08c31.993,0,62.07,12.458,84.692,35.08s35.081,52.7,35.08,84.692 C360,176.764,347.542,206.841,324.92,229.463z"></path> </g> </g></svg>
                <input placeholder="Rechercher une offre"></input>
            </div>

            <div>
                <button id="filterButton" class="smallButton">Filtrer</button>
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
                    <button id="retirerFiltres">Enlever les fitres</button>
                </fieldset>
                <button class="smallButton">Trier</button>
            </div>
        </nav>

        <section>
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
                <article onclick="loadInfoOffre('<?php echo $offre['idoffre']; ?>')">
                    <div>
                        <h3><?php echo $offre['nomoffre']; ?></h3>
                        <section class="art-header">
                            <h3><?php echo $offre['categorie']; ?></h3>
                            <div>
                                <p>5/5<?php echo $requeteCompteAvis['nbavis'] ?></p>
                            </div>
                            <p><?php echo $offre['prixmin']; ?> &#8364;</p>
                        </section>
                    </div>
                    <div>
                        <!-- <?php echo $offre['urlversimage']; ?> -->
                        <img src="https://photographe-en-herbe.com/wp-content/uploads/2019/03/paysage-montagne-photographe-en-herbe-1024x576.jpg" alt="Nom_image" class="clopArtImg">
                        
                        <h4><?php echo $offre['villeoffre']; ?></h4>
                        
                        <div class="fade-out-container">
                            <p><?php echo $offre['resume']; ?></p>
                        </div>
                        
                        <p class="clopDeno"><?php echo $offre['denomination']; ?></p>
                    </div>
                </article>
            <?php
            }
            ?>
        </section>
        
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

    let retirerFiltres = document.querySelector("#retirerFiltres");
    retirerFiltres.addEventListener('click', () => {
        window.location.href = `consulter_liste_offres_cli-1.php`;
    });
    
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
