<?php
SESSION_START();
//$_SESSION['loginUser'] = "STU1";
//$_SESSION['userClass'] = "STU";
$dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
//$_SESSION['loginUser'];
$index=1;
?>
<h3>Student List for topic: <?php echo " {$_POST['topic']}";?> (<?php echo "{$_POST['sessionID']}";?>) </h3><br>

<table border='1'>
    <tr>
        <th>No.</th>
        <th>Student ID</th>
        <th>Student Name</th>
    </tr>

    <tr>

    <?php
        $query="SELECT t.studentID,s.name FROM student s, session_student t WHERE s.studentID=t.studentID AND t.sessionID='{$_POST['sessionID']}';";
        $result=mysqli_query($dbc,$query)or die("Query Failed $query");
        while($row=mysqli_fetch_assoc($result))
        { ?>
            <td>
                <?php 
                    echo"$index";
                    $index++;
                ?>
            </td>
            <td><?php echo"{$row['studentID']}"; ?></td>
            <td><?php echo"{$row['name']}"; ?></td>
        </tr>
    <?php    
        }
    ?>
</table> <br>
<form method='POST' action='tutShowSession.php'>
    <input type="submit" value="Return to Show Session page">
</form>