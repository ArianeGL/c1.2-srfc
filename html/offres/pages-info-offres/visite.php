<!-- Main content -->
<main id="top">
    <div>
        <h1>
            <?php echo $categorie; ?> &#x2022; <?php echo $name; ?>
        </h1>
    </div>

    <h3><?php echo $address; ?></h3>

    <p><?php echo $resume; ?></p>

    <hr style="border: none; border-top: 2px solid var(--navy-blue); margin: 20px; margin-left: 0px;">

    <section>
        <div class="img-container">

            <?php
            $queryImage = 'SELECT DISTINCT ON (' . NOM_SCHEMA . '.' . NOM_TABLE_IMGOF . '.idoffre) * FROM ' . NOM_SCHEMA . '.' . NOM_TABLE_IMGOF . ' WHERE idoffre = :idoffre';
            $sthImage = $dbh->prepare($queryImage);
            $sthImage->bindParam(':idoffre', $id);
            $sthImage->execute();
            $image = $sthImage->fetch(PDO::FETCH_ASSOC);
            ?>
            <img src="<?php echo $image['urlimage']; ?>" alt="Nom_image" />

        </div>

        <section class="info">
            <p>Dur&eacute;e: <?php echo $duration; ?></p>
            <p>Visite guidee: <?php echo $isGuided; ?></p>
            <h3><?php echo $description; ?></h3>
        </section>
    </section>

    <section class="buttons">
        <?php
        $query_compte = "SELECT email FROM sae._compte 
        INNER JOIN sae._offre on _compte.idcompte = _offre.idcompte
        WHERE _offre.idoffre = :idoffre";
        $stmt_compte = $dbh->prepare($query_compte);
        $stmt_compte->bindParam(':idoffre', $id, PDO::PARAM_STR);
        $stmt_compte->execute();
        $compte = $stmt_compte->fetch(PDO::FETCH_ASSOC)["email"];

        if ($compte == $_SESSION['identifiant']) {
            if ($isOnline) {
        ?><button class="redButton" onclick="window.location='mettre_hors_ligne.php?idoffre=<?php echo $id ?>'">Mettre hors-ligne</button>
            <?php
            } else {
            ?><button class="redButton" onclick="window.location='mettre_en_ligne.php?idoffre=<?php echo $id ?>'">Mettre en ligne</button>
            <?php
            }
            ?>
            <button class="button" onclick="window.location='../offres/modifier.php?idoffre=<?php echo $id ?>'">Modifier l'offre</button>
            <button class="button" onclick="window.location='../factures/consulter.php?idoffre=<?php echo $id ?>'">Facture</button>
        <?php
        } ?>
    </section>
