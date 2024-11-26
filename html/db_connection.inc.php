<?php

const NOM_SCHEMA = "sae";
const NOM_TABLE_OFFRE = "_offre";
const NOM_TABLE_COMPTE = "_compte";
const VUE_ACTIVITE = "activite";
const VUE_RESTO = "restauration";
const VUE_VISITE = "visite";
const VUE_PARC_ATTRACTIONS = "parcattraction";
const VUE_SPECTACLE = "spectacle";
const VUE_PRO_PRIVE = "compteProfessionnelPrive";
const VUE_PRO_PUBLIQUE = "compteprofessionnelpublique";
const VUE_MEMBRE = "comptemembre";

$host = "srfc.ventsdouest.dev";
$dbname = "sae";
$user = "sae";
$pass = "escapade-Venait-s1gner";
try {
    $dbh = new PDO('pgsql:host=' . $host . ';dbname=' . $dbname, $user, $pass);
} catch (PDOException $e) {
    echo "db connection failed : " . $e->getMessage();
}
