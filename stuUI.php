<head>
    <title>USTS- Student UI</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/outStyle.css">
</head>

<?php
SESSION_START();
if (preg_match("/\ASTU/", @$_SESSION['loginUser'])) {
?>

    <ul>
        <li class="active"><a href="stuUI.php">Home</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Tutor Session</a>
            <div class="dropdown-content">
                <a href="stuSessionRegistration.php">Register/Deregister Tutor Session</a>
                <a href="stuShowRegisteredSession.php">Show Registered Tutor Session</a>
            </div>
        </li>
        <li><a href="stuSystemUsageStatistics.php">System Usage Statistics</a></li>
        <li style="float:right"><a href="logOut.php">Log Out</a></li>
    </ul>

    <?php 
    $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established");
    $query="SELECT name FROM student WHERE studentID='{$_SESSION['loginUser']}';";
    $result=mysqli_query($dbc, $query) or die("Query Failed $Query");
    $row=mysqli_fetch_assoc($result);
    mysqli_close($dbc);
?>
<div class="container">
Welcome back to UTeM Student Tutor System (USTS), <span class="important"> <?php echo"{$row['name']}" ?> </span>.
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