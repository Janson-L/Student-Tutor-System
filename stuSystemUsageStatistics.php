<head>
    <title>USTS- System Usage Statistics </title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/outStyle.css">
</head>

<?php
    SESSION_START();
    if(preg_match("/\ASTU/",@$_SESSION['loginUser'])){
?>

<ul>
        <li><a href="stuUI.php">Home</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Tutor Session</a>
            <div class="dropdown-content">
                <a href="stuSessionRegistration.php">Register/Deregister Tutor Session</a>
                <a href="stuShowRegisteredSession.php">Show Registered Tutor Session</a>
            </div>
        </li>
        <li class="active"><a href="stuSystemUsageStatistics.php">System Usage Statistics</a></li>
        <li style="float:right"><a href="logOut.php">Log Out</a></li>
    </ul>

<?php
    $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
    $duration=0;
    function format_time_output($t) //fuction to format time in hour and minute for output purposes
        {
            return sprintf("%02d%s%02d%s", floor($t / 3600), ' hour(s) ', ($t / 60) % 60,' minute(s)');
        }

    $query="SELECT COUNT(s.sessionID) AS noOfTutoringSession FROM tutoringSession t,session_student s  WHERE t.sessionID=s.sessionID AND s.studentID='{$_SESSION['loginUser']}';";
    $result=mysqli_query($dbc,$query) or die("Query Failed $query");
    $row = mysqli_fetch_assoc($result);
    $noOfTutoringSession=$row['noOfTutoringSession'];

    $query="SELECT t.startTime, t.endTime FROM tutoringSession t,session_student s  WHERE t.sessionID=s.sessionID AND s.studentID='{$_SESSION['loginUser']}';";
    $result=mysqli_query($dbc,$query) or die("Query Failed $query");
    
    while($row = mysqli_fetch_assoc($result))
    {
        $duration +=(strtotime($row['endTime']) - strtotime($row['startTime']));
    }
    $durationd=format_time_output($duration);

    mysqli_close($dbc);
?>

<h2>System Usage Statistics</h2>
<div class="container">
<table>
    <tr>
        <th>No. of tutoring sessions enrolled:</th>
        <th>Total duration of tutoring sessions attended:</th>
        
    </tr>
    <tr>
    <td><?php echo "$noOfTutoringSession"; ?></td>
        <td><?php echo "$durationd"; ?></td>
    </tr>
</table><br>
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