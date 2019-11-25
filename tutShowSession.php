<?php
SESSION_START();
//$_SESSION['loginUser'] = "STU1";
//$_SESSION['userClass'] = "STU";
$out = "";
$dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");

function format_time_output($t) // t = seconds, f = separator 
{
    return sprintf("%02d%s%02d", floor($t / 3600), ':', ($t / 60) % 60);
}

$query = "SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession WHERE tutorid='{$_SESSION['loginUser']}'ORDER BY date DESC;";
$result = mysqli_query($dbc, $query) or die("Query Failed $query");

?>
<h3>Show Tutor Session</h3>
<table border='1'>
    <tr>
        <th>Session ID</th>
        <th>Topic</th>
        <th>Subject Code</th>
        <th>Date</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Duration(Hour(s))</th>
        <th>Location</th>
        <th>No. of Students</th>
        <th>Show Student List</th>
    </tr>

    <tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $durationd = format_time_output(strtotime($row['endTime']) - strtotime($row['startTime'])); 
            
            $query2="SELECT COUNT(studentID) AS noOfStudent FROM session_student WHERE sessionID='{$row['sessionID']}';";
            $result2=mysqli_query($dbc,$query2) or die("Query Failed $query2");
            $row2=mysqli_fetch_assoc($result2);
            ?>


            <form method='POST'action='tutShowStudents.php'>
                <td><?php echo "{$row['sessionID']}"; ?></td>
                <td><?php echo "{$row['topic']}" ;?></td>
                <td><?php echo "{$row['subjectCode']}"; ?></td>
                <td><?php echo "{$row['date']}"; ?></td>
                <td><?php echo "{$row['startTime']}"; ?></td>
                <td><?php echo "{$row['endTime']}"; ?></td>
                <td><?php echo "$durationd"; ?></td>
                <td><?php echo "{$row['location']}"; ?></td>
                <td><?php echo "{$row2['noOfStudent']}"; ?></td>
                <td>
                    <input type="text" name="sessionID" value="<?php echo $row['sessionID']; ?>" style="display:none">
                    <input type="text" name="topic" value="<?php echo $row['topic']; ?>" style="display:none">
                    <input type="text" name="subjectCode" value="<?php echo $row['subjectCode']; ?>" style="display:none">
                    <input type="text" name="date" value="<?php echo $row['date']; ?>" style="display:none">
                    <input type="text" name="startTime" value="<?php echo $row['startTime']; ?>" style="display:none">
                    <input type="text" name="endTime" value="<?php echo $row['endTime']; ?>" style="display:none">
                    <input type="text" name="duration" value="<?php echo $durationd; ?>" style="display:none">
                    <input type="text" name="location" value="<?php echo $row['location']; ?>" style="display:none">

                <?php if (($row2['noOfStudent'])>=1){?>
                    <input type="submit" name="showStudentList" value="Show Student List">
                <?php } else {echo "No student available to show";}?>
                </td>
            </form>
    </tr>
        <?php
        }
        ?>
</table><br>

<form method='POST' action='tutUI.php'>
    <input type="submit" value="Back to Tutor UI">
</form>