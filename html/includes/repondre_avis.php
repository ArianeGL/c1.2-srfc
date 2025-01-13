<?php
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
require_once "../db_connection.inc.php";
require_once "offre_appartient.php";
require_once "afficher_avis.inc.php";



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
        <button class="button deroulerReponse" data-idavis="<?php echo $idAvis; ?>">Modifier</button>
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
        <button class="button deroulerReponse" data-idavis="<?php echo $idAvis; ?>">Répondre à cet avis</button>
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