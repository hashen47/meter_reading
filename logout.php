<?php

session_start();
if (isset($_SESSION["uid"])) {
    $_SESSION["uid"] = null;
    session_destroy();
}

header("location: index.php");
die();
