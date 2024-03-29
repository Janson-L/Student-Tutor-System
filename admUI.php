<head>
    <title>USTS- Admin UI</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/outStyle.css">
</head>

<?php
SESSION_START();
if (preg_match("/\AADM/", @$_SESSION['loginUser'])) {
?>
    <ul>
        <li class="active"><a href="admUI.php">Home</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Manage Users</a>
            <div class="dropdown-content">
            <a href="admAddUser.php">Add User</a>
            <a href="admManageUsers.php">Update and Delete Users</a>
        </div>
        </li>
        <li><a href="admManageTutorSession.php">Manage Tutoring Session</a></li>
        <li><a href="editPersonalInfo.php">Edit Personal Info</a></li>
        <li><a href="admSystemUsageStatistics.php">System Usage Statistics</a></li>
        <li style="float:right"><a href="logOut.php">Log Out</a></li>
    </ul>
<?php 
    $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established");
    $query="SELECT name FROM admin WHERE adminID='{$_SESSION['loginUser']}';";
    $result=mysqli_query($dbc, $query) or die("Query Failed $Query");
    $row=mysqli_fetch_assoc($result);
    mysqli_close($dbc);
?>
<div class="container">
Welcome back to UTeM Student Tutor System(USTS), <span class="important"> <?php echo"{$row['name']}" ?> </span>.
</div>

<div class="row">
<h2>Note:</h2>
</div>
<div class="row">
<p>1. UTeM Student Tutor System is a platform for student to join a tutoring session as well as host a tutoring session.</p>
</div>
<div class="row">
<p>2.Admins are free to moderate. However, abuse of power will be penalized.</p>
</div>
</div>
<div class="row">
<p>3. Any problem/suggestion/feedback? Feel free to send an email to dev@USTS.com .</p>
</div>

<?php
} else { 
    ?>
    <br>
    <div class="prompt">You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</div>
    <?php
    header("Refresh:5;URL=logOut.php");
    die();
}
?>