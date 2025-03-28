<?php
session_start();
require_once "../includes/consts.inc.php";
include('../db_connection.inc.php');

function get_account_id()
{
    global $dbh;

    $query = "SELECT idcompte FROM " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . " WHERE email = '" . $_SESSION['identifiant'] . "';";
    $id = $dbh->query($query)->fetch();

    return $id['idcompte'];
}

try {
    global $dbh;
} catch (PDOException $e) {
    die("SQL Query failed : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en" id="liste_offre">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../includes/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>

        <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin="">
    </script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

    <script src="../includes/main.js"></script>
    <script src="../scripts/recherche.js"></script>
    <script>
            function loadInfoOffre(idoffre) {
            window.location.href = `informations.php?idoffre=${idoffre}`;
        }
    </script>

    <title>PACT</title>
</head>

<body>
    <?php require_once HEADER; ?>
    <!-- Main content -->
    <main style="margin-bottom: 700px;">
        <?php if(isset($_SESSION['identifiant']) && est_pro($_SESSION['identifiant'])){ ?>
            <button style="margin-bottom:30px" class="button" onclick="window.location='creation.php'">Créer une offre</button>
        <?php } ?>
        <nav>   
            <div>
                <svg height="30px" fill="#254766" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 385.00 385.00" xml:space="preserve" stroke="#000000" stroke-width="0.00385" transform="rotate(0)">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#9ABBDBCCCCCC" stroke-width="0.77"></g>
                    <g id="SVGRepo_iconCarrier">
                        <g>
                            <path d="M342.598,42.402C315.254,15.058,278.899-0.001,240.229,0c-0.002,0,0,0-0.002,0c-38.666,0-75.025,15.06-102.368,42.402 c-27.343,27.344-42.402,63.7-42.402,102.37c0,26.388,7.018,51.696,20.161,73.801L10.252,323.938C3.642,330.55,0,339.34,0,348.69 c0,9.35,3.641,18.14,10.252,24.75l1.307,1.307C18.17,381.359,26.96,385,36.311,385s18.14-3.641,24.751-10.252l105.365-105.366 c22.104,13.144,47.413,20.161,73.801,20.161c38.67,0,75.026-15.059,102.37-42.402C369.942,219.798,385,183.442,385,144.772 C385,106.102,369.943,69.747,342.598,42.402z M43.384,357.07c-1.89,1.89-4.402,2.93-7.074,2.93c-2.671,0-5.183-1.041-7.073-2.93 l-1.308-1.309c-1.889-1.889-2.93-4.4-2.93-7.071c0-2.673,1.041-5.185,2.93-7.074l102.489-102.488 c2.369,2.748,4.849,5.421,7.44,8.013c2.591,2.592,5.265,5.072,8.013,7.44L43.384,357.07z M324.92,229.463 c-22.622,22.622-52.7,35.08-84.691,35.08c-31.992,0-62.069-12.458-84.69-35.08c-22.622-22.622-35.081-52.699-35.08-84.69 c0-31.993,12.458-62.07,35.08-84.692s52.698-35.081,84.69-35.08c31.993,0,62.07,12.458,84.692,35.08s35.081,52.7,35.08,84.692 C360,176.764,347.542,206.841,324.92,229.463z"></path>
                        </g>
                    </g>
                </svg>
                <input type="text" id="rechercheOffre" placeholder="Rechercher une offre" onkeyup="rechercheOffreConsultation()"></input>
            </div>

            <div id="buttonContainer">
                <button id="filterButton" class="smallButton">Filtrer</button>
                <fieldset id="filterOptions">
                    <h3>Par Catégorie :</h3>

                    <div id="filtre_cat">
                        <div>
                            <label for="activite">
                                <input type="checkbox" id="activite" name="activite" value="activite" />Activit&eacute;</label>
                        </div>
                        <div>
                            <label for="visite">
                                <input type="checkbox" id="visite" name="visite" value="visite" />Visite</label>
                        </div>
                        <div>
                            <label for="parcAttraction">
                                <input type="checkbox" id="parcAttraction" name="parcAttraction" value="parcAttraction" />Parc d'Attraction</label>
                        </div>
                        <div>
                            <label for="spectacle">
                                <input type="checkbox" id="spectacle" name="spectacle" value="spectacle" />Spectacle</label>
                        </div>
                        <div>
                            <label for="restauration">
                                <input type="checkbox" id="restauration" name="restauration" value="restauration" />Restauration</label>
                        </div>
                        <button class="smallButton" id="retirerFiltreCat">Enlever le filtre</button>
                    </div>

                    <h3>Par Note minimale :</h3>

                    <div id="filtre_note">
                        <div>
                            <input type="radio" id="sup_a_1" name="fil_note" value="1" />
                            <label for="sup_a_1">
                                <h1>&gt; 1</h1>
                            </label>
                        </div>
                        <div>
                            <input type="radio" id="sup_a_2" name="fil_note" value="2" />
                            <label for="sup_a_2">
                                <h1>&gt; 2</h1>
                            </label>
                        </div>
                        <div>
                            <input type="radio" id="sup_a_3" name="fil_note" value="3" />
                            <label for="sup_a_3">
                                <h1>&gt; 3</h1>
                            </label>
                        </div>
                        <div>
                            <input type="radio" id="sup_a_4" name="fil_note" value="4" />
                            <label for="sup_a_4">
                                <h1>&gt; 4</h1>
                            </label>
                        </div>
                        <button class="smallButton" id="retirerFiltreNote">Enlever le filtre</button>
                    </div>

                    <h3>Par Prix :</h3>

                    <div id="filtre_prix">
                        <div>
                            <p>Minimal :</p>
                            <input type="range" id="slider_min" min="0" max="100" value="0">
                            <input type="number" id="number_min" min="0" max="100" value="0">
                        </div>
                        <div>
                            <p>Maximal :</p>
                            <input type="range" id="slider_max" min="0" max="100" value="100">
                            <input type="number" id="number_max" min="0" max="100" value="100">
                        </div>
                    </div>
                    <button class="smallButton" id="retirerFiltrePrix">Enlever le filtre</button>

                    <button class="smallButton" id="retirerFiltres">Retirer tous les fitres</button>
                </fieldset>
                <div class="tri">
                    <?php
                    $triOption = isset($_GET['tri']) ? $_GET['tri'] : null;
                    $ListeTris = ['noteCroissante', 'noteDecroissante'];
                    switch ($triOption) {
                        case 'noteCroissante':
                            $ordreTri = ' ORDER BY ' . NOM_TABLE_OFFRE . '.note ASC';
                            break;
                        case 'noteDecroissante':
                            $ordreTri = ' ORDER BY ' . NOM_TABLE_OFFRE . '.note DESC';
                            break;
                        case 'prixCroissant':
                            $ordreTri = ' ORDER BY ' . NOM_TABLE_OFFRE . '.prixmin ASC';
                            break;
                        case 'prixDecroissant':
                            $ordreTri = ' ORDER BY ' . NOM_TABLE_OFFRE . '.prixmin DESC';
                            break;
                        default:
                            $ordreTri = '';
                    }

                    ?>

                    <select id="SelectionTri" class="smallButton" onchange="triOffre()">
                        <option value="" disabled selected>Tris</option>
                        <option value="noteCroissante">Note Croissante</option>
                        <option value="noteDecroissante">Note D&eacute;croissante</option>
                        <option value="prixCroissant">Prix Croissant</option>
                        <option value="prixDecroissant">Prix D&eacute;croissant</option>
                        <option value="retireTri">Retirer Tri</option>
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
            </div>
        </nav>
        
        <div id="map"></div>
        <section>
            <?php
            $query1 = '
            SELECT * FROM ' . NOM_SCHEMA . '._offre 
            NATURAL JOIN ' . NOM_SCHEMA . '._compteProfessionnel' . $ordreTri;
            
            if (est_pro(get_account_id())) {
                $filtre_cat = " WHERE idcompte = '" . get_account_id() . "'";
                $query1 = $query1 . $filtre_cat;
                $query1 = $query1 . $ordreTri;
            }
            ?><script>
            var markers = L.markerClusterGroup();
            var redIcon = L.icon({ 
                iconUrl: '../IMAGES/pinMapRouge.png',

                iconSize:     [45, 55], // size of the icon
                shadowSize:   [50, 64], // size of the shadow
                iconAnchor:   [23, 20], // point of the icon which will correspond to marker's location
                shadowAnchor: [4, 62],  // the same for the shadow
                popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
            });

            var blackIcon = L.icon({ 
                iconUrl: '../IMAGES/pinMapNoir.png',

                iconSize:     [45, 55], // size of the icon
                shadowSize:   [50, 64], // size of the shadow
                iconAnchor:   [23, 20], // point of the icon which will correspond to marker's location
                shadowAnchor: [4, 62],  // the same for the shadow
                popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
            });

            var greenIcon = L.icon({ 
                iconUrl: '../IMAGES/pinMapVert.png',

                iconSize:     [45, 55], // size of the icon
                shadowSize:   [50, 64], // size of the shadow
                iconAnchor:   [23, 20], // point of the icon which will correspond to marker's location
                shadowAnchor: [4, 62],  // the same for the shadow
                popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
            });

            var purpleIcon = L.icon({ 
                iconUrl: '../IMAGES/pinMapViolet.png',

                iconSize:     [45, 55], // size of the icon
                shadowSize:   [50, 64], // size of the shadow
                iconAnchor:   [23, 20], // point of the icon which will correspond to marker's location
                shadowAnchor: [4, 62],  // the same for the shadow
                popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
            });

            var blueIcon = L.icon({ 
                iconUrl: '../IMAGES/pinMapBleu.png',

                iconSize:     [45, 55], // size of the icon
                shadowSize:   [50, 64], // size of the shadow
                iconAnchor:   [23, 20], // point of the icon which will correspond to marker's location
                shadowAnchor: [4, 62],  // the same for the shadow
                popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
            });
        </script><?php
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
                        $query = "SELECT * FROM " . NOM_SCHEMA . ".option WHERE idoffre = :idoffre";
                        $sth = $dbh->prepare($query);
                        $sth->bindParam(':idoffre', $offre['idoffre']);
                        $sth->execute();
                        $result = $sth->fetchColumn();
                        
                        if ($result != 0) {
                            ?>
                    <article class="relief art-offre" onclick="loadInfoOffre('<?php echo $offre['idoffre']; ?>')" data-categorie="<?php echo $offre['categorie']; ?>" data-note="<?php echo $offre['note']; ?>" data-prix="<?php echo $offre['prixmin']; ?>">
                        <?php
                } else {
                    ?>
                        <article class="art-offre" onclick="loadInfoOffre('<?php echo $offre['idoffre']; ?>')" data-categorie="<?php echo $offre['categorie']; ?>" data-note="<?php echo $offre['note']; ?>" data-prix="<?php echo $offre['prixmin']; ?>">
                        <?php
                    }
                    ?>
                        <div>
                            <h3 class="clopTitre"><?php echo $offre['nomoffre']; ?></h3>
                            <h3><?php echo $offre['note'] . "/5" ?></h3>
                            <section class="art-header">
                                <h3><?php echo $offre['categorie']; ?></h3>
                                <p><?php echo $offre['prixmin']; ?> &#8364;</p>
                            </section>
                        </div>
                        <div>
                            <?php
                            $query_image = 'SELECT urlimage FROM ' . NOM_SCHEMA . '.' . NOM_TABLE_OFFRE . ' NATURAL JOIN ' . NOM_SCHEMA . '.' . NOM_TABLE_IMGOF . ' WHERE idoffre=\'' . $offre['idoffre'] . '\'';
                            $images = $dbh->query($query_image)->fetch(); ?>
                            <img src="<?php echo $images[0]; ?>" alt="Nom_image" class="clopArtImg">
                            
                            <h4 id="clopVille"><?php echo $offre['villeoffre']; ?></h4>
                            
                            <div class="fade-out-container">
                                <p><?php echo $offre['resume']; ?></p>
                            </div>
                            
                            <p class="clopDeno"><?php echo $offre['denomination']; ?></p>
                        </div>
                    </article>
                    
                    <?php
                        $popupContent = '<div>';
                        $popupContent .= '<h3>' . htmlspecialchars($offre['nomoffre']) . '</h3>';
                        $popupContent .= '<p>Prix: ' . htmlspecialchars($offre['prixmin']) . ' €</p>';
                        $popupContent .= '<p>Note: ' . htmlspecialchars($offre['note']) . '/5</p>';
                        $popupContent .= '<p>Résumé: ' . htmlspecialchars($offre['resume']) . '</p>';
                        $popupContent .= '<a href="informations.php?idoffre=' . $offre['idoffre'] . '">Voir les détails</a>';
                        $popupContent .= '</div>';
                        
                        $iconType = "greenIcon"; // Par défaut

                        // Déterminez l'icône en fonction de la catégorie
                        switch($offre['categorie']) {
                            case 'Spectacle':
                                $iconType = "blueIcon";
                                break;
                            case 'Restauration':
                                $iconType = "redIcon";
                                break;
                            case 'Activite':
                                $iconType = "purpleIcon";
                                break;
                            case 'Parc attraction':
                                $iconType = "blackIcon";
                                break;
                            case 'Visite':
                                $iconType = "greenIcon";
                                break;
                        }
            
                        ?>
                    <script>
                        marqueur=L.marker([<?php echo $offre['latitude']?>, <?php echo $offre['longitude']?>], {icon: <?php echo $iconType; ?>})
                                .bindPopup(`<?php echo addslashes($popupContent);
                                ?>`);
                        marqueur["data-categorie"]="<?php echo $offre['categorie']?>";
                        marqueur["data-note"]="<?php echo $offre['note']?>";
                        marqueur["data-prix"]="<?php echo $offre['prixmin']?>";
                        marqueur["cat_hidden"]=false;
                        marqueur["note_hidden"]=false;
                        marqueur["prix_hidden"]=false;
                        markers.addLayer(marqueur);
                    </script>
                    <?php

                }
                
                ?>
        </section>
        
    </main>
    
    <?php require_once FOOTER; ?>
    
</body>
<?php
        $adresse = "9 la tégrie, Saint hilaire de loulay";
        $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($adresse) . "&format=json&limit=1";
        
        // Préparer l'en-tête HTTP pour respecter la politique d'utilisation de Nominatim
        $options = [
            "http" => [
                "header" => "User-Agent: MonApp/1.0 (contact@exemple.com)\r\n"
            ]
        ];
        $context = stream_context_create($options);
        
        // Faire la requête
        $response = file_get_contents($url, false, $context);
        $data = json_decode($response, true);
        
        if (!empty($data)) {
            $latitude = $data[0]['lat'];
            $longitude = $data[0]['lon'];
        }
        
        ?>
    <script>

        //markers.push(L.marker([<?php echo "$latitude, $longitude" ?>]));

        var map = L.map('map').setView([48.6, -3.0], 8.5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

        
        map.addLayer(markers);
        var lstMarkers = markers.getLayers();

        var legend = L.control({ position: 'bottomright' });

        legend.onAdd = function (map) {
            var div = L.DomUtil.create('div', 'info legend'); 
            var categories = ['Spectacle', 'Restauration', 'Activite', 'Parc attraction', 'Visite'];
            var labels = ['<strong>Catégories</strong>']; 
            function getIconUrl(category) {
                switch(category) {
                    case 'Spectacle':       return '../IMAGES/pinMapBleu.png';
                    case 'Restauration':    return '../IMAGES/pinMapRouge.png';
                    case 'Activite':        return '../IMAGES/pinMapViolet.png';
                    case 'Parc attraction': return '../IMAGES/pinMapNoir.png'; 
                    case 'Visite':          return '../IMAGES/pinMapVert.png';
                    default:                return '../IMAGES/pinMapVert.png'; 
                }
            }

            // Loop through categories and generate legend items
            for (var i = 0; i < categories.length; i++) {
                var categoryName = categories[i];
                var iconUrl = getIconUrl(categoryName);
                labels.push(
                    '<img src="' + iconUrl + '" class="legend-icon"> ' + categoryName
                );
            }

            div.innerHTML = labels.join('<br>');
            return div;
        };

        legend.addTo(map);
            

        //markers.forEach(element) => element.addTo(map);


    </script>
<script>
    let bouton_filtre = document.querySelector("#filterButton");
    let champs_filtres = document.querySelector("#filterOptions");
    bouton_filtre.addEventListener("click", hideAndShow);

    document.addEventListener('click', (e) => {
        if (!bouton_filtre.contains(e.target) && !champs_filtres.contains(e.target)) {
            champs_filtres.style.display = 'none';
        }
    });

    function hideAndShow() {
        const isVisible = window.getComputedStyle(champs_filtres).display === 'flex';
        champs_filtres.style.display = isVisible ? 'none' : 'flex';
    }

    var activite = document.querySelector("#activite");
    var visite = document.querySelector("#visite");
    var parc_attraction = document.querySelector("#parcAttraction");
    var spectacle = document.querySelector("#spectacle");
    var restauration = document.querySelector("#restauration");

    var articles = document.getElementsByClassName("art-offre");
    

    activite.addEventListener("click", fil_cat);
    visite.addEventListener("click", fil_cat);
    parc_attraction.addEventListener("click", fil_cat);
    spectacle.addEventListener("click", fil_cat);
    restauration.addEventListener("click", fil_cat);

    function fil_cat_verif() {
        let all_checked = (spectacle.checked) && (parc_attraction.checked) && (visite.checked) && (activite.checked) && (restauration.checked);
        let none_checked = (!spectacle.checked) && (!parc_attraction.checked) && (!visite.checked) && (!activite.checked) && (!restauration.checked);

        if (all_checked || none_checked) {
            retire_fil_cat();
            return false;
        } else { 
            return true;
        }
    }

    function fil_cat() {
        if (fil_cat_verif()) {
            //checkbox eventListener onchange
            //classList.toggle("hidden")
            //classList.contains("hidden")
            //let articles = document.getElementsByClassName("art-offre");
            for(marqueur of lstMarkers){
                let art_cat = marqueur['data-categorie'];
                let present = true;
                switch (art_cat) {
                    case 'Activite':
                        if (!activite.checked) {
                            marqueur["cat_hidden"]=true;
                        }else{
                            if(marqueur["cat_hidden"]){
                                marqueur["cat_hidden"]=false;
                            }
                        }
                        break;

                    case 'Visite':
                        if (!visite.checked) {
                            marqueur["cat_hidden"]=true;
                        }else{
                            if(marqueur["cat_hidden"]){
                                marqueur["cat_hidden"]=false;
                            }
                        }
                        break;

                    case 'Spectacle':
                        if (!spectacle.checked) {
                            marqueur["cat_hidden"]=true;
                        }else{
                            if(marqueur["cat_hidden"]){
                                marqueur["cat_hidden"]=false;
                            }
                        }
                        break;

                    case 'Parc attraction':
                        if (!parc_attraction.checked) {
                            marqueur["cat_hidden"]=true;
                        }else{
                            if(marqueur["cat_hidden"]){
                                marqueur["cat_hidden"]=false;
                            }
                        }
                        break;

                    case 'Restauration':
                        if (!restauration.checked) {
                            marqueur["cat_hidden"]=true;
                        }else{
                            if(marqueur["cat_hidden"]){
                                marqueur["cat_hidden"]=false;
                            }
                        }
                        break;

                    default:
                        console.log("Erreur de valeur pour le switch.\n");
                        break;
                }
            }

            for (article of articles) {
                let art_cat = article.getAttribute('data-categorie');

                switch (art_cat) {
                    case 'Activite':
                        if (!activite.checked) {
                            article.classList.add("cat_hidden");
                        } else {
                            if (article.classList.contains("cat_hidden")) {
                                article.classList.remove("cat_hidden");
                            }
                        }
                        break;

                    case 'Visite':
                        if (!visite.checked) {
                            article.classList.add("cat_hidden");
                        } else {
                            if (article.classList.contains("cat_hidden")) {
                                article.classList.remove("cat_hidden");
                            }
                        }
                        break;

                    case 'Spectacle':
                        if (!spectacle.checked) {
                            article.classList.add("cat_hidden");
                        } else {
                            if (article.classList.contains("cat_hidden")) {
                                article.classList.remove("cat_hidden");
                            }
                        }
                        break;

                    case 'Parc attraction':
                        if (!parc_attraction.checked) {
                            article.classList.add("cat_hidden");
                        } else {
                            if (article.classList.contains("cat_hidden")) {
                                article.classList.remove("cat_hidden");
                            }
                        }
                        break;

                    case 'Restauration':
                        if (!restauration.checked) {
                            article.classList.add("cat_hidden");
                        } else {
                            if (article.classList.contains("cat_hidden")) {
                                article.classList.remove("cat_hidden");
                            }
                        }
                        break;

                    default:
                        console.log("Erreur de valeur pour le switch.\n");
                        break;
                }
            }
        }
        change_filtre_marqueur();
        cachees_verif();
    }

    let retirerFiltreCat = document.querySelector("#retirerFiltreCat");
    retirerFiltreCat.addEventListener('click', retire_fil_cat);

    function retire_fil_cat() {
        if (activite.checked) {
            activite.checked = false;
        }
        if (visite.checked) {
            visite.checked = false;
        }
        if (parc_attraction.checked) {
            parc_attraction.checked = false;
        }
        if (spectacle.checked) {
            spectacle.checked = false;
        }
        if (restauration.checked) {
            restauration.checked = false;
        }

        for (article of articles) {
            if (article.classList.contains("cat_hidden")) {
                article.classList.remove("cat_hidden");
            }
        }

        for(marqueur of lstMarkers){
            if(marqueur["cat_hidden"]){
                marqueur["cat_hidden"]=false;
            }
        }
        
        change_filtre_marqueur();
        cachees_verif();
    }

    var sup_a_1 = document.querySelector("#sup_a_1");
    var sup_a_2 = document.querySelector("#sup_a_2");
    var sup_a_3 = document.querySelector("#sup_a_3");
    var sup_a_4 = document.querySelector("#sup_a_4");

    sup_a_1.addEventListener("click", fil_note);
    sup_a_2.addEventListener("click", fil_note);
    sup_a_3.addEventListener("click", fil_note);
    sup_a_4.addEventListener("click", fil_note);

    function fil_note_verif() {
        if (sup_a_1.checked || sup_a_2.checked || sup_a_3.checked || sup_a_4.checked) {
            return true;
        } else {
            return false;
        }
    }

    function fil_note() {
        if (fil_note_verif()) {
            //checkbox eventListener onchange
            //classList.toggle("hidden")
            //classList.contains("hidden")
            //let articles = document.getElementsByClassName("art-offre");

            let filtre_note;

            if (sup_a_1.checked) {
                filtre_note = 1;
            } else if (sup_a_2.checked) {
                filtre_note = 2;
            } else if (sup_a_3.checked) {
                filtre_note = 3;
            } else if (sup_a_4.checked) {
                filtre_note = 4;
            }

            for (marqueur of lstMarkers) {
                let art_note = marqueur['data-note'];

                if (filtre_note > art_note) {
                    marqueur["note_hidden"]=true;
                } else {
                    if(marqueur["note_hidden"]){
                        marqueur["note_hidden"]=false;
                    }
                }
            }

            for (article of articles) {
                let art_note = article.getAttribute('data-note');

                if (filtre_note > art_note) {
                    article.classList.add("note_hidden");
                } else {
                    if (article.classList.contains("note_hidden")) {
                        article.classList.remove("note_hidden");
                    }
                }
            }
        }

        change_filtre_marqueur();
        cachees_verif();
    }

    let retirerFiltreNote = document.querySelector("#retirerFiltreNote");
    retirerFiltreNote.addEventListener('click', retire_fil_note);

    function retire_fil_note() {
        if (sup_a_1.checked) {
            sup_a_1.checked = false;
        } else if (sup_a_2.checked) {
            sup_a_2.checked = false;
        } else if (sup_a_3.checked) {
            sup_a_3.checked = false;
        } else if (sup_a_4.checked) {
            sup_a_4.checked = false;
        }

        for (article of articles) {
            if (article.classList.contains("note_hidden")) {
                article.classList.remove("note_hidden");
            }
        }

        for(marqueur of lstMarkers){
            if(marqueur["note_hidden"]){
                marqueur["note_hidden"]=false;
            }
        }
        
        change_filtre_marqueur();
        cachees_verif();
    }

    var slid_min = document.getElementById('slider_min');
    var slid_max = document.getElementById('slider_max');
    var num_min = document.getElementById('number_min');
    var num_max = document.getElementById('number_max');

    slid_min.addEventListener('input', update_prix);
    slid_max.addEventListener('input', update_prix);
    num_min.addEventListener('input', update_prix);
    num_max.addEventListener('input', update_prix);

    function update_prix(event) {
        let new_val = event.target.value;

        if (new_val === '') {
            new_val = 0;
        }

        switch (event.target.id) {
            case 'slider_min':
                num_min.value = new_val;
                break;
            case 'slider_max':
                num_max.value = new_val;
                break;
            case 'number_min':
                slid_min.value = new_val;
                break;
            case 'number_max':
                slid_max.value = new_val;
                break;
        }
    }

    slid_min.addEventListener('mouseup', fil_prix);
    slid_max.addEventListener('mouseup', fil_prix);
    num_min.addEventListener('blur', fil_prix);
    num_max.addEventListener('blur', fil_prix);
    num_min.addEventListener('click', fil_prix);
    num_max.addEventListener('click', fil_prix);

    function fil_prix(event) {
        let new_val = event.target.value;

        if (new_val === '') {
            new_val = 0;
        }

        switch (event.target.id) {
            case 'slider_min':
                if (Number(new_val) > Number(slid_max.value)) {
                    slid_min.value = slid_min.min;
                    num_min.value = num_min.min;
                } else {
                    num_min.value = new_val;
                }
                break;
            case 'slider_max':
                if (Number(new_val) < Number(slid_min.value)) {
                    slid_max.value = slid_max.max;
                    num_max.value = num_max.max;
                } else {
                    num_max.value = new_val;
                }
                break;
            case 'number_min':
                if (Number(new_val) > Number(num_max.value)) {
                    slid_min.value = slid_min.min;
                    num_min.value = num_min.min;
                } else {
                    slid_min.value = new_val;
                }
                break;
            case 'number_max':
                if (Number(new_val) < Number(num_min.value)) {
                    slid_max.value = slid_max.max;
                    num_max.value = num_max.max;
                } else {
                    slid_max.value = new_val;
                }
                break;
        }

        for (marqueur of lstMarkers) {
            let art_prix = marqueur['data-prix'];

            if ((Number(art_prix) < Number(num_min.value)) || (Number(art_prix) > Number(num_max.value))) {
                marqueur["prix_hidden"]=true;
            } else {
                if(marqueur["prix_hidden"]){
                    marqueur["prix_hidden"]=false;
                }
            }
        }

        for (article of articles) {
            let art_prix = article.getAttribute('data-prix');

            if ((Number(art_prix) < Number(num_min.value)) || (Number(art_prix) > Number(num_max.value))) {
                article.classList.add("prix_hidden");
            } else {
                if (article.classList.contains("prix_hidden")) {
                    article.classList.remove("prix_hidden");
                }
            }
        }

        change_filtre_marqueur();
        cachees_verif();
    }

    let retirerFiltrePrix = document.querySelector("#retirerFiltrePrix");
    retirerFiltrePrix.addEventListener('click', retire_fil_prix);

    function retire_fil_prix() {
        slid_min.value = slid_min.min;
        slid_max.value = slid_max.max;
        num_min.value = num_min.min;
        num_max.value = num_max.max;

        for (article of articles) {
            if (article.classList.contains("prix_hidden")) {
                article.classList.remove("prix_hidden");
            }
        }

        for(marqueur of lstMarkers){
            if(marqueur["prix_hidden"]){
                marqueur["prix_hidden"]=false;
            }
        }
        
        change_filtre_marqueur();
        cachees_verif();
    }

    let retirerFiltres = document.querySelector("#retirerFiltres");
    retirerFiltres.addEventListener('click', retire_filtres);

    function retire_filtres() {
        if (fil_cat_verif()) {
            retire_fil_cat();
        }

        if (fil_note_verif()) {
            retire_fil_note();
        }

        retire_fil_prix();

        cachees_verif();
    }

    function cachees_verif() {
        let toutes_cachees = true;

        for (article of articles) {
            if (!(article.classList.contains("cat_hidden")) && !(article.classList.contains("note_hidden")) && !(article.classList.contains("prix_hidden"))) {
                toutes_cachees = false;
            }
        }

        let retirer = document.getElementById('ListeVide');

        if (toutes_cachees) {
            if (!retirer) {
                let message_remplace = document.createElement('h1');
                message_remplace.id = 'ListeVide';
                message_remplace.textContent = 'On ne peut rien vous proposer avec votre sélection, désolé...';
                navigateur = document.querySelector('nav');
                navigateur.appendChild(message_remplace);
            }
        } else {
            if (retirer) {
                retirer.remove();
            }
        }
    }

    function change_filtre_marqueur(){
        markers.clearLayers();
        var marqueurs_filtre=[]
        for(marqueur of lstMarkers){
            if(!marqueur["cat_hidden"] && !marqueur["note_hidden"] && !marqueur["prix_hidden"]){
                marqueurs_filtre.push(marqueur);
            }
        }
        markers.addLayers(marqueurs_filtre);
    }
</script>

</html>