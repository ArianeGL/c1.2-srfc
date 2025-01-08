<?php
require_once "../includes/consts.inc.php";

    session_start();
    session_destroy();
    <script>
        window.location = <?php echo LISTE_OFFRES ?>;
    </script>
?>