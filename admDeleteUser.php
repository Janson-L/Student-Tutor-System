<?php
SESSION_START();
if (preg_match("/ADM/", @$_SESSION['loginUser'])) {
?>

<h2> </h2>
<form method='POST' action='admManageUsers.php'>
<input type="submit" value="Back to Admin UI">
</form>
<?php
}
else {
    echo "<h3>You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</h3>";
    header("Refresh:5;URL=logOut.php");
   die();
}
?>