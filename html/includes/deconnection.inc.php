<?php
require_once "../includes/consts.inc.php";

    session_start();
    session_destroy();
    <script>
        window.location.href = <?php echo LISTE_OFFRES ?>;
    </script>
?>