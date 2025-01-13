<?php
require_once("../html/db_connection.inc.php");
global $dbh;

// verification quotidienne des offres et leurs historique de mise en ligne
// avec changement si necessaire grace a la fonction 'daily_check_historique_offre'
$query = "select distinct idoffre from " . NOM_SCHEMA . "." . "_historique";
$rows = $dbh->query($query)->fetchAll();

try{
    foreach($rows as $row){
        $query = "select daily_check_historique_offre(:idoffre)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam("idoffre", $row['idoffre']);
    }
}
catch(PDOException $e){
    die("sql query failed : :" . $e->getMessage());
}

// verification quotidienne des options des offres et leurs historique
// avec changement si necessaire grace a la fonction 'daily_check_historique_option'
$query = "select idoffre, option from " . NOM_SCHEMA . "." . "_souscriptionoption";
$rows = $dbh->query($query)->fetchAll();

try{
    foreach($rows as $row){
        $query = "select daily_check_historique_option(:idoffre, :option)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam("idoffre", $row['idoffre']);
        $stmt->bindParam("option", $row['option']);
    }
}
catch(PDOException $e){
    die("sql query failed : :" . $e->getMessage());
}