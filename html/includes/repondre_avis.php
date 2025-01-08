<link rel="stylesheet" href="./styles/crea_rep.css">
<?php

require_once "db_connection.inc.php";
require_once "offre_apartient.php";

class FunctionException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

// Fonction pour générer un ID unique pour la réponse
function generate_reponse_id()
{
    global $dbh;
    $id_base = "Re-"; 
    $count_query = "SELECT COUNT(*) FROM " . NOM_SCHEMA . "._reponse;"; 
    try {
        $count = $dbh->query($count_query)->fetchColumn(); 
    } catch (PDOException $e) {
        die("SQL Query in generate_reponse_id() failed : " . $e->getMessage());
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


function can_repondre($idAvis)
{
    global $dbh;

    $est_authorise = false;
    if (isset($_SESSION['identifiant'])) {
        $queryCompte = 'SELECT COUNT(*) FROM ' . NOM_SCHEMA . '.' . VUE_PROFESSIONNEL . ' WHERE email = :email';
        $sthCompte = $dbh->prepare($queryCompte);
        $sthCompte->bindParam(':email', $_SESSION['identifiant'], PDO::PARAM_STR);
        $sthCompte->execute();
        $countProfessionnel = $sthCompte->fetchColumn();

        // Vérifie si l'utilisateur est le propriétaire de l'offre associée à l'avis
        $queryAvis = 'SELECT idoffre FROM ' . NOM_SCHEMA . '.' . VUE_AVIS . ' WHERE idavis = :idavis';
        $sthAvis = $dbh->prepare($queryAvis);
        $sthAvis->bindParam(':idavis', $idAvis, PDO::PARAM_STR);
        $sthAvis->execute();
        $avis = $sthAvis->fetch(PDO::FETCH_ASSOC);

        if ($countProfessionnel != 0 && offre_appartient($_SESSION['identifiant'], $avis['idoffre'])) {
            $est_authorise = true;
        }
    }
    return $est_authorise;
}


function afficher_form_reponse($idAvis)
{
    global $dbh;
    if (reponse_existe($idAvis)) {
        echo "<p>Une réponse a déjà été postée pour cet avis.</p>";
        return;
    }

    if (isset($_POST['valider'])) {
        $idRep = generate_reponse_id();
        $reponse = trim($_POST['reponse']);
        $idCompte = get_account_id();

        // Insère la réponse dans la table _reponse
        $queryInsert = 'INSERT INTO ' . NOM_SCHEMA . '._reponse (idrep, idcompte, idavis, reponse) VALUES (:idrep, :idcompte, :idavis, :reponse);';
        $sth = $dbh->prepare($queryInsert);
        $sth->bindParam(':idrep', $idRep);
        $sth->bindParam(':idcompte', $idCompte);
        $sth->bindParam(':idavis', $idAvis);
        $sth->bindParam(':reponse', $reponse);

        $sth->execute();
        $sth = null;

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
?>
        <button class="button" id="deroulerReponse">Répondre à cet avis</button>
        <form method="post" enctype="multipart/form-data" id="formReponse">
            <label for="reponse">
                <h1>Réponse</h1>
            </label>
            <textarea name="reponse" id="reponse" placeholder=" Votre réponse" required></textarea>
            <br>
            <input class="bigButton" type="submit" name="valider" value="Valider" id="valider">
        </form>

        <script>
            let bouton_creer_reponse = document.querySelector("#deroulerReponse");
            let form_creer_reponse = document.querySelector("#formReponse");
            bouton_creer_reponse.addEventListener("click", hideAndShow);

            function hideAndShow() {
                const isVisible = window.getComputedStyle(form_creer_reponse).display === 'block';
                form_creer_reponse.style.display = isVisible ? 'none' : 'block';
            }
        </script>
<?php
    }
}
?>