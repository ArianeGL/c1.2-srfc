<?php
require_once "../db_connection.inc.php";

function suppr_avis($id)
{
    global $dbh;
    $query = "DELETE FROM " . NOM_SCHEMA . "." . VUE_AVIS . " WHERE idavis = :id";

    try {
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        error_log("Avis supprimé");
    } catch (PDOException $e) {
        error_log("Couldn't delete review : " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $idavis = $_GET['idavis'];
    suppr_avis($idavis);
}
