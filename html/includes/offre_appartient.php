<?php 
function offre_appartient($compte, $idoffre): bool
{
    global $dbh;
    $appartient = false;

    try {
        $query_compte = "SELECT email FROM " . NOM_SCHEMA . "." . NOM_TABLE_OFFRE . "
                INNER JOIN " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . " ON " . NOM_TABLE_COMPTE . ".idcompte = " . NOM_TABLE_OFFRE . ".idcompte
                WHERE " . NOM_TABLE_OFFRE . ".idoffre = :idoffre;";
        $stmt_compte = $dbh->prepare($query_compte);
        $stmt_compte->bindParam(':idoffre', $idoffre);
        $stmt_compte->execute();
        $res = $stmt_compte->fetch(PDO::FETCH_ASSOC)["email"];
        if ($res == $compte) $appartient = true;
    } catch (PDOException $e) {
        die("SQL Query error : " . $e->getMessage());
    }

    return $appartient;
}
?>