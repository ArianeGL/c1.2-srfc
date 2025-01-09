<?php
require_once "../db_connection.inc.php";
require_once "./consts.inc.php";

/*
 * prend en argument l'id d'un avis et une string
 * remplace dans la bdd le commentaire de l'avis correspondant a l'id par la string en parametre
 */
function modifier_message_avis($id_avis, $message)
{
    global $dbh;

    try {
        $query = "UPDATE " . NOM_SCHEMA . "." . VUE_AVIS . " SET commentaire = :message WHERE idavis = :id";
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
        $query = "UPDATE " . NOM_SCHEMA . "." . VUE_AVIS . " SET titre = :message WHERE idavis = :id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":message", $message);
        $stmt->bindParam(":id", $id_avis);
        $stmt->execute();
        $stmt = null;
    } catch (PDOException $e) {
        die("Couldn't update review comment : " . $e->getMessage());
    }
}
