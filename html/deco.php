<?php
    session_start();
    session_destroy();
    header("Location: consulter_liste_offres_cli-1.php");
?>