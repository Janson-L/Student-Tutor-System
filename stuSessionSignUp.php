<?php
    SESSION_START(); 
    //$_SESSION['loginUser']
    //$_SESSION['userClass']
    $out="";
    $out .= $_SESSION['loginUser'];
    $out .= $_SESSION['userClass'];
    echo "<h6> $out<h6>";
    $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established"); 
    $studentID=$_SESSION['loginUser'];
?>

<body><h3>10 Newly Added Session </h3></body>
<?php
    $query="SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession ORDER BY sessionID DESC LIMIT 10;";
    $result=mysqli_query($dbc, $query) or die("Query Failed $query");     
?>
<table border='1'>
    <tr><td>Session ID</td><td>Topic</td><td>Subject Code</td><td>Date</td><td>Start Time</td><td>End Time</td><td>Duration(Hour(s))</td><td>Location</td><td>Register</td></tr>
    <?php
        while($row=mysqli_fetch_assoc($result)){
        $duration=(strtotime($row['endTime'])-strtotime($row['startTime']))/3600;
    ?>
        <tr>
            <form method="get">
            <td><?php echo $row['sessionID']; ?></td>
            <td><?php echo $row['topic']; ?></td>
            <td><?php echo $row['subjectCode']; ?></td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo $row['startTime']; ?></td>
            <td><?php echo $row['endTime']; ?></td>
            <td><?php echo $duration; ?></td>
            <td><?php echo $row['location']; ?></td>
            <td>
                <input type="text" name="sessionID" value="<?php echo $row['sessionID']; ?>" style="display:none">
                <input type="text" name="topic" value="<?php echo $row['topic']; ?>" style="display:none">
                <input type="text" name="subjectCode" value="<?php echo $row['subjectCode']; ?>" style="display:none">
                <input type="text" name="date" value="<?php echo $row['date']; ?>" style="display:none">
                <input type="text" name="startTime" value="<?php echo $row['startTime']; ?>" style="display:none">
                <input type="text" name="endTime" value="<?php echo $row['endTime']; ?>" style="display:none">
                <input type="text" name="duration" value="<?php echo $duration; ?>" style="display:none">
                <input type="text" name="location" value="<?php echo $row['locaton']; ?>" style="display:none">
                <input type="submit" name="submit" value="Register">
            </td> 
            </form>
        </tr>
<?php
    }
?>
    </table>

