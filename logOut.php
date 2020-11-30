<head>
    <title>Log out</title>
    <link rel="stylesheet" href="css/outStyle.css">
</head>
<?php
    SESSION_START();
    $_SESSION=array();
    session_destroy();
    ?>
    <br>
    <div class="prompt">You are logged out. You will be redirected back to login page in 3 seconds.</div>
    <?php
    header("Refresh:3;URL=login.php");
    die();
?>