<link rel="stylesheet" href="./styles/crea_avis.css">
<?php

require_once "../db_connection.inc.php";

class FunctionException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

function generate_id()
{
    global $dbh;
    $id_base = "Av-";
    $count_query = "SELECT COUNT(*) FROM " . NOM_SCHEMA . "." . NOM_TABLE_AVIS . ";";
    try {
        $count = $dbh->query($count_query)->fetchColumn(); // recupere le nombre d'offre deja existante
    } catch (PDOException $e) {
        die("SQL Query in generate_id() failed : " . $e->getMessage());
    }
    $count++;

    switch (strlen((string)$count)) {
        case 1:
            return $id_base . "000" . strval($count);
            break;
        case 2:
            return $id_base . "00" . strval($count);
            break;
        case 3:
            return $id_base . "0" . strval($count);
            break;
        case 4:
            return $id_base . strval($count);
            break;
        default:
            throw new FunctionException("Couldn't generate id");
    }
}

function get_account_id()
{
    global $dbh;

    try {
        $query = "SELECT idcompte FROM " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . " WHERE email = '" . $_SESSION['identifiant'] . "';";
        $id = $dbh->query($query)->fetch();
    } catch (PDOException $e) {
        throw $e;
    }

    return $id['idcompte'];
}

function can_post($idOffre)
{
    global $dbh;

    $est_authorise = false;
    if (isset($_SESSION['identifiant'])) {
        $identifiant = $_SESSION["identifiant"];
        $mdp = $_SESSION["mdp"];
        //$mdp_crypte = md5($mdp);

        $queryCompte = 'SELECT COUNT(*) FROM ' . NOM_SCHEMA . '.' . VUE_MEMBRE . ' WHERE email = :email AND motdepasse = :mdp';
        $sthCompte = $dbh->prepare($queryCompte);
        $sthCompte->bindParam(':email', $identifiant, PDO::PARAM_STR);
        $sthCompte->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $sthCompte->execute();
        $countMembre = $sthCompte->fetchColumn();

        $idCompte = get_account_id();

        $queryAvis = 'SELECT COUNT(*) FROM ' . NOM_SCHEMA . '.' . VUE_AVIS . ' WHERE idcompte = :idcompte AND idoffre = :idoffre';
        $sthAvis = $dbh->prepare($queryAvis);
        $sthAvis->bindParam(':idcompte', $idCompte, PDO::PARAM_STR);
        $sthAvis->bindParam(':idoffre', $idOffre, PDO::PARAM_STR);
        $sthAvis->execute();
        $countAvis = $sthAvis->fetchColumn();

        if ($countMembre != 0 && $countAvis == 0) {
            $est_authorise = true;
        }
    }
    return $est_authorise;
}

function afficher_form_avis($idOffre)
{
    global $dbh;
    if (isset($_POST['valider'])) {
        $idAvis = generate_id();
        $titre = $_POST['titre'];
        if ($_POST['date'] !== "") {
            $date = $_POST['date'];
        } else {
            $date = 'null';
        }
        $contexte = $_POST['contexte'];
        $commentaire = $_POST['commentaire'];
        $note = $_POST['note'];
        $idCompte = get_account_id();

        /*
        $nbLike = 0;
        $nbDislike = 0;
        $blacklist = false;
        $signale = false;
        */

        $queryInsert = 'INSERT INTO ' . NOM_SCHEMA . '.' . VUE_AVIS . '(idavis,titre,datevisite,contexte,idoffre,idcompte,commentaire,noteavis) VALUES (:idavis, :titre, :datevisite, :contexte, :idoffre, :idcompte, :commentaire, :noteavis);';
        $sth = $dbh->prepare($queryInsert);
        $sth->bindParam(':idavis', $idAvis);
        $sth->bindParam(':titre', $titre);
        $sth->bindParam(':datevisite', $date);
        $sth->bindParam(':contexte', $contexte);
        $sth->bindParam(':idoffre', $idOffre);
        $sth->bindParam(':idcompte', $idCompte);
        $sth->bindParam(':commentaire', $commentaire);
        $sth->bindParam(':noteavis', $note);

        $sth->execute();
        $sth = null;
    } else {
?>
        <div id="crea_avis">
        <button class="button" id="deroulerAvis">Ajouter un avis</button>
        <form method="post" enctype="multipart/form-data" id="formAvis">

            <label for="titre">
                <h1>Titre*
            </label>
            <input type="text" name="titre" id="titre" placeholder="Renseigner un titre" required />
            <br>
            <label for="date">Date</label>
            <input type="date" name="date" id="date" />
            <br>
            <select class="smallButton" name="contexte" id="contexte" required>
                <option value="" disabled selected hidden>Contexte *</option>
                <option value="En amoureux">En amoureux</option>
                <option value="En famille">En famille</option>
                <option value="Entre amis">Entre amis</option>
                <option value="Seul">Seul</option>
            </select>
            <br>
            <label for="commentaire">Commentaire *</label>
            <input type="textarea" name="commentaire" id="commentaire" placeholder="Renseigner un commentaire" required />
            <br>
            <label for="note">Note *</label>
            <input type="text" name="note" id="note" placeholder="Renseigner une note" required />
            <br>
            <script src="image_preview.js"></script>
            <div class="boutonimages">
                <p>Importer vos images</p>
                <label for="images_offre" class="smallButton">Importer</label>
                <input type="file" id="images_offre" name="images_offre[]" multiple="multiple" accept="image/*" onchange="preview(image_preview)">
            </div>
            <img id="image_preview" src="" alt="">
            <br>
            <input class="bigButton" type="submit" name="valider" value="Valider" id="valider"></h1>
        </form>
        </div>

        <script>
            let bouton_creer_avis = document.querySelector("#deroulerAvis");
            let form_creer_avis = document.querySelector("#formAvis");
            bouton_creer_avis.addEventListener("click", hideAndShow);

            function hideAndShow() {
                const isVisible = window.getComputedStyle(form_creer_avis).display === 'block';
                form_creer_avis.style.display = isVisible ? 'none' : 'block';
            }
        </script>
<?php
    }
}
?>
