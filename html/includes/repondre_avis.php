<?php
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
require_once "../db_connection.inc.php";
require_once "offre_appartient.php";
require_once "afficher_avis.inc.php";


const EDIT = '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4 4L33 11L36 26L26 36L11 33L4 4ZM4 4L19.172 19.172M24 38L38 24L44 30L30 44L24 38ZM26 22C26 24.2091 24.2091 26 22 26C19.7909 26 18 24.2091 18 22C18 19.7909 19.7909 18 22 18C24.2091 18 26 19.7909 26 22Z" stroke="#254766" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';

const REPORT = '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8 30C8 30 10 28 16 28C22 28 26 32 32 32C38 32 40 30 40 30V6C40 6 38 8 32 8C26 8 22 4 16 4C10 4 8 6 8 6V30ZM8 30V44" stroke="#254766" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M8 30C8 30 10 28 16 28C22 28 26 32 32 32C38 32 40 30 40 30V6C40 6 38 8 32 8C26 8 22 4 16 4C10 4 8 6 8 6V30ZM8 30V44" stroke="#254766" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';


function can_repondre($idAvis)
{
    global $dbh;
    $est_authorise = false;
    if (isset($_SESSION['identifiant'])) {
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
        }
    }
    return $est_authorise;
}


function afficher_form_reponse($idAvis)
{
    global $dbh;
    // Vérifier si une réponse existe
    $reponseExiste = reponse_existe($idAvis);
    // Récupérer la réponse existante si elle existe
    $reponseExistante = '';
    if ($reponseExiste) {
        $query = "SELECT reponse FROM sae._avis WHERE idavis = :idavis";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':idavis', $idAvis, PDO::PARAM_STR);
        $stmt->execute();
        $reponseExistante = $stmt->fetchColumn();
    }
    if ($reponseExiste) {
        // Si une réponse existe, afficher le bouton "Modifier"
        ?>
        <button class="deroulerReponse" data-idavis="<?php echo $idAvis; ?>" style="display: flex; align-items: center;">
    <?php echo EDIT; ?>
</button>
        <form method="post" enctype="multipart/form-data" class="formReponse" id="formReponse-<?php echo $idAvis; ?>" style="display: none;">
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
        <form method="post" enctype="multipart/form-data" class="formReponse" id="formReponse-<?php echo $idAvis; ?>" style="display: none;">
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
        // Sélectionner tous les boutons avec la classe 'deroulerReponse'
        document.querySelectorAll('.deroulerReponse').forEach(button => {
            button.addEventListener('click', function() {
                const idAvis = this.getAttribute('data-idavis');
                const form = document.querySelector(`#formReponse-${idAvis}`);
                if (form.style.display === 'none') {
                    form.style.display = 'block';
                } else {
                    form.style.display = 'none';
                }
            });
        });
    </script>
    <?php
}

if (isset($_POST['valider']) && isset($_POST['idAvis'])) {
    $idAvis = $_POST['idAvis'];
    $reponse = trim($_POST['reponse']);

    if (can_repondre($idAvis)) {
        // Insère ou met à jour la réponse dans la table
        $queryInsert = 'UPDATE sae._avis SET reponse = :reponse, datereponse = CURRENT_DATE WHERE idavis = :idavis;';
        $sth = $dbh->prepare($queryInsert);
        $sth->bindParam(':reponse', $reponse, PDO::PARAM_STR);
        $sth->bindParam(':idavis', $idAvis, PDO::PARAM_STR); 
        $sth->execute();
        $sth = null;
        echo '<meta http-equiv="refresh" content="0">'; // Rafraîchit la page après la soumission
    } else {
        echo 'Vous n\'avez pas l\'autorisation de répondre à cet avis.';
    }
}
?>
