<?php
session_start();
require_once "db_connection.inc.php";

$idoffre = $_POST['idoffre'];
$option = $_POST['option'];

try {
    $queryUpdate = 'UPDATE ' . NOM_SCHEMA . '._souscriptionoption 
                    SET active = false 
                    WHERE idoffre = :idoffre AND "option" = :option';
    $sthUpdate = $dbh->prepare($queryUpdate);
    $sthUpdate->execute([
        'idoffre' => $idoffre,
        'option' => $option
    ]);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
} catch (PDOException $e) {
    die('SQL Error: ' . $e->getMessage());
}