<?php
session_start();
require_once "../db_connection.inc.php";
require_once "../includes/consts.inc.php";
global $dbh;

require_once "../includes/verif_connection.inc.php";

function est_membre($email)
{
    $ret = false;
    global $dbh;

    $query = "SELECT * FROM " . NOM_SCHEMA . "." . VUE_MEMBRE . " WHERE email = '" . $email . "';";
    $row = $dbh->query($query)->fetch();

    if (isset($row['pseudo'])) $ret = true;

    return $ret;
}

function est_prive($email)
{
    $ret = false;
    global $dbh;

    $query = "SELECT * FROM " . NOM_SCHEMA . "." . VUE_PRO_PRIVE . " WHERE email = '" . $email . "';";
    $row = $dbh->query($query)->fetch();

    if (isset($row['iban'])) $ret = true;

    return $ret;
}

if (isset($_SESSION['identifiant']) && valid_account()) {
    $email = $_SESSION['identifiant'];
    $requeteCompte = $dbh->prepare("SELECT idcompte, email FROM sae._compte WHERE email = '" . $email . "';"); //, PDO::FETCH_ASSOC
    $requeteCompte->execute();
    //$idCompte = $requeteCompte['idcompte'];
    $idCompte = $requeteCompte->fetch(PDO::FETCH_ASSOC)["idcompte"];
} else {
?> <script>
        window.location = "./connection.php";
    </script>
<?php }

if (!est_membre($email)) { ?>
    <script>
        let params = new URLSearchParams(document.location.search);
        let toast = params.get("toast");
        if(toast!=null){
            window.location = `./consultation_pro.php?toast=${toast}`;
        }
    </script>
<?php }
$schemaCompte = VUE_MEMBRE;

$queryCompte = 'SELECT * FROM ' . NOM_SCHEMA . '.' . $schemaCompte . ' WHERE idcompte = :idcompte';
$sthCompte = $dbh->prepare($queryCompte);
$sthCompte->bindParam(':idcompte', $idCompte, PDO::PARAM_STR);
$sthCompte->execute();
//$count = $sthCompte->fetchColumn();
$count = 5;
$compte = $sthCompte->fetch(PDO::FETCH_ASSOC);

if ($compte) {
    //$row = $rows[0];
    $email = $compte["email"];
    $adresse = $compte['numadressecompte'] . " " . $compte['ruecompte'];
    $ville = $compte['villecompte'];
    $codePostal = $compte['codepostalcompte'];
    $telephone = $compte['telephone'];
    $nom = $compte['nommembre'];
    $prenom = $compte['prenommembre'];
    $pseudo = $compte['pseudo'];
    $image = $compte['urlimage'];
} else { ?>
    <script>
        window.location = <?php echo LISTE_OFFRES ?>;
    </script>
<?php } ?>

<!DOCTYPE html>
<html lang="fr" id="consultation">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../includes/style.css">
    <script src="../includes/main.js"></script>

    <title>Mon Compte - PACT</title>
</head>

<body>
    <?php require_once HEADER ?>
    <ul class="notifications" id="notif"></ul>
    <script>
        const notifications = document.querySelector("#notif");
        let params = new URLSearchParams(document.location.search);
        let toast = params.get("toast");
        if(toast!=null){
            createToast(toast);
        }
    </script>

    <main>
        <section class="profile">
            <div class="profile-header">
                <h1>Bonjour, <?php echo htmlspecialchars($pseudo); ?></h1>
            </div>
            <div class="profile-row">
                <form>
                    <div id="code-postal-ville">
                        <div class="input-group">
                            <input type="text" id="nom" value="<?php echo htmlspecialchars($nom) ?>" disabled>
                        </div>

                        <div class="input-group">
                            <input type="text" id="prenom" value="<?php echo htmlspecialchars($prenom) ?>" disabled>
                        </div>
                    </div>

                    <div class="input-group">
                        <input type="text" id="courriel" value="<?php echo htmlspecialchars($email) ?>" disabled>
                    </div>

                    <div class="input-group">
                        <input type="text" id="telephone" value="<?php echo htmlspecialchars($telephone) ?>" disabled>
                    </div>

                    <div class="input-group">
                        <input type="text" id="adresse" value="<?php echo htmlspecialchars($adresse) ?>" disabled>
                    </div>

                    <div id="code-postal-ville">
                        <div class="input-group">
                            <input type="text" id="code-postal" value="<?php echo htmlspecialchars($codePostal) ?>" disabled>
                        </div>

                        <div class="input-group">
                            <input type="text" id="ville" value="<?php echo htmlspecialchars($ville) ?>" disabled>
                        </div>
                    </div>
                </form>

                <div class="actions-profil">
                    <img src="<?php echo htmlspecialchars($image) ?>" alt="Photo de profil" class="photo-profil">
                    <button id="bouton-modifier" type="button" onclick="window.location.href='./modification_membre.php'">Modifier vos informations</button>
                    <form action="../includes/supprimer_compte.inc.php" method="post" enctype="multipart/form-data">
                        <input id="bouton-supprimer" type="submit" value="Supprimer le compte">
                    </form>
                    <form action="../includes/deconnection.inc.php" method="post" enctype="multipart/form-data">
                        <input id="bouton-supprimer" type="submit" value="Se déconnecter">
                    </form>
                </div>
            </div>
        </section>
    </main>
    <?php require_once FOOTER; ?>
</body>

</html>
