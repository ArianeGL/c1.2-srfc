<?php
require_once "../includes/consts.inc.php";

    session_start();
    session_destroy();
    header("Location: ../offres/liste.php");
?>