<head>
    <title>USTS- Show Registered Tutoring Session</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/outStyle.css">
</head>

<?php
SESSION_START();
//$_SESSION['loginUser'];
if(preg_match("/\ASTU/",@$_SESSION['loginUser'])){
?>
 <ul>
        <li><a href="stuUI.php">Home</a></li>
        <li class="dropdown active">
            <a href="javascript:void(0)" class="dropbtn">Tutoring Session</a>
            <div class="dropdown-content">
                <a href="stuSessionRegistration.php">Register/Deregister Tutoring Session</a>
                <a href="stuShowRegisteredSession.php">Show Registered Tutoring Session</a>
            </div>
        </li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Manage Personal Information</a>
            <div class="dropdown-content">
                <a href="editPersonalInfo.php">Edit Personal Information</a>
                <a href="resetPersonalPassword.php">Reset Password</a>
            </div>
        </li>
        <li><a href="stuSystemUsageStatistics.php">System Usage Statistics</a></li>
        <li style="float:right"><a href="logOut.php">Log Out</a></li>
</ul>

<?php
$dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
function format_time_output($t) // t = seconds, f = separator 
{
    return sprintf("%02d%s%02d", floor($t / 3600), ':', ($t / 60) % 60);
}

$searchType = "";
$searchQuery = "";
$searchTable = 0;

if (isset($_POST['searchType'])) {
    $searchType = $_POST['searchType'];
}

if (isset($_POST['searchQuery'])) {
    $searchQuery = $_POST['searchQuery'];
}
?>
<h2>Show Registered Tutoring Sessions</h2> <br>
<div class="container">
<form method='POST'>
<div class="row">
        <div class="col-15"><label>Search Type:</label></div>
        <div class="col-15"><select name='searchType' required>
            <option <?php if ($searchType == "topicSearch") echo 'selected="selected"'; ?>value='topicSearch'>Search by Topic</option>
            <option <?php if ($searchType == "sessionSearch") echo 'selected="selected"'; ?>value='sessionSearch'>Search by SessionID</option>
            <option <?php if ($searchType == "tutorIDSearch") echo 'selected="selected"'; ?>value='tutorIDSearch'>Search by TutorID</option>
            <option <?php if ($searchType == "subjectCodeSearch") echo 'selected="selected"'; ?>value='subjectCodeSearch'>Search by Subject Code </option>
        </select></div>

        <div class="col-25"><input type='text' name='searchQuery' value='<?php echo $searchQuery ?>' pattern="[A-Za-z0-9 ]{0,30}" placeholder="(Maximum 30 characters)" maxlength="30"></div>
        <div class="col-5"><input type='submit' name='search' value='Search'></div>
    </form>
    <form method='POST' action='stuShowRegisteredSession.php'>
        <div class="col-5"><input type='submit' value='Refresh'></div>
    </form>
</div>
<?php
if (isset($_POST['search'])) {
    if ($_POST['searchType'] == "sessionSearch") {
        $searchTable = 1;
        $searchQuery = $_POST['searchQuery'];
    } else if ($_POST['searchType'] == "tutorIDSearch") {
        $searchTable = 2;
        $searchQuery = $_POST['searchQuery'];
    } else if ($_POST['searchType'] == "subjectCodeSearch") {
        $searchTable = 3;
        $searchQuery = $_POST['searchQuery'];
    } else if ($_POST['searchType'] == "topicSearch") {
        $searchTable = 4;
        $searchQuery = $_POST['searchQuery'];
    }
}
?>

<?php if ($searchTable == 0) { ?>
    <h3>All Registered Tutoring Sessions</h3>
<?php } else if ($searchTable == 1) { ?>
    <h3>Search by SessionID</h3>
<?php } else if ($searchTable == 2) { ?>
    <h3>Search by TutorID</h3>
<?php } else if ($searchTable == 3) { ?>
    <h3>Search by Subject Code</h3>
<?php } else if ($searchTable == 4) { ?>
    <h3>Search by Topic</h3>
<?php } ?>

    <?php
    if ($searchTable == 0) {
        $query = "SELECT t.sessionID,t.topic,t.subjectCode,t.date,t.startTime,t.endTime,b.name, t.location FROM tutor b,tutoringsession t, student s, session_student a WHERE b.tutorID=t.tutorID AND a.sessionID=t.sessionID AND a.studentID=s.studentID AND s.studentID='{$_SESSION['loginUser']}' ORDER BY t.ID DESC;";
    } else if ($searchTable == 1) {
        $query = "SELECT t.sessionID,t.topic,t.subjectCode,t.date,t.startTime,t.endTime,b.name, t.location FROM tutor b,tutoringsession t, student s, session_student a WHERE b.tutorID=t.tutorID AND a.sessionID=t.sessionID AND a.studentID=s.studentID AND s.studentID='{$_SESSION['loginUser']}' AND t.sessionID='$searchQuery' ORDER BY t.ID DESC;";
    } else if ($searchTable == 2) {
        $query = "SELECT t.sessionID,t.topic,t.subjectCode,t.date,t.startTime,t.endTime,b.name, t.location FROM tutor b,tutoringsession t, student s, session_student a WHERE b.tutorID=t.tutorID AND a.sessionID=t.sessionID AND a.studentID=s.studentID AND s.studentID='{$_SESSION['loginUser']}' AND t.tutorID='$searchQuery' ORDER BY t.ID DESC;";
    } else if ($searchTable == 3) {
        $query = "SELECT t.sessionID,t.topic,t.subjectCode,t.date,t.startTime,t.endTime,b.name, t.location FROM tutor b,tutoringsession t, student s, session_student a WHERE b.tutorID=t.tutorID AND a.sessionID=t.sessionID AND a.studentID=s.studentID AND s.studentID='{$_SESSION['loginUser']}' AND t.subjectCode='$searchQuery' ORDER BY t.ID DESC;";
    } else {
        $query = "SELECT t.sessionID,t.topic,t.subjectCode,t.date,t.startTime,t.endTime,b.name, t.location FROM tutor b,tutoringsession t, student s, session_student a WHERE b.tutorID=t.tutorID AND a.sessionID=t.sessionID AND a.studentID=s.studentID AND s.studentID='{$_SESSION['loginUser']}' AND t.topic LIKE '%$searchQuery%' ORDER BY t.ID DESC;";
    }
    $result = mysqli_query($dbc, $query) or die("Query Failed");
    if(mysqli_num_rows($result)>0){
    ?>
    <div style="overflow-x:auto;">
        <table>
            <tr>
                <th>Session ID</th>
                <th>Topic</th>
                <th>Subject Code</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Duration (Hour:Minute)</th>
                <th>Tutor Name</th>
                <th>Location</th>
                <th>Session Status</th>
            </tr>
    <?php
    }
    else
    {
        echo"No result is found. Please make sure you have entered the correct search term.";
    }
    $currentDate = date('Y-m-d', time());
    $currentTime = date('His', time());
    $currentTime += "070000";

    while ($row = mysqli_fetch_assoc($result)) {
        $durationd = format_time_output(strtotime($row['endTime']) - strtotime($row['startTime']));
        $duration=date('His',(strtotime($row['endTime']) - strtotime($row['startTime'])));
        $expiredSession = false;
        ?>
        <tr>
                <td><?php echo $row['sessionID']; ?></td>
                <td><?php echo $row['topic']; ?></td>
                <td><?php echo $row['subjectCode']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['startTime']; ?></td>
                <td><?php echo $row['endTime']; ?></td>
                <td><?php echo $durationd; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['location']; ?></td>
                <td>
                    <?php if (date('Y-m-d', strtotime($row['date'])) < $currentDate) {
                            echo "Tutor Session Ended";
                            $expiredSession = true;
                        } else {
                            if ((date('Y-m-d', strtotime($row['date']))) == $currentDate) {
                                if(date('His', strtotime($row['startTime']))<= $currentTime&& $currentTime-date('His',strtotime($row['startTime']))<=$duration){
                                    echo"Tutor Session Started";
                                    $expiredSession=true;
                                }
                                else if (date('His', strtotime($row['startTime'])) <= $currentTime &&$currentTime-date('His',strtotime($row['startTime']))>$duration) {
                                    echo "Tutor Session Ended";
                                    $expiredSession=true;
                                } 
                            }
                            else{
                                echo"Upcoming Tutor Session";
                            }
                        }
                        ?>
                </td>
            </form>
        </tr>
    <?php } ?>
</table><br>
</div>
</div>
<?php
} else { 
    ?>
    <br>
    <div class="prompt">You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</div>
    <?php
    header("Refresh:5;URL=logOut.php");
    die();
}
?>