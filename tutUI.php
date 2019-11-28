<?php
SESSION_START();
//$_SESSION['loginUser']
if (preg_match("/\ATUT/", @$_SESSION['loginUser'])) {
    ?>
    <html>

    <head>
        <style>
        </style>
    </head>

    <body>
        <h2>Tutor UI</h2>
        <form action='tutNewSession.php' method='POST'>
            <button type='Submit'>Add new tutoring session</button> <br>
        </form>
        <form action='tutShowSession.php' method='POST'>
            <button type='Submit'>Show tutoring session</button> <br>
        </form>
        <form action='logOut.php' method='POST'>
            <button type='Submit'>Log Out</button> <br>
        </form>
    </body>

    </html>
<?php
} else {
    echo "<h3>You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</h3>";
    header("Refresh:5;URL=logOut.php");
    die();
}
?>