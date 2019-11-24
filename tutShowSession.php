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
    $tutorID=$_SESSION['loginUser'];

    $query="SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession WHERE tutorid='$tutorID'ORDER BY date DESC;";
    $result=mysqli_query($dbc, $query) or die("Query Failed $query");

    echo"<table border='1'>";
    echo"<tr><td>Session ID</td><td>Topic</td><td>Subject Code</td><td>Date</td><td>Start Time</td><td>End Time</td><td>Location</td></tr>\n";
    while($row=mysqli_fetch_assoc($result)){
        echo"<tr><td>{$row['sessionID']}</td><td>{$row['topic']}</td><td>{$row['subjectCode']}</td><td>{$row['date']}</td><td>{$row['startTime']}</td><td>{$row['endTime']}</td><td>{$row['location']}</td></tr>\n";
    }
    echo"</table>";
?>