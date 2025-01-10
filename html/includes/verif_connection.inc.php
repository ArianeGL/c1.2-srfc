<?php
require_once "../db_connection.inc.php";


function valid_account()
{
    global $dbh;
    try {
        $query = "SELECT * FROM " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . ";";
        $res = $dbh->query($query)->fetchAll();

        $isValid = false;
        $i = 0;
        while ($i < sizeof($res) && !$isValid) {
            if ($_SESSION['identifiant'] == $res[$i]['email']) $isValid = true;
            $i++;
        }
    } catch (PDOException $e) {
        echo "Couldn't fetch accounts : " . $e->getMessage();
    }

    return $isValid;
}
