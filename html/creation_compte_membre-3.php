<?php
include('db_connection_inc.php');



try {
    $conn = new PDO("host=$servername dbname=$dbname user=$username password=$password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $prenom = substr(trim($_POST['prenom']), 0, 20); 
    $nom = substr(trim($_POST['nom']), 0, 20); 
    $pseudo = substr(trim($_POST['pseudo']), 0, 40); 
    $tel = $_POST['tel']; 
    $mdp = substr(trim($_POST['mdp']), 0, 20); 
    $email = substr(trim($_POST['email']), 0, 50); 
    $adresse = substr(trim($_POST['adresse']), 0, 50); 
    $ville = substr(trim($_POST['ville']), 0, 30); 
    $code = substr(trim($_POST['code']), 0, 5); 


    if (strlen($tel) > 10) {
        echo "Le numéro de téléphone doit contenir 10 chiffres.";
        exit;
    }

    if (strlen($code) !== 5) {
        echo "Le code postal doit contenir 5 chiffres.";
        exit;
    }
        
        $sql = "INSERT INTO comptemembre (prenom, nom, pseudo, telephone, motdepasse, email, adresse, ville, codepostal, urlimage) 
        VALUES (:prenom, :nom, :pseudo, :tel, :mdp, :email, :adresse, :ville, :code_postal, :urlimage
        ) RETURNING idcompte";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':prenom' => $prenom, ':nom' => $nom,':pseudo' => $pseudo,':tel' => $tel,':mdp' => $mdp,':email' => $email,
        ':adresse' => $adresse,':ville' => $ville,':code_postal' => $code_postal,':urlimage' => 'default.png'
        ]);
        $idcompte = $stmt->fetchColumn();


        if (isset($_FILES['photo'])) {
            $user_dir = 'images_importees/' . $idcompte;
            if (!file_exists($user_dir)) {
                mkdir($user_dir, 0755, true);
            }
            $filename = $idcompte . '.png';
            $destination = $user_dir . '/' . $filename;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                $urlimage = 'images_importees/' . $idcompte . '/' . $filename;
                $_SESSION['photo'] = $urlimage;
                
                $update_sql = "UPDATE comptemembre SET urlimage = :urlimage WHERE idcompte = :idcompte";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->execute([
                    ':urlimage' => $urlimage,
                    ':idcompte' => $idcompte
                ]);
            }
        }

        header('Location: confirmation.php');
        exit();
    }

} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}
?>
        
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="./styles/creation.css"> 
    <title>Création compte membre</title>
</head>
<body>
  
<?php require_once 'header_inc.html'; ?>

  <section>
  <h1>Création du compte membre</h1>
  <form action="consultation_pro.php" method="post" enctype="multipart/form-data">
        <div class="form-container">
            <div id="groupeInput" class="form-left">
                <div class="form-row">
                    <input type="text" class="input-creation" id="prenom" name="prenom" placeholder="Prénom *" required />
                    <input type="text" class="input-creation" id="nom" name="nom" placeholder="Nom *" required />
                </div>
                <div class="form-row">
                    <input type="text" class="input-creation" id="pseudo" name="pseudo" placeholder="Pseudonyme *" required />
                    <input type="text" class="input-creation" id="tel" name="tel" placeholder="Téléphone *" required />
                </div>
                <div class="form-row">
                    <input type="password" class="input-creation" id="mdp" name="mdp" placeholder="Mot de passe *" required />
                    <input type="password" class="input-creation" id="confmdp" name="confmdp" placeholder="Confirmation mdp *" required />
                </div>
                <div class="form-row">
                    <input type="email" class="input-creation" id="email" name="email" placeholder="Adresse mail *" required />
                </div>
                <div class="form-row">
                    <input type="text" class="input-creation" id="adresse" name="adresse" placeholder="Adresse *" required />
                </div>
                <div class="form-row">
                    <input type="text" class="input-creation" id="ville" name="ville" placeholder="Ville *" required />
                    <input type="text" class="input-creation" id="code" name="code" placeholder="Code postal *" required />
                </div>
            </div>

            <div id="form-photo">
                <div id="photo-profil" class="form-right">
                    <?php if (isset($_SESSION['photo'])) { ?>
                        <img src="<?php echo htmlspecialchars($_SESSION['photo']); ?>" alt="Photo de profil" />
                    <?php } else { ?>
                        <img src="images/photoProfileDefault.png" alt="Photo de profil" id="photo-profil" />
                    <?php } ?>
                </div>
                    <label class="smallButton" for="photo">Importer une image</label>
                    <input type="file" id="photo" name="photo" style="display:none;" />
                </div>
            
            </div>
        <div id="form-footer">
                <p id="obligation">* Obligatoire</p>
                <input class="button" id="bouton-modifier" type="submit" value="Valider" />
                <div id="accepte">
                    <div class="position-checkbox">
                        <input type="checkbox" value="communication" id="communication">
                        <label class="bouton-info" for="communication">J’accepte de recevoir des communications commerciales</label>
                    </div>
                    <br />
                    <div class="position-checkbox">
                        <input type="checkbox" value="condition" id="condition" required>
                        <label class="bouton-info" for="condition">J’accepte les conditions générales d’utilisation</label>
                    </div>
                </div>
            </div>
    </form>
    
  </section>
  <?php require_once "footer_inc,html"; ?>

</body>
<script src="main.js"></script>
</html>