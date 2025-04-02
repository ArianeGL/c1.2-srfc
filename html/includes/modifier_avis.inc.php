<link rel="stylesheet" href="../styles/modifier_avis.css">
<?php
require_once "../db_connection.inc.php";
require_once "../includes/consts.inc.php";

/*
 * prend en argument l'id d'un avis et une string
 * remplace dans la bdd le commentaire de l'avis correspondant a l'id par la string en parametre
 */
function modifier_message_avis($id_avis, $message)
{
    global $dbh;

    try {
        $query = "UPDATE " . NOM_SCHEMA . "." . NOM_TABLE_AVIS . " SET commentaire = :message WHERE idavis = :id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":message", $message);
        $stmt->bindParam(":id", $id_avis);
        $stmt->execute();
        $stmt = null;
    } catch (PDOException $e) {
        die("Couldn't update review comment : " . $e->getMessage());
    }
}

/*
 * prend en argument l'id d'un avis et une string
 * remplace dans la bdd le titre de l'avis correspondant a l'id par la string en parametre
 */
function modifier_titre_avis($id_avis, $titre)
{
    global $dbh;

    try {
        $query = "UPDATE " . NOM_SCHEMA . "." . NOM_TABLE_AVIS . " SET titre = :titre WHERE idavis = :id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":titre", $titre);
        $stmt->bindParam(":id", $id_avis);
        $stmt->execute();
        $stmt = null;
    } catch (PDOException $e) {
        die("Couldn't update review title : " . $e->getMessage());
    }
}

/*
 * prend en argument l'id d'un avis et un entier
 * remplace dans la bdd la note de l'avis correspondant a l'id par l'entier en parametre
 */
function modifier_note_avis($id_avis, $note)
{
    global $dbh;

    try {
        $query = "UPDATE " . NOM_SCHEMA . "." . NOM_TABLE_AVIS . " SET noteavis = :note WHERE idavis = :id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":note", $note);
        $stmt->bindParam(":id", $id_avis);
        $stmt->execute();
        $stmt = null;
    } catch (PDOException $e) {
        die("Couldn't update review note : " . $e->getMessage());
    }
}

/*
 * prend en argument l'id d'un avis et une string
 * remplace dans la bdd le contexte de l'avis correspondant a l'id par la string en parametre
 */
function modifier_contexte_avis($id_avis, $contexte)
{
    global $dbh;

    try {
        $query = "UPDATE " . NOM_SCHEMA . "." . NOM_TABLE_AVIS . " SET contexte = :contexte WHERE idavis = :id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":contexte", $contexte);
        $stmt->bindParam(":id", $id_avis);
        $stmt->execute();
        $stmt = null;
    } catch (PDOException $e) {
        die("Couldn't update review context : " . $e->getMessage());
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['note']) && $_POST['request'] = "edit") {
    $id_avis = $_POST['idavis'];
    $id_offre = $_POST['idoffre'];
    $note = $_POST['note'];
    $titre = $_POST['titre'];
    $contexte = $_POST['contexte'];
    $commentaire = $_POST['commentaire'];

    echo $id_avis;

    modifier_note_avis($id_avis, $note);
    modifier_titre_avis($id_avis, $titre);
    modifier_contexte_avis($id_avis, $contexte);
    modifier_message_avis($id_avis, $commentaire);

    echo "bwaah" . $id_offre;
    echo "<script>window.location.href=\"../offres/informations.php?idoffre=" . $id_offre . "\"</script>";
}
