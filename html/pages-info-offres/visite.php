<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;family=Concert+One&display=swap" rel="stylesheet">

    <title>PACT</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
</head>

<body>
    <?php require_once 'header_inc.html'; ?>


    <!-- Main content -->
    <main id="top">
        <div>
            <h1>
                <?php echo $categorie; ?> &#x2022; <?php echo $name; ?> &#x2022; <?php echo $address; ?>
            </h1>
        </div>

        
        <p><?php echo $resume; ?></p>
        
        <div>
            <div class="info">
                <p>Duree: <?php echo $duration; ?></p>
                <p>Visite guidee: <?php echo $isGuided; ?></p>
                <p><?php echo $description; ?></p>
            </div>
        </div>
    </main>

    <?php require_once "footer_inc,html"; ?>

</body>
<script src="main.js">
if (pageName.includes("1")) {
    document.getElementById("div1").classList.add("b1-indicator");
    document.getElementById("div1").classList.remove("hidden");
    } else 
    if (pageName.includes("2")) {
    document.getElementById("div2").classList.add("b2-indicator");
    document.getElementById("div2").classList.remove("hidden");
    } else 
    if (pageName.includes("3")) {
    document.getElementById("div3").classList.add("b3-indicator");
    document.getElementById("div3").classList.remove("hidden");
    }
</script>
</html>