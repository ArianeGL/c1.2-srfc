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
                <p>Age requis: <?php echo $ageRequierement; ?></p>
                <p>Nombre d'Attractions<?php echo $nbAttractions; ?></p>
                <p><?php echo $description; ?></p>

                <botton onclick="window.open('<?php echo $menuURL; ?>', '_blank')" class="buttons">
                    <h3>Menu</h3>
                </botton>
            </div>
        </div>
    </main>

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

