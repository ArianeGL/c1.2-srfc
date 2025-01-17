<?php
error_log("Script PHP démarré.");

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
require_once "../db_connection.inc.php";
require_once "../includes/offre_appartient.php";
require_once "../includes/afficher_avis.inc.php";

// Récupérer l'ID de l'offre depuis l'URL
$idOffre = $_GET['idoffre'];
error_log("ID Offre récupéré : " . $idOffre);

function can_repondre($idAvis) {
    global $dbh;
    $est_authorise = false;
    if (isset($_SESSION['identifiant'])) {
        error_log("Vérification des autorisations pour répondre à l'avis.");
        $queryCompte = 'SELECT COUNT(*) FROM ' . NOM_SCHEMA . '.' . NOM_TABLE_COMPTE . ' WHERE email = :email';
        $sthCompte = $dbh->prepare($queryCompte);
        $sthCompte->bindParam(':email', $_SESSION['identifiant'], PDO::PARAM_STR);
        $sthCompte->execute();
        $countProfessionnel = $sthCompte->fetchColumn();
        // Vérifie si l'utilisateur est le propriétaire de l'offre associée à l'avis
        $queryAvis = 'SELECT idoffre FROM ' . NOM_SCHEMA . '.' . VUE_AVIS . ' WHERE idavis = :idavis';
        $sthAvis = $dbh->prepare($queryAvis);
        $sthAvis->bindParam(':idavis', $idAvis, PDO::PARAM_STR);
        $sthAvis->execute();
        $avis = $sthAvis->fetch(PDO::FETCH_ASSOC);
        if ($countProfessionnel != 0 && offre_appartient($_SESSION['identifiant'], $avis['idoffre'])) {
            $est_authorise = true;
            error_log("L'utilisateur est autorisé à répondre à l'avis.");
        } else {
            error_log("L'utilisateur n'est pas autorisé à répondre à l'avis.");
        }
    }
    return $est_authorise;
}

function afficher_form_reponse($idAvis) {
    global $idOffre;
    error_log("Affichage du formulaire de réponse pour l'avis ID: $idAvis, Offre ID: $idOffre");

    global $dbh;
    // Vérifier si une réponse existe
    $reponseExiste = reponse_existe($idAvis);
    error_log("Réponse existante pour l'avis ID $idAvis : " . ($reponseExiste ? "Oui" : "Non"));

    // Récupérer la réponse existante si elle existe
    $reponseExistante = '';
    if ($reponseExiste) {
        $query = "SELECT reponse FROM sae._avis WHERE idavis = :idavis";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':idavis', $idAvis, PDO::PARAM_STR);
        $stmt->execute();
        $reponseExistante = $stmt->fetchColumn();
        error_log("Réponse existante récupérée : " . $reponseExistante);
?>
        <button class="deroulerReponse" data-idavis="<?php echo $idAvis; ?>" style="display: flex; align-items: center;">
            <?php echo EDIT; ?>
        </button>
        <form method="post" action="informations.php?idoffre=<?php echo $idOffre; ?>" enctype="multipart/form-data" class="formReponse" id="formReponse-<?php echo $idAvis; ?>" style="display: block;">
            <input type="hidden" name="idAvis" value="<?php echo $idAvis; ?>">
            <label for="reponse-<?php echo $idAvis; ?>">
                <h1>Réponse</h1>
            </label>
            <textarea name="reponse" id="reponse-<?php echo $idAvis; ?>" placeholder="Votre réponse" required><?php echo htmlspecialchars($reponseExistante); ?></textarea>
            <br>
            <input class="bigButton" type="submit" name="valider" value="Valider" id="valider-<?php echo $idAvis; ?>">
        </form>
    <?php
    } else {
        // Si aucune réponse n'existe, afficher le bouton "Répondre à cet avis"
    ?>
        <button class="deroulerReponse" data-idavis="<?php echo $idAvis; ?>" style="display: flex; align-items: center;">
            <?php echo EDIT; ?>
        </button>
        <form method="post" action="informations.php?idoffre=<?php echo $idOffre; ?>" enctype="multipart/form-data" class="formReponse" id="formReponse-<?php echo $idAvis; ?>" style="display: block;">
            <input type="hidden" name="idAvis" value="<?php echo $idAvis; ?>">
            <label for="reponse-<?php echo $idAvis; ?>">
                <h1>Réponse</h1>
            </label>
            <textarea name="reponse" id="reponse-<?php echo $idAvis; ?>" placeholder="Votre réponse" required></textarea>
            <br>
            <input class="bigButton" type="submit" name="valider" value="Valider" id="valider-<?php echo $idAvis; ?>">
        </form>
        <?php
    }
        ?>

    <script>
        console.log("Script JavaScript démarré.");

        // Sélectionner tous les boutons avec la classe 'deroulerReponse'
        document.querySelectorAll('.deroulerReponse').forEach(button => {
            button.addEventListener('click', function() {
                const idAvis = this.getAttribute('data-idavis');
                console.log("Bouton cliqué pour l'avis ID : " + idAvis);

                const form = document.querySelector(`#formReponse-${idAvis}`);
                if (form.style.display === 'none') {
                    form.style.display = 'block';
                    console.log("Formulaire affiché pour l'avis ID : " + idAvis);
                } else {
                    form.style.display = 'none';
                    console.log("Formulaire masqué pour l'avis ID : " + idAvis);
                }
                })});
        </script>
    <?php
}

if (isset($_POST['valider']) && isset($_POST['idAvis'])) {
    error_log("Formulaire de réponse soumis.");
    $idAvis = $_POST['idAvis'];
    $reponse = trim($_POST['reponse']);

    if (can_repondre($idAvis)) {
        error_log("Tentative de mise à jour de la réponse pour l'avis ID : $idAvis");
        // Insère ou met à jour la réponse dans la table
        $queryInsert = 'UPDATE sae._avis SET reponse = :reponse, datereponse = CURRENT_DATE WHERE idavis = :idavis;';
        $sth = $dbh->prepare($queryInsert);
        $sth->bindParam(':reponse', $reponse, PDO::PARAM_STR);
        $sth->bindParam(':idavis', $idAvis, PDO::PARAM_STR);
        $sth->execute();
        $sth = null;

        error_log("Réponse mise à jour avec succès pour l'avis ID : $idAvis");
        exit();
    } else {
        error_log("L'utilisateur n'est pas autorisé à répondre à l'avis ID : $idAvis");
        echo 'Vous n\'avez pas l\'autorisation de répondre à cet avis.';
    }
}
?>
