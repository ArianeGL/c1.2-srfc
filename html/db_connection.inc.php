<?php

ini_set('display_errors', 0);

const NOM_SCHEMA = "sae";
const NOM_TABLE_OFFRE = "_offre";
const NOM_TABLE_COMPTE = "_compte";
const NOM_TABLE_COMPTE_PRO = "_compteprofessionnel";
const NOM_TABLE_SOUSCRI_OPTION = "_souscriptionoption";
const NOM_TABLE_AVIS = "_avis";
const NOM_TABLE_TAGS = "_tag";
const NOM_TABLE_TAG_POUR_OFFRE = "_tagpouroffre";
const NOM_TABLE_TAGSRE = "_tagrestauration";
const NOM_TABLE_IMGOF = "_imageoffre";
const VUE_ACTIVITE = "activite";
const VUE_RESTO = "restauration";
const VUE_VISITE = "visite";
const VUE_PARC_ATTRACTIONS = "parcattraction";
const VUE_SPECTACLE = "spectacle";
const VUE_MEMBRE = "comptemembre";
const VUE_PRO_PRIVE = "compteProfessionnelPrive";
const VUE_PRO_PUBLIQUE = "compteprofessionnelpublique";
const VUE_TAGS_OF = "tagof";
const VUE_TAGS_RE = "tagre";
const VUE_OPTION = "option";
const VUE_FACTURE = "facture";
const VUE_AVIS = "avis";
const VUE_AVIS_RESTAURATION = "avisre";
const VUE_REPONSE = "reponse";
const VUE_AIME_AVIS = "aime";
const NOM_TABLE_COMPTE_MEMBRE = "_comptemembre";

$host = "srfc.ventsdouest.dev";
$dbname = "sae";
$user = "sae";
$pass = "escapade-Venait-s1gner";
try {
	$dbh = new PDO('pgsql:host=' . $host . ';dbname=' . $dbname, $user, $pass);
} catch (PDOException $e) {
	die("db connection failed : " . $e->getMessage());
}
