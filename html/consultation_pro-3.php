<?php
include('db_connection_inc.php');

$conn = new PDO("host=$servername dbname=$dbname user=$username password=$password");

if (!$conn) {
    echo "Erreur de connexion à la base de données.";
    exit;
}

if (isset($_POST['idcompte'])) {
    $idCompte = $_GET['idcompte'];
}

$sql = "SELECT email, numadressecompte, ruecompte, villecompte, codepostalcompte, telephone, urlimage, denomination, iban,
        FROM comptesProfessionnel 
        WHERE idCompte = $1";
$result = pg_query_params($conn, $sql, array($idCompte));

if ($result && pg_num_rows($result) > 0) {
    $row = pg_fetch_assoc($result);
    $email = $row['email'];
    $adresse = $row['numadressecompte'] . " " . $row['ruecompte'];
    $ville = $row['villecompte'];
    $codePostal = $row['codepostalcompte'];
    $telephone = $row['telephone'];
    $denomination = $row['denomination'];
    $IBAN = $row['iban'];
    $image = $row['urlimage'];
} else {
    http_response_code(404);
    echo "
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Erreur 404 - Compte non trouvé</title>
    </head>
    <body>
        <div class='error-page'>
            <h1>404 - Compte non trouvé</h1>
            <p>Le compte demandé n'existe pas.</p>
        </div>
    </body>
    </html>";
    exit;
}

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./styles/consultation.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

    <title>PACT</title>
</head>

<body>
<header>
        <div id="homeButtonID" class="homeButton">
            <img src="./IMAGES/LOGO-SRFC.webp" alt="HOME PAGE" height="80%" style="margin-left: 5%; margin-right: 5%;">
            <h2>PACT</h2>
            <p id="slogan" class="sloganHide">Des avis qui comptent, des voyages qui marquent.</p>
        </div>
        <div>
            <div class="container">
                <button class="buttons header-button1">
                    <h4>Offres</h4>
                </button>
                
                <!-- Button for back office -->
                <button class="buttons header-button2">
                    <h4>Factures</h4>
                </button>
                
                <!-- Button for front office -->
                <button style="display: none;" class="buttons header-button2">
                    <h4>R&eacute;cent</h4>
                </button>
                
                <button class="buttons header-button3">
                    <h4>Compte</h4>
                </button>
            </div>
            <div class="indicator">
                <div id="div1" class="hidden"></div>
                <div id="div2" class="hidden"></div>
                <div id="div3" class="seek"></div>
            </div>
        </div>
    </header>
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
                    <input type="text" id="telephone" value=<?php echo htmlspecialchars($telephone) ?>readonly>
                </div>

                <div class="input-group">
                    <input type="text" id="adresse" value="<?php echo htmlspecialchars($adresse)?>" readonly>
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
        </div>
            </div>
            
        </section>
    </main>
    <?php require_once "footer_inc,html"; ?>

</body>
<script src="main.js"></script>
</html>
