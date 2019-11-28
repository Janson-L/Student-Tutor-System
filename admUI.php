<?php
SESSION_START();
if (preg_match("/\AADM/", @$_SESSION['loginUser'])) {
    ?>

    <h2>Admin UI</h2>
    <form action='admAddUser.php' method='POST'>
        <button type='Submit'>Add Users</button> <br>
    </form>
    <form action='admManageUsers.php' method='POST'>
        <button type='Submit'>Manage Users</button> <br>
    </form>
    <form action='admManageTutorSession.php' method='POST'>
        <button type='Submit'>Delete Tutoring Session</button> <br>
    </form>
    <form action='' method='POST'>
        <button type='Submit'>Show System Usage Statistics</button> <br>
    </form>
    <form action='logOut.php' method='POST'>
        <button type='Submit'>Log Out</button> <br>
    </form>


<?php
} else {
    echo "<h3>You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</h3>";
    header("Refresh:5;URL=logOut.php");
    die();
}
?>