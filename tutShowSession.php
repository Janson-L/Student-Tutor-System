<?php
SESSION_START();
//$_SESSION['loginUser'];

if (preg_match("/\ATUT/", @$_SESSION['loginUser'])) {
?>

<?php
$dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
$out = "";
$searchTable=0;
$searchQuery="";
$searchType="";


if (isset($_POST['searchType'])) {
    $searchType = $_POST['searchType'];
}

if (isset($_POST['searchQuery'])) {
    $searchQuery = $_POST['searchQuery'];
}

function format_time_output($t) // t = seconds, f = separator 
{
    return sprintf("%02d%s%02d", floor($t / 3600), ':', ($t / 60) % 60);
}
?>

<h3>Show Tutor Session</h3>
<form method='POST'>
        <label>Search Type</label>
        <select name='searchType' required>
            <option <?php if ($searchType == "topicSearch") echo 'selected="selected"'; ?>value='topicSearch'>Search by Topic</option>
            <option <?php if ($searchType == "sessionIDSearch") echo 'selected="selected"'; ?>value='sessionIDSearch'>Search by SessionID</option>
            <option <?php if ($searchType == "subjectCodeSearch") echo 'selected="selected"'; ?>value='subjectCodeSearch'>Search by Subject Code </option>
        </select>

        <input type='text' name='searchQuery' value='<?php echo $searchQuery ?>' pattern="[A-Za-z0-9 ]{0,30}" placeholder="(Maximum 30 characters)" maxlength="30">
        <input type='submit' name='search' value='Search'>

        <form method='POST' action='tutShowSession.php'>
            <input type="submit" value="Refresh">
        </form>
        <form method='POST' action='tutUI.php'>
            <input type="submit" value="Back to Tutor UI">
        </form>

        <?php
        if (isset($_POST['search'])) {
            if ($_POST['searchType'] == "sessionIDSearch") {
                $searchTable = 1;
                $searchQuery = $_POST['searchQuery'];
            } 
            else if ($_POST['searchType'] == "subjectCodeSearch") {
                $searchTable = 2;
                $searchQuery = $_POST['searchQuery'];
            } else if ($_POST['searchType'] == "topicSearch") {
                $searchTable = 3;
                $searchQuery = $_POST['searchQuery'];
            }
        }
        ?>
    <?php if ($searchTable == 0) { ?>
        <h3>Show all Session </h3>
    <?php } else if ($searchTable == 1) { ?>
        <h3>Search by SessionID</h3>
    <?php } else if ($searchTable == 2) { ?>
        <h3>Search by Subject Code</h3>
    <?php } else if ($searchTable == 3) { ?>
        <h3>Search by topic</h3>
    <?php } ?>

    <?php
$query = "";

if ($searchTable == 0) {
    $query = "SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession WHERE tutorid='{$_SESSION['loginUser']}'ORDER BY sessionID DESC;";
} else if ($searchTable == 1) {
    $query = "SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession WHERE tutorid='{$_SESSION['loginUser']}' AND sessionID='$searchQuery' ORDER BY sessionID DESC;";
} else if ($searchTable == 2) {
    $query = "SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession WHERE tutorid='{$_SESSION['loginUser']}' AND subjectCode='$searchQuery' ORDER BY sessionID DESC;";
} else if ($searchTable == 3) {
    $query = "SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession WHERE tutorid='{$_SESSION['loginUser']}' AND topic LIKE'%$searchQuery%' ORDER BY sessionID DESC;";
}
$result = mysqli_query($dbc, $query) or die("Query Failed $query");

if(mysqli_num_rows($result)>0){
?>
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

    
        <?php
        }
        else
        {
            echo"No result is found. Please make sure you have entered the correct search term.";
        }
        while ($row = mysqli_fetch_assoc($result)) {
            $durationd = format_time_output(strtotime($row['endTime']) - strtotime($row['startTime'])); 
            
            $query2="SELECT COUNT(studentID) AS noOfStudent FROM session_student WHERE sessionID='{$row['sessionID']}';";
            $result2=mysqli_query($dbc,$query2) or die("Query Failed $query2");
            $row2=mysqli_fetch_assoc($result2);
            ?>

        <tr>
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

<?php
} else {
    echo "<h3>You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</h3>";
    header("Refresh:5;URL=logOut.php");
    die();
}
?>