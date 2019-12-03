<?php
SESSION_START();
if (preg_match("/\AADM/", @$_SESSION['loginUser'])) {
    ?>
    <head>
    <link rel="stylesheet" href="css/navbar.css">
    </head>
    <ul>
        <li class="active"><a href="admUI.php">Home</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Manage Users</a>
            <div class="dropdown-content">
            <a href="admAddUser.php">Add User</a>
            <a href="admManageUsers.php">Update and Delete Users</a>
        </div>
        </li>
        <li><a href="admManageTutorSession.php">Manage Tutor Session</a></li>
        <li><a href="admSystemUsageStatistics.php">System Usage Statistics</a></li>
        <li style="float:right"><a href="logOut.php">Log Out</a></li>
    </ul>

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
    <form action='admSystemUsageStatistics.php' method='POST'>
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