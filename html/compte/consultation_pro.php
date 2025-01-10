<?php
session_start();
require_once "../db_connection.inc.php";
require_once "../includes/consts.inc.php";

global $dbh;

require_once "../includes/verif_connection.inc.php";

function est_membre($email) {
    $ret = false;
    global $dbh;

    $query = "SELECT * FROM " . NOM_SCHEMA . "." . VUE_MEMBRE . " WHERE email = '" . $email . "';";
    $row = $dbh->query($query)->fetch();

    if (isset($row['pseudo'])) $ret = true;

    return $ret;
}

function est_prive($email) {
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
    </script> <?php
            }

            if (est_membre($email)) {
                ?> <script>
        window.location = "./consultation_membre.php";
    </script> <?php
            }

            if (est_prive($email)) {
                $schemaCompte = VUE_PRO_PRIVE;
            } else {
                $schemaCompte = VUE_PRO_PUBLIQUE;
            }
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
                $denomination = $compte['denomination'];
                $IBAN = $compte['iban'];
                $image = $compte['urlimage'];
            } else { ?> 
                <script>
                    window.location = <?php echo LSITE_OFFRES ?>;
                </script>
            <?php } ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./styles/consultation.css">

    <title>Mon Compte - PACT</title>
</head>

<body>
    <?php require_once HEADER ?>

    <main id="box">
        <section class="profile">
            <div class="profile-header">
                <h1>Bonjour, <?php echo htmlspecialchars($denomination); ?></h1>
            </div>
            <div class="profile-row">
                <form>
                    <div class="input-group">
                        <input type="text" id="nom-societe" value="<?php echo htmlspecialchars($denomination) ?>" readonly>
                    </div>

                    <div class="input-group">
                        <input type="text" id="courriel" value="<?php echo htmlspecialchars($email) ?>" readonly>
                    </div>

                    <div class="input-group">
                        <input type="text" id="telephone" value="<?php echo htmlspecialchars($telephone) ?>" readonly>
                    </div>

                    <div class="input-group">
                        <input type="text" id="adresse" value="<?php echo htmlspecialchars($adresse) ?>" readonly>
                    </div>

                    <div id="code-postal-ville">
                        <div class="input-group">
                            <input type="text" id="code-postal" value="<?php echo htmlspecialchars($codePostal) ?>" readonly>
                        </div>

                        <div class="input-group">
                            <input type="text" id="ville" value="<?php echo htmlspecialchars($ville) ?>" readonly>
                        </div>
                    </div>

                    <div class="input-group">
                        <input type="text" id="IBAN" value="<?php echo htmlspecialchars($IBAN) ?>" readonly>
                    </div>
                </form>

                <div class="actions-profil">
                    <img src="<?php echo htmlspecialchars($image) ?>" alt="Photo de profil" class="photo-profil">
                    <button id="bouton-modifier" type="button" onclick="window.location.href='modification_pro.php'">Modifier informations</button>
                    <button id="bouton-supprimer" type="button">Supprimer le compte</button>
                    <form action="../includes/deconnection.inc.php" method="post" enctype="multipart/form-data">
                        <input id="bouton-supprimer" type="submit" value="Se déconnecter">
                    </form>
                </div>
            </div>
        </section>
    </main>
    <?php require_once FOOTER; ?>

</body>
<script src="../includes/main.js"></script>

</html>
