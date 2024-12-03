    <!-- Main content -->
    <main id="top">
        <div>
            <h1>
                <?php echo $categorie; ?> &#x2022; <?php echo $name; ?> &#x2022; <?php echo $address; ?>
            </h1>
        </div>

        <p>Prix: <?php echo $priceRange; ?></p>

        <p><?php echo $resume; ?></p>

        <div>
            <div class="info">
                <table class="meal-table">
                    <tr>
                        <th>Repas</th>
                        <th>Servi</th>
                    </tr>
                    <tr>
                        <td>Petit déjeuner</td>
                        <td><?php if ($breakfast) echo "OUI";
                            else echo "NON" ?></td>
                    </tr>
                    <tr>
                        <td>Déjeuner</td>
                        <td><?php if ($lunch) echo "OUI";
                            else echo "NON" ?></td>
                    </tr>
                    <tr>
                        <td>Dîner</td>
                        <td><?php if ($dinner) echo "OUI";
                            else echo "NON" ?></td>
                    </tr>
                    <tr>
                        <td>Boissons</td>
                        <td><?php if ($drinks) echo "OUI";
                            else echo "NON" ?></td>
                    </tr>
                    <tr>
                        <td>Brunch</td>
                        <td><?php if ($brunch) echo "OUI";
                            else echo "NON" ?></td>
                    </tr>
                </table>

                <p><?php echo $description; ?></p>


                <div class="buttons">
                    <button onclick="window.open('<?php echo $menuURL; ?>', '_blank')" class="buttons">
                        <h3>Menu</h3>
                    </button>
                    <button id="modifier">
                        <h3>Modifier mon offre</h3>
                    </button>

                    <script>
                        document.getElementById("modifier").onclick = function() {
                            location.href = "./modifier_offre.php?idoffre=<?php echo $offerId ?>";
                        };
                    </script>
                </div>
            </div>
        </div>
    </main>


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
