<?php
SESSION_START();
$_SESSION['loginUser'] = "STU1";
$_SESSION['userClass'] = "STU";
$out = "";
$out .= $_SESSION['loginUser'];
$out .= $_SESSION['userClass'];
echo "<h6> $out<h6>"
;
$dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
$studentID = $_SESSION['loginUser'];
?>

<?php
function format_time($t) // t = seconds, f = separator 
{
    return sprintf("%02d%s%02d", floor($t / 3600), ':', ($t / 60) % 60);
}

$searchType = "";
$searchQuery = "";
$searchTable = 0;

if (isset($_GET['searchType'])) {
    $searchType = $_GET['searchType'];
}

if (isset($_GET['searchQuery'])) {
    $searchQuery = $_GET['searchQuery'];
}
?>

<form method='POST'>
    <label>Search Type</label>
    <select name='searchType' required>
        <option <?php if ($searchType == "sessionSearch") echo 'selected="selected"'; ?>value='sessionSearch'>Search by SessionID</option>
        <option <?php if ($searchType == "tutorIDSearch") echo 'selected="selected"'; ?>value='tutorIDSearch'>Search by TutorID</option>
        <option <?php if ($searchType == "subjectCodeSearch") echo 'selected="selected"'; ?>value='subjectCodeSearch'>Search by Subject Code </option>
        <option <?php if ($searchType == "topicSearch") echo 'selected="selected"'; ?>value='topicSearch'>Search by Topic</option>
    </select>

    <input type='text' name='searchQuery' value='<?php echo $searchQuery ?>' required pattern="[A-Za-z0-9 ]{1,30}" placeholder="(Maximum 30 characters)" maxlength="30"><br>
    <input type='submit' name='search' value='Search'><br>
</form>

<?php
if (isset($_POST['search'])) {
    if ($_POST['searchType'] == "sessionSearch") {
        $searchTable = 1;
        $searchQuery=$_POST['searchQuery'];
    } else if ($_POST['searchType'] == "tutorIDSearch") {
        $searchTable = 2;
        $searchQuery=$_POST['searchQuery'];
    } else if ($_POST['searchType'] == "subjectCodeSearch") {
        $searchTable = 3;
        $searchQuery=$_POST['searchQuery'];
    } else if ($_POST['searchType'] == "topicSearch") {
        $searchTable = 4;
        $searchQuery=$_POST['searchQuery'];
    }
}
?>

<?php if ($searchTable == 0) { ?>
    <h3>10 Latest Added Session </h3>
    <table border='1'>
        <tr>
            <th>Session ID</th>
            <th>Topic</th>
            <th>Subject Code</th>
            <th>Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Duration (Hour:Minute)</th>
            <th>Location</th>
            <th>Register</th>
        </tr>
        <?php
            $query = "SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession ORDER BY sessionID DESC LIMIT 10;";
            $result = mysqli_query($dbc, $query) or die("Query Failed $query");
            while ($row = mysqli_fetch_assoc($result)) {
                $duration = format_time(strtotime($row['endTime']) - strtotime($row['startTime']));
                ?>
            <tr>
                <form method='POST' action='stuSessionSignUp.php'>
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
                                $sessionRegistered = false;
                                $query2 = "SELECT studentID FROM session_student WHERE sessionID='{$row['sessionID']}';";
                                $result2 = mysqli_query($dbc, $query2) or die("Query Failed $query2");
                                $stuID = mysqli_fetch_assoc($result2);
                                if ($stuID['studentID'] === $_SESSION['loginUser']) {
                                    $sessionRegistered = true;
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
                        <?php if ($sessionRegistered != true) { ?>
                            <input type="submit" name="register" value="Register">
                        <?php } else {
                                    echo "Registered";
                                }
                                ?>
                    </td>
                </form>
            </tr>
        <?php } ?>
    </table>
<?php } ?>

<?php if ($searchTable == 1) { ?>
    <h3>Search by SessionID</h3>
    <table border='1'>
        <tr>
            <th>Session ID</th>
            <th>Topic</th>
            <th>Subject Code</th>
            <th>Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Duration (Hour:Minute)</th>
            <th>Location</th>
            <th>Register</th>
        </tr>
        <?php
            $query = "SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession WHERE sessionID='$searchQuery';";
            $result = mysqli_query($dbc, $query) or die("Query Failed $query");
            while ($row = mysqli_fetch_assoc($result)) {
                $duration = format_time(strtotime($row['endTime']) - strtotime($row['startTime']));
                ?>
            <tr>
                <form method='POST' action='stuSessionSignUp.php'>
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
                                $sessionRegistered = false;
                                $query2 = "SELECT studentID FROM session_student WHERE sessionID='{$row['sessionID']}';";
                                $result2 = mysqli_query($dbc, $query2) or die("Query Failed $query2");
                                $stuID = mysqli_fetch_assoc($result2);
                                if ($stuID['studentID'] === $_SESSION['loginUser']) {
                                    $sessionRegistered = true;
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
                        <?php if ($sessionRegistered != true) { ?>
                            <input type="submit" name="register" value="Register">
                        <?php } else {
                                    echo "Registered";
                                }
                                ?>
                    </td>
                </form>
            </tr>
        <?php } ?>
    </table>
<?php } ?>

<?php
if (isset($_POST['register'])) {
    $query = "INSERT INTO session_student (sessionID,studentID) VALUES('{$_POST['sessionID']}','{$_SESSION['loginUser']}');";
    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
    echo '<meta http-equiv="refresh" content="0">';
    die();
}
?>