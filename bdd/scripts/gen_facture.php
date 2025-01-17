<?php
require_once("../html/db_connection.inc.php");
global $dbh;


function generate_id()
{
    global $dbh;
    $id_base = "Fa-";
    $count_query = "SELECT COUNT(*) FROM " . NOM_SCHEMA . "." . VUE_FACTURE;
    try {
        $count = $dbh->query($count_query)->fetchColumn();
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
    }
}
                
$query = "select distinct idoffre from " . NOM_SCHEMA . "." . NOM_TABLE_OFFRE;
$rows = $dbh->query($query)->fetchAll();

try{
    foreach($rows as $row){


        $query = "INSERT INTO" . NOM_SCHEMA . "." . VUE_FACTURE . "(idfacture,idoffre) VALUES(:idfacture, :idoffre)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam("idfacture", generate_id());
        $stmt->bindParam("idoffre", $row['idoffre']);
    }
}
catch(PDOException $e){
    die("sql query failed : :" . $e->getMessage());
}


?>