<?php
require_once "../includes/consts.inc.php";

session_start();
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

    <script src="../includes/main.js"></script>
	<script src="../scripts/recherche.js"></script>
    <script>
        function loadInfoOffre(idoffre) {
            window.location.href = `informations.php?idoffre=${idoffre}`;
        }

        alert(articles[0].firstElementChild.children[1].innerHTML);

        let clopButtonsRedirige = document.getElementsByClassName("clopButton");
        clopButtonsRedirige[0].addEventListener("click", loadCreaOffre);
        clopButtonsRedirige[1].addEventListener("click", loadSesOffresPro);
    </script>

    <title>PACT</title>
</head>

<body>
    <?php require_once HEADER; ?>
    <!-- Main content -->
    <main style="margin-bottom: 700px;">
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

            <div>
                <button id="filterButton" class="smallButton">Filtrer</button>
                <fieldset id="filterOptions">
                    <h3>Par Catégorie :</h3>

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
                        default:
                            $ordreTri = '';
                    }
                    $query1 = '
                    SELECT * FROM ' . NOM_SCHEMA . '._offre 
                    NATURAL JOIN ' . NOM_SCHEMA . '._compteProfessionnel' . $ordreTri;

                    ?>
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
                    <button class="smallButton" id="retirerFiltres">Enlever les fitres</button>
		        </fieldset>
                <div class="tri">

                    <select id="SelectionTri" onchange="triOffre()" >
                        <option value="" disabled selected>Tris</option>
                        <option value="noteCroissante">Note Croissante</option>
                        <option value="noteDecroissante">Note Décroissante</option>
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

        <section>
            <?php
            if (est_pro(get_account_id())) {
                $filtre_cat = " WHERE idcompte = '" . get_account_id() . "'";
                $query1 = $query1 . $filtre_cat;
                $query1 = $query1 . 'ORDER BY ' . NOM_SCHEMA . "._offre.idoffre, CASE WHEN sae.option.option = 'A la une' THEN 1 ELSE 2 END, sae._offre.idoffre ASC";
            }

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
                    <article class="relief art-offre" onclick="loadInfoOffre('<?php echo $offre['idoffre']; ?>')" data-categorie="<?php echo $offre['categorie'] ?>">
                    <?php
		        } else {
                    ?>
                    <article class="art-offre" onclick="loadInfoOffre('<?php echo $offre['idoffre']; ?>')" data-categorie="<?php echo $offre['categorie'] ?>">
                    <?php
                } 
                ?>
                    <div>
                        <h3 class="clopTitre"><?php echo $offre['nomoffre']; ?></h3>
                        <section class="art-header">
                            <h3><?php echo $offre['categorie']; ?></h3>
                            <div>
                                <!-- <p>5/5<?php echo $requeteCompteAvis['nbavis'] ?></p> -->
                            </div>
                            <p><?php echo $offre['prixmin']; ?> &#8364;</p>
                        </section>
                    </div>
                    <div>
                        <?php
                        $query_image = 'SELECT urlimage FROM ' . NOM_SCHEMA . '.' . NOM_TABLE_OFFRE . ' NATURAL JOIN ' . NOM_SCHEMA . '.' . NOM_TABLE_IMGOF . ' WHERE idoffre=\'' . $offre['idoffre'] . '\'';
                        $images = $dbh->query($query_image)->fetch();?>
                        <img src="<?php echo $images[0]; ?>" alt="Nom_image" class="clopArtImg">

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

    <?php require_once FOOTER; ?>

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

    function hideAndShow() {
        const isVisible = window.getComputedStyle(champs_filtres).display === 'flex';
        champs_filtres.style.display = isVisible ? 'none' : 'flex';
    }

    let retirerFiltres = document.querySelector("#retirerFiltres");
    retirerFiltres.addEventListener('click', () => {
        window.location.href = `liste.php`;
    });

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
            for (article of articles) {
                article.classList.remove("cat_hidden");
            }
            if (all_checked) {
                activite.checked = false;
                visite.checked = false;
                parc_attraction.checked = false;
                spectacle.checked = false;
                restauration.checked = false;
            }
            return false;
        } else {
            return true;
        }
    }

    function fil_cat() {

        if (fil_cat_verif()){
            //checkbox eventListener onchange
            //classList.toggle("hidden")
            //classList.contains("hidden")
            //let articles = document.getElementsByClassName("art-offre");
            
            for (article of articles) {
                let art_cat = article.getAttribute('data-categorie');

                switch (art_cat) {
                    case 'Activite':
                        if (!activite.checked) {
                            article.classList.add("cat_hidden");
                        } else {
                            article.classList.remove("cat_hidden");
                        }
                        break;

                    case 'Visite':
                        if (!visite.checked) {
                            article.classList.add("cat_hidden");
                        } else {
                            article.classList.remove("cat_hidden");
                        }
                        break;
                    
                    case 'Spectacle':
                        if (!spectacle.checked) {
                            article.classList.add("cat_hidden");
                        } else {
                            article.classList.remove("cat_hidden");
                        }
                        break;

                    case 'Parc attraction':
                        if (!parc_attraction.checked) {
                            article.classList.add("cat_hidden");
                        } else {
                            article.classList.remove("cat_hidden");
                        }
                        break;

                    case 'Restauration':
                        if (!restauration.checked) {
                            article.classList.add("cat_hidden");
                        } else {
                            article.classList.remove("cat_hidden");
                        }
                        break;

                    default:
                        console.log("Erreur de valeur pour le switch.\n");
                        break;
                }
            }
        }
    }
</script>

</html>
