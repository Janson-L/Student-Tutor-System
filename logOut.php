<?php
    SESSION_START();
    $_SESSION=array();
    session_destroy();
    echo"You are logged out. You will be redirected back to login page in 3 seconds.";
    header("Refresh:3;URL=login.php");
    die();
?>