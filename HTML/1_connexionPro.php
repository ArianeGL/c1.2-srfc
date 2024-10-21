<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="creation.css"> 
    <title>PACT - Connexion compte pro</title>
</head>

<body>
  <header>
      <h1>Connexion à un compte profesionnel</h1>
  </header>
<form action="index.php" method="post" enctype="multipart/form-data">
    <label class="identifiant"> Identifiant</label>
    <input type="text" id="identifiant" name="identifiant" required>
    <br />
    <label class="motdepasse">Mot de passe</label>
    <input type="password" id="motdepasse" name="motdepasse" required />
    <br />
    <input class="bouton" type="submit" value="Se connecter">
    <input class="bouton" type="submit" value="Mot de passe oublié">
    <input class="bouton" type="submit" value="Créer un compte">
    
</form>
</body>
</html>