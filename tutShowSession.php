<?php
    SESSION_START(); 
    $_SESSION['loginUser']="TUT1";
    $_SESSION['userClass']="TUT";
    $out="";
    $out .= $_SESSION['loginUser'];
    $out .= $_SESSION['userClass'];
    echo "<h6> $out<h6>";
?>

<?php
    $out="";
    $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established");

    function format_time_output($t) // t = seconds, f = separator 
    {
        return sprintf("%02d%s%02d", floor($t / 3600), ':', ($t / 60) % 60);
    }

    $query="SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession WHERE tutorid='{$_SESSION['loginUser']}'ORDER BY date DESC;";
    $result=mysqli_query($dbc, $query) or die("Query Failed $query");

    echo"<table border='1'>";
    echo"<tr><th>Session ID</th><th>Topic</th><th>Subject Code</th><th>Date</th><th>Start Time</th><th>End Time</th><th>Duration(Hour(s))</th><th>Location</th></tr>\n";
    while($row=mysqli_fetch_assoc($result)){
        $durationd = format_time_output(strtotime($row['endTime']) - strtotime($row['startTime']));
        echo"<tr><td>{$row['sessionID']}</td><td>{$row['topic']}</td><td>{$row['subjectCode']}</td><td>{$row['date']}</td><td>{$row['startTime']}</td><td>{$row['endTime']}</td><td>$durationd</td><td>{$row['location']}</td></tr>\n";
    }
    echo"</table>";
?>