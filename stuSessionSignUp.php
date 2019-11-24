<?php
    SESSION_START(); 
    $_SESSION['loginUser']="STU1";
    $_SESSION['userClass']="STU";
    $out="";
    $out .= $_SESSION['loginUser'];
    $out .= $_SESSION['userClass'];
    echo "<h6> $out<h6>";
    $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established"); 
    $studentID=$_SESSION['loginUser'];
?>

<?php
    function format_time($t) // t = seconds, f = separator 
    {
      return sprintf("%02d%s%02d", floor($t/3600), ':', ($t/60)%60);
    }

    $query="SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession ORDER BY sessionID DESC LIMIT 10;";
    $result=mysqli_query($dbc, $query) or die("Query Failed $query");  
?>


<h3>10 Latest Added Session </h3>
<table border='1'>
    <tr><td>Session ID</td><td>Topic</td><td>Subject Code</td><td>Date</td><td>Start Time</td><td>End Time</td><td>Duration (Hour:Minute)</td><td>Location</td><td>Register</td></tr>
    <?php
        while($row=mysqli_fetch_assoc($result)){
        $duration=format_time(strtotime($row['endTime'])-strtotime($row['startTime']));
    ?>
        <tr>
            <form method='POST'action='stuSessionSignUp.php'>
            <td><?php echo $row['sessionID']; ?></td>
            <td><?php echo $row['topic']; ?></td>
            <td><?php echo $row['subjectCode']; ?></td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo $row['startTime']; ?></td>
            <td><?php echo $row['endTime']; ?></td>
            <td><?php echo $duration; ?></td>
            <td><?php echo $row['location']; ?></td>
            <td>
                <?php 
                    $sessionRegistered=false;
                    $query2="SELECT studentID FROM session_student WHERE sessionID='{$row['sessionID']}';";
                    $result2=mysqli_query($dbc, $query2) or die("Query Failed $query2");
                    $stuID=mysqli_fetch_assoc($result2);
                    if($stuID['studentID']===$_SESSION['loginUser']){
                    $sessionRegistered=true;
                    }
                ?>
                <input type="text" name="sessionID" value="<?php echo $row['sessionID']; ?>" style="display:none">
                <input type="text" name="topic" value="<?php echo $row['topic']; ?>" style="display:none">
                <input type="text" name="subjectCode" value="<?php echo $row['subjectCode']; ?>" style="display:none">
                <input type="text" name="date" value="<?php echo $row['date']; ?>" style="display:none">
                <input type="text" name="startTime" value="<?php echo $row['startTime']; ?>" style="display:none">
                <input type="text" name="endTime" value="<?php echo $row['endTime']; ?>" style="display:none">
                <input type="text" name="duration" value="<?php echo $duration; ?>" style="display:none">
                <input type="text" name="location" value="<?php echo $row['location']; ?>" style="display:none">
                <?php if ($sessionRegistered!=true) {?>
                <input type="submit" name="register" value="Register">
                <?php } 
                    else{
                        echo "Registered";
                    }
                ?>
            </td> 
            </form>
        </tr>
    <?php } ?>
</table>

<?php
    if(isset($_POST['register'])){
        $query="INSERT INTO session_student (sessionID,studentID) VALUES('{$_POST['sessionID']}','{$_SESSION['loginUser']}');";
        $result=mysqli_query($dbc, $query) or die("Query Failed $query");
        echo '<meta http-equiv="refresh" content="0">';
        die();
    }
?>

