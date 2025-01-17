<?php
session_start();
require_once "../db_connection.inc.php";

function get_account_id() {
    global $dbh;
    $query = "SELECT idcompte FROM " . NOM_SCHEMA . "." . NOM_TABLE_COMPTE . " WHERE email = '" . $_SESSION['identifiant'] . "';";
    $id = $dbh->query($query)->fetch();
    return $id['idcompte'];
}

function est_membre($email) {
    global $dbh;
    $query = "SELECT * FROM " . NOM_SCHEMA . "." . VUE_MEMBRE . " WHERE email = '" . $email . "';";
    $row = $dbh->query($query)->fetch();
    return isset($row['pseudo']);
}

global $dbh;
$email = $_SESSION["identifiant"];
$idCompte = get_account_id($email);
$idAvis = $_GET['avisId'];
$action = $_GET['action']; // 'add' ou 'remove'

if (est_membre($email)) {
    if ($action === 'add') {
        // Vérifie si l'utilisateur a déjà dislike cet avis
        $queryCheckDislike = "SELECT aime FROM " . NOM_SCHEMA . "." . VUE_AIME_AVIS . " WHERE idcompte = :idcompte AND idavis = :idavis";
        $stmtCheckDislike = $dbh->prepare($queryCheckDislike);
        $stmtCheckDislike->execute([':idcompte' => $idCompte, ':idavis' => $idAvis]);
        $existingDislike = $stmtCheckDislike->fetch(PDO::FETCH_ASSOC);

        if ($existingDislike && $existingDislike['aime'] === false) {
            // Supprime le dislike existant
            $queryDeleteDislike = "DELETE FROM " . NOM_SCHEMA . "." . VUE_AIME_AVIS . " WHERE idcompte = :idcompte AND idavis = :idavis";
            $dbh->prepare($queryDeleteDislike)->execute([':idcompte' => $idCompte, ':idavis' => $idAvis]);

            // Décrémente nbdislike dans la table avis
            $queryUpdateAvisDislike = "UPDATE " . NOM_SCHEMA . "." . NOM_TABLE_AVIS . " SET nbdislike = nbdislike - 1 WHERE idavis = :idavis";
            $dbh->prepare($queryUpdateAvisDislike)->execute([':idavis' => $idAvis]);
        }

        // Vérifie si l'utilisateur a déjà like cet avis
        $queryCheckLike = "SELECT aime FROM " . NOM_SCHEMA . "." . VUE_AIME_AVIS . " WHERE idcompte = :idcompte AND idavis = :idavis";
        $stmtCheckLike = $dbh->prepare($queryCheckLike);
        $stmtCheckLike->execute([':idcompte' => $idCompte, ':idavis' => $idAvis]);
        $existingLike = $stmtCheckLike->fetch(PDO::FETCH_ASSOC);

        if ($existingLike) {
            // Met à jour le like existant
            $queryUpdate = "UPDATE " . NOM_SCHEMA . "." . VUE_AIME_AVIS . " SET aime = true WHERE idcompte = :idcompte AND idavis = :idavis";
            $dbh->prepare($queryUpdate)->execute([':idcompte' => $idCompte, ':idavis' => $idAvis]);
        } else {
            // Insère un nouveau like
            $queryInsert = "INSERT INTO " . NOM_SCHEMA . "." . VUE_AIME_AVIS . " (idcompte, idavis, aime) VALUES (:idcompte, :idavis, true)";
            $dbh->prepare($queryInsert)->execute([':idcompte' => $idCompte, ':idavis' => $idAvis]);
        }


        // Renvoie l'état actuel du like et du dislike
        echo json_encode(['success' => true, 'action' => 'add', 'liked' => true, 'disliked' => false]);
    } elseif ($action === 'remove') {
        // Retire un like
        $queryDelete = "DELETE FROM " . NOM_SCHEMA . "." . VUE_AIME_AVIS . " WHERE idcompte = :idcompte AND idavis = :idavis";
        $dbh->prepare($queryDelete)->execute([':idcompte' => $idCompte, ':idavis' => $idAvis]);

        // Décrémente nblike dans la table avis
        $queryUpdateAvis = "UPDATE " . NOM_SCHEMA . "." . NOM_TABLE_AVIS . " SET nblike = nblike - 1 WHERE idavis = :idavis";
        $dbh->prepare($queryUpdateAvis)->execute([':idavis' => $idAvis]);

        // Renvoie l'état actuel du like
        echo json_encode(['success' => true, 'action' => 'remove', 'liked' => false]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Utilisateur non membre']);
}
?>