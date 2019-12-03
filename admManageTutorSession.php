<?php
SESSION_START();
if (preg_match("/\AADM/", @$_SESSION['loginUser'])) {
    ?>

    <head>
        <title>USTS-Manage Tutor Session</title>
        <link rel="stylesheet" href="css/navbar.css">
        <link rel="stylesheet" href="css/form.css">
        <link rel="stylesheet" href="css/table.css">
    </head>
    <ul>
        <li><a href="admUI.php">Home</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Manage Users</a>
            <div class="dropdown-content">
                <a href="admAddUser.php">Add User</a>
                <a href="admManageUsers.php">Update and Delete Users</a>
            </div>
        </li>
        <li class="active"><a href="admManageTutorSession.php">Manage Tutor Session</a></li>
        <li><a href="admSystemUsageStatistics.php">System Usage Statistics</a></li>
        <li style="float:right"><a href="logOut.php">Log Out</a></li>
    </ul>


    <?php
        $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
        ?>
    <?php
        $searchType = "";
        $searchQuery = "";
        $searchTable = 0;

        if (isset($_POST['searchType'])) {
            $searchType = $_POST['searchType'];
        }

        if (isset($_POST['searchQuery'])) {
            $searchQuery = $_POST['searchQuery'];
        }
        function format_time_output($t)
        {
            return sprintf("%02d%s%02d", floor($t / 3600), ':', ($t / 60) % 60);
        }
        ?>
    <h2>Manage Tutor Session</h2>
    <div class="container">
        <form method='POST'>
        <div class="row">
            <div class="col-15"><label>Search Type: </label></div>
            <div class="col-15"><select name='searchType' required>
                <option <?php if ($searchType == "topicSearch") echo 'selected="selected"'; ?>value='topicSearch'>Search by Topic</option>
                <option <?php if ($searchType == "subjectCodeSearch") echo 'selected="selected"'; ?>value='subjectCodeSearch'>Search by Subject Code</option>
                <option <?php if ($searchType == "sessionIDSearch") echo 'selected="selected"'; ?>value='sessionIDSearch'>Search by sessionID</option>
                <option <?php if ($searchType == "tutorIDSearch") echo 'selected="selected"'; ?>value='tutorIDSearch'>Search by tutorID</option>
            </select>
            </div>

            <div class="col-25"> <input type='text' name='searchQuery' value='<?php echo $searchQuery ?>' pattern="[A-Za-z0-9 ]{0,30}" placeholder="(Maximum 30 characters)" maxlength="30"></div>
            <div class="col-5"><input type='submit' name='search' value='Search'></div>
        
        </form>
        <form method='POST' action='admManageTutorSession.php'>
        <div class="col-5"><input type='submit' value='Refresh'></div>
        </form>
        </div>
        <?php
            if (isset($_POST['search'])) {
                if ($_POST['searchType'] == "sessionIDSearch") {
                    $searchTable = 1;
                } else if ($_POST['searchType'] == "topicSearch") {
                    $searchTable = 2;
                } else if ($_POST['searchType'] == "tutorIDSearch") {
                    $searchTable = 3;
                }else if ($_POST['searchType'] == "subjectCodeSearch") {
                    $searchTable = 4;
                }
            }
            ?>

        <?php
            if ($searchTable == 0) { ?>
            <h2>Top 10 Recently Added Session</h2>
        <?php } else if ($searchTable == 1) { ?>
            <h2>Search by Session ID</h2>
        <?php } else if ($searchTable == 2) { ?>
            <h2>Search by Topic</h2>
        <?php } else if ($searchTable == 3) { ?>
            <h2>Search by tutorID</h2>
        <?php } else if ($searchTable == 4) { ?>
            <h2>Search by Subject Code</h2>
        <?php } ?>
        <?php
            if ($searchTable == 0) {
                $query = "SELECT s.sessionID,s.topic,s.subjectCode,s.date,s.startTime,s.endTime,t.name,s.location FROM tutoringsession s, tutor t WHERE t.tutorID=s.tutorID ORDER BY s.ID DESC LIMIT 10;";
            }
            if ($searchTable == 1) {
                $query = "SELECT s.sessionID,s.topic,s.subjectCode,s.date,s.startTime,s.endTime,t.name,s.location FROM tutoringsession s, tutor t WHERE t.tutorID=s.tutorID AND s.sessionID='$searchQuery' ORDER BY s.ID DESC;";
            } else if ($searchTable == 2) {
                $query = "SELECT s.sessionID,s.topic,s.subjectCode,s.date,s.startTime,s.endTime,t.name,s.location FROM tutoringsession s, tutor t WHERE t.tutorID=s.tutorID AND s.topic LIKE '%$searchQuery%' ORDER BY s.ID DESC;";
            } else if ($searchTable == 3) {
                $query = "SELECT s.sessionID,s.topic,s.subjectCode,s.date,s.startTime,s.endTime,t.name,s.location FROM tutoringsession s, tutor t WHERE t.tutorID=s.tutorID AND s.tutorID='$searchQuery' ORDER BY s.ID DESC;";
            } else if ($searchTable == 4) {
                $query = "SELECT s.sessionID,s.topic,s.subjectCode,s.date,s.startTime,s.endTime,t.name,s.location FROM tutoringsession s, tutor t WHERE t.tutorID=s.tutorID AND s.subjectCode='$searchQuery' ORDER BY s.ID DESC;";
            }

            $result = mysqli_query($dbc, $query) or die("Query Failed $query");
            if (mysqli_num_rows($result) > 0) {
                $currentDate = date('Y-m-d', time());
                $currentTime = date('His', time());
                $currentTime += "070000";
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
                    <th>Delete Session</th>
                </tr>
            <?php
                } else {
                    echo "No result is found. Please make sure you have entered the correct search term.";
                }

                while ($row = mysqli_fetch_assoc($result)) {
                    $durationd = format_time_output(strtotime($row['endTime']) - strtotime($row['startTime']));
                    $duration = date('His', (strtotime($row['endTime']) - strtotime($row['startTime'])));
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
                        <form method='POST' action='admDeleteTutorSession.php'>
                            <input type="text" name="sessionID" value="<?php echo $row['sessionID']; ?>" style="display:none">
                            <input type="text" name="topic" value="<?php echo $row['topic']; ?>" style="display:none">
                            <input type="text" name="subjectCode" value="<?php echo $row['subjectCode']; ?>" style="display:none">
                            <input type="text" name="date" value="<?php echo $row['date']; ?>" style="display:none">
                            <input type="text" name="startTime" value="<?php echo $row['startTime']; ?>" style="display:none">
                            <input type="text" name="endTime" value="<?php echo $row['endTime']; ?>" style="display:none">
                            <input type="text" name="durationd" value="<?php echo "$durationd"; ?>" style="display:none">
                            <input type="text" name="tutorName" value="<?php echo $row['name']; ?>" style="display:none">
                            <input type="text" name="location" value="<?php echo $row['location']; ?>" style="display:none">

                            <input type="submit" name="deleteSession" value="Delete">
                        </form>
                    </td>

                </tr>
            <?php }
                ?>
            </table> </div>

    </div>
    <?php
        mysqli_close($dbc);
        ?>
<?php
} else {
    echo "<h3>You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</h3>";
    header("Refresh:5;URL=logOut.php");
    die();
}
?>