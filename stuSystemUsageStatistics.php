<head>
    <title>USTS- </title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/outStyle.css">
</head>

<?php
    SESSION_START();
    if(preg_match("/\ASTU/",@$_SESSION['loginUser'])){
?>

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

?>

<table border='1'>
    <tr>
        <th>No. of tutoring sessions enrolled:</th>
        <td><?php echo "$noOfTutoringSession"; ?></td>
    </tr>
</table><br>

<table border='1'>
    <tr>
        <th>Total duration of tutoring sessions attended:</th>
        <td><?php echo "$durationd"; ?></td>
    </tr>
</table>

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