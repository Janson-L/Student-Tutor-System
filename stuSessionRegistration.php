<head>
    <title>USTS- Session Registration</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/outStyle.css">
</head>

<?php
SESSION_START();
if (preg_match("/\ASTU/", @$_SESSION['loginUser'])) {
    ?>

    <ul>
        <li class="active"><a href="stuUI.php">Home</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Tutor Session</a>
            <div class="dropdown-content">
                <a href="stuSessionRegistration.php">Register/Deregister Tutor Session</a>
                <a href="stuShowRegisteredSession.php">Show Registered Tutor Session</a>
            </div>
        </li>
        <li><a href="stuSystemUsageStatistics.php">System Usage Statistics</a></li>
        <li style="float:right"><a href="logOut.php">Log Out</a></li>
    </ul>

    <?php
        $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
        function format_time_output($t)
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

    <h2>Session Registration</h2> 
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
    <form method='POST' action='stuSessionRegistration.php'>
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
        <h2>10 Latest Added Session </h2>
    <?php } else if ($searchTable == 1) { ?>
        <h2>Search by SessionID</h2>
    <?php } else if ($searchTable == 2) { ?>
        <h2>Search by TutorID</h2>
    <?php } else if ($searchTable == 3) { ?>
        <h2>Search by Subject Code</h2>
    <?php } else if ($searchTable == 4) { ?>
        <h2>Search by Topic</h2>
    <?php } ?>


    <?php
        if ($searchTable == 0) {
            $query = "SELECT s.sessionID,s.topic,s.subjectCode,s.date,s.startTime,s.endTime,t.name,s.location FROM tutoringsession s, tutor t WHERE t.tutorID=s.tutorID ORDER BY s.ID DESC LIMIT 10;";
        } else if ($searchTable == 1) {
            $query = "SELECT s.sessionID,s.topic,s.subjectCode,s.date,s.startTime,s.endTime,t.name,s.location FROM tutoringsession s, tutor t WHERE t.tutorID=s.tutorID AND s.sessionID='$searchQuery' ORDER BY s.sessionID DESC;";
        } else if ($searchTable == 2) {
            $query = "SELECT s.sessionID,s.topic,s.subjectCode,s.date,s.startTime,s.endTime,t.name,s.location FROM tutoringsession s, tutor t WHERE t.tutorID=s.tutorID AND s.tutorID='$searchQuery' ORDER BY s.sessionID DESC;";
        } else if ($searchTable == 3) {
            $query = "SELECT s.sessionID,s.topic,s.subjectCode,s.date,s.startTime,s.endTime,t.name,s.location FROM tutoringsession s, tutor t WHERE t.tutorID=s.tutorID AND s.subjectCode='$searchQuery' ORDER BY s.sessionID DESC;";
        } else {
            $query = "SELECT s.sessionID,s.topic,s.subjectCode,s.date,s.startTime,s.endTime,t.name,s.location FROM tutoringsession s, tutor t WHERE t.tutorID=s.tutorID AND s.topic LIKE '%$searchQuery%' ORDER BY s.sessionID DESC;";
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
                <th>Registration Status</th>
            </tr>

        <?php
            } else {
                echo "No result is found. Please make sure you have entered the correct search term.";
            }


            while ($row = mysqli_fetch_assoc($result)) {
                $durationd = format_time_output(strtotime($row['endTime']) - strtotime($row['startTime']));
                $duration = date('His', (strtotime($row['endTime']) - strtotime($row['startTime'])));
                $expiredSession = false;
                ?>
            <tr>
                <form method='POST'>
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
                        <input type="text" name="duration" value="<?php echo $durationd; ?>" style="display:none">
                        <input type="text" name="tutorID" value="<?php echo $row['tutorID']; ?>" style="display:none">
                        <input type="text" name="location" value="<?php echo $row['location']; ?>" style="display:none">
                        <?php if (date('Y-m-d', strtotime($row['date'])) < $currentDate) {
                                    echo "Expired Tutor Session";
                                    $expiredSession = true;
                                } else {
                                    if ((date('Y-m-d', strtotime($row['date']))) == $currentDate) {
                                        if (date('His', strtotime($row['startTime'])) <= $currentTime && $currentTime - date('His', strtotime($row['startTime'])) <= $duration && $sessionRegistered == true) {
                                            echo "Session Started";
                                            $expiredSession = true;
                                        } else if (date('His', strtotime($row['startTime'])) <= $currentTime && $currentTime - date('His', strtotime($row['startTime'])) > $duration) {
                                            echo "Expired Tutor Session";
                                            $expiredSession = true;
                                        } else if (((date('His', strtotime($row['startTime']))) - $currentTime) <= "035900" && $sessionRegistered != true) {
                                            echo "Registration Closed";
                                            $expiredSession = true;
                                        }
                                    }
                                }
                                if ($sessionRegistered != true && $expiredSession != true) { ?>
                            <input type="submit" name="register" value="Register">
                        <?php } else if ($expiredSession != true && $sessionRegistered == true) { ?>
                            <input type="submit" name="deregister" value="Deregister">
                        <?php
                                }
                                ?>
                    </td>
                </form>
            </tr>
        <?php } ?>
        </table>

        </div>
        <br>

        <?php
            if (isset($_POST['register'])) {
                $query = "INSERT INTO session_student (sessionID,studentID) VALUES('{$_POST['sessionID']}','{$_SESSION['loginUser']}');";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                mysqli_close($dbc);
                echo '<meta http-equiv="refresh" content="0">';
                die();
            }
            ?>

        <?php
            if (isset($_POST['deregister'])) {
                $query = "DELETE FROM session_student WHERE sessionID='{$_POST['sessionID']}'AND studentID='{$_SESSION['loginUser']}';";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                mysqli_close($dbc);
                echo '<meta http-equiv="refresh" content="0">';
                die();
            }
            ?>

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