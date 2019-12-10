<head>
    <title>USTS- Show Session</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/outStyle.css">
    <link rel="stylesheet" href="css/table.css">
</head>

<?php
SESSION_START();
//$_SESSION['loginUser'];

if (preg_match("/\ATUT/", @$_SESSION['loginUser'])) {
    ?>
    <ul>
        <li><a href="tutUI.php">Home</a></li>
        <li class="dropdown active">
            <a href="javascript:void(0)" class="dropbtn">Manage Tutor Session</a>
            <div class="dropdown-content">
                <a href="tutNewSession.php">Add New Tutor Session</a>
                <a href="tutShowSession.php">Show Tutor Session</a>
            </div>
        </li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Manage Personal Information</a>
            <div class="dropdown-content">
                <a href="editPersonalInfo.php">Edit Personal Information</a>
                <a href="resetPersonalPassword.php">Reset Password</a>
            </div>
        </li>
        <li><a href="tutSystemUsageStatistics.php">System Usage Statistics</a></li>
        <li style="float:right"><a href="logOut.php">Log Out</a></li>
    </ul>
    <?php
        $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
        $out = "";
        $searchTable = 0;
        $searchQuery = "";
        $searchType = "";


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

    <h2>Show Tutor Session</h2>
    <form method='POST'>
        <div class="container">
            <div class="row">
                <div class="col-15"><label>Search Type:</label></div>
                <div class="col-15"><select name='searchType' required>
                    <option <?php if ($searchType == "topicSearch") echo 'selected="selected"'; ?>value='topicSearch'>Search by Topic</option>
                    <option <?php if ($searchType == "sessionIDSearch") echo 'selected="selected"'; ?>value='sessionIDSearch'>Search by SessionID</option>
                    <option <?php if ($searchType == "subjectCodeSearch") echo 'selected="selected"'; ?>value='subjectCodeSearch'>Search by Subject Code </option>
                </select></div>

                <div class="col-25"><input type='text' name='searchQuery' value='<?php echo $searchQuery ?>' pattern="^[A-Za-z0-9 ]{1,30}$" maxlength="30">(Maximum 30 alphanumeric characters only)</div>
                <div class="col-5"><input type='submit' name='search' value='Search'></div>

                <form method='POST' action='tutShowSession.php'>
                <div class="col-5"><input type="submit" value="Refresh"></div>
                </form>
            </div>

            <?php
                if (isset($_POST['search'])) {
                    if ($_POST['searchType'] == "sessionIDSearch") {
                        $searchTable = 1;
                        $searchQuery = $_POST['searchQuery'];
                    } else if ($_POST['searchType'] == "subjectCodeSearch") {
                        $searchTable = 2;
                        $searchQuery = $_POST['searchQuery'];
                    } else if ($_POST['searchType'] == "topicSearch") {
                        $searchTable = 3;
                        $searchQuery = $_POST['searchQuery'];
                    }
                }
                ?>
            <?php if ($searchTable == 0) { ?>
                <h2>Show all Session </h2>
            <?php } else if ($searchTable == 1) { ?>
                <h2>Search by SessionID</h2>
            <?php } else if ($searchTable == 2) { ?>
                <h2>Search by Subject Code</h2>
            <?php } else if ($searchTable == 3) { ?>
                <h2>Search by topic</h2>
            <?php } ?>

            <?php
                $query = "";

                if ($searchTable == 0) {
                    $query = "SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession WHERE tutorid='{$_SESSION['loginUser']}'ORDER BY ID DESC;";
                } else if ($searchTable == 1) {
                    $query = "SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession WHERE tutorid='{$_SESSION['loginUser']}' AND sessionID='$searchQuery' ORDER BY ID DESC;";
                } else if ($searchTable == 2) {
                    $query = "SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession WHERE tutorid='{$_SESSION['loginUser']}' AND subjectCode='$searchQuery' ORDER BY ID DESC;";
                } else if ($searchTable == 3) {
                    $query = "SELECT sessionID,topic,subjectCode,date,startTime,endTime,location FROM tutoringsession WHERE tutorid='{$_SESSION['loginUser']}' AND topic LIKE'%$searchQuery%' ORDER BY ID DESC;";
                }
                $result = mysqli_query($dbc, $query) or die("Query Failed");

                if (mysqli_num_rows($result) > 0) {
                    ?>
                <table>
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
                    } else {
                        echo "No result is found. Please make sure you have entered the correct search term.";
                    }
                    while ($row = mysqli_fetch_assoc($result)) {
                        $durationd = format_time_output(strtotime($row['endTime']) - strtotime($row['startTime']));

                        $query2 = "SELECT COUNT(studentID) AS noOfStudent FROM session_student WHERE sessionID='{$row['sessionID']}';";
                        $result2 = mysqli_query($dbc, $query2) or die("Query Failed $query2");
                        $row2 = mysqli_fetch_assoc($result2);
                        ?>

                    <tr>
                        <form method='POST' action='tutShowStudents.php'>
                            <td><?php echo "{$row['sessionID']}"; ?></td>
                            <td><?php echo "{$row['topic']}"; ?></td>
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

                                <?php if (($row2['noOfStudent']) >= 1) { ?>
                                    <input type="submit" name="showStudentList" value="Show Student List">
                                <?php } else {
                                            echo "No student available to show";
                                        } ?>
                            </td>
                        </form>
                    </tr>
                <?php
                    }
                    ?>
                </table><br>
        </div>

    <?php
    } else {
        echo "<h3>You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</h3>";
        header("Refresh:5;URL=logOut.php");
        die();
    }
    ?>