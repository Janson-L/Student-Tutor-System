<?php
SESSION_START();
//$_SESSION['loginUser']
if (preg_match("/\ATUT/", @$_SESSION['loginUser'])) {
?>

<?php
    $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
    $duration=0;
    $noOfStudentD=0;
    function format_time_output($t) //fuction to format time in hour and minute for output purposes
        {
            return sprintf("%02d%s%02d%s", floor($t / 3600), ' hour(s) ', ($t / 60) % 60,' minute(s)');
        }
    
    $query="SELECT COUNT(sessionID) AS noOfTutoringSession FROM tutoringSession WHERE tutorID='{$_SESSION['loginUser']}';";
    $result=mysqli_query($dbc,$query) or die("Query Failed $query");
    $row = mysqli_fetch_assoc($result);
    $noOfTutoringSession=$row['noOfTutoringSession'];

    //$query="SELECT COUNT(s.studentID) AS noOfStudent FROM tutoringSession t,session_student s  WHERE t.sessionID=s.sessionID AND t.tutorID='{$_SESSION['loginUser']}';";
    $query="SELECT COUNT(DISTINCT a.name) AS noOfStudent FROM tutoringSession t,session_student s, student a WHERE s.studentID=a.StudentID AND t.sessionID=s.sessionID AND t.tutorID='{$_SESSION['loginUser']}';";
    $result=mysqli_query($dbc,$query) or die("Query Failed $query");
    while($row = mysqli_fetch_assoc($result))
    {
        $noOfStudentD+=$row['noOfStudent'];
    }

    $query="SELECT startTime, endTime FROM tutoringSession WHERE tutorID='{$_SESSION['loginUser']}';";
    $result=mysqli_query($dbc,$query) or die("Query Failed $query");
    
    while($row = mysqli_fetch_assoc($result))
    {
        $duration +=(strtotime($row['endTime']) - strtotime($row['startTime']));
    }
    $durationd=format_time_output($duration);

?>

<table border='1'>
    <tr>
        <th>No. of tutoring sessions organized:</th>
        <td><?php echo "$noOfTutoringSession"; ?></td>
    </tr>
</table><br>

<table border='1'>
    <tr>
        <th>Total no. of distinct students taught:</th>
        <td><?php echo "$noOfStudentD"; ?></td>
    </tr>
</table><br>

<table border='1'>
    <tr>
        <th>Total duration of tutoring sessions organized:</th>
        <td><?php echo "$durationd"; ?></td>
    </tr>
</table>

<?php
} else {
    echo "<h3>You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</h3>";
    header("Refresh:5;URL=logOut.php");
    die();
}
?>