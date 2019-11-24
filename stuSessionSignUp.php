<?php
    SESSION_START(); 
    //$_SESSION['loginUser']
    //$_SESSION['userClass']
    $out="";
    $out .= $_SESSION['loginUser'];
    $out .= $_SESSION['userClass'];
    echo "<h6> $out<h6>";
?>

<?php
    $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established"); 
    $studentID=$_SESSION['loginUser'];

    $query="SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession ORDER BY date DESC,startTime DESC;";
    $result=mysqli_query($dbc, $query) or die("Query Failed $query");

    echo"<table border='1'>";
    echo"<tr><td>Session ID</td><td>Topic</td><td>Subject Code</td><td>Date</td><td>Start Time</td><td>End Time</td><td>Duration(Hour(s))</td><td>Location</td></tr>\n";
    while($row=mysqli_fetch_assoc($result)){
        $duration=(strtotime($row['endTime'])-strtotime($row['startTime']))/3600;
        echo"<tr><td>{$row['sessionID']}</td><td>{$row['topic']}</td><td>{$row['subjectCode']}</td><td>{$row['date']}</td><td>{$row['startTime']}</td><td>{$row['endTime']}</td><td>$duration</td><td>{$row['location']}</td></tr>\n";
    }
    echo"</table>";
?>
