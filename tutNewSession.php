<head>
    <title>USTS- Add New Tutor Session</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/outStyle.css">
</head>

<?php
SESSION_START();
//$_SESSION['loginUser']
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
        <li><a href="tutSystemUsageStatistics.php">System Usage Statistics</a></li>
        <li style="float:right"><a href="logOut.php">Log Out</a></li>
    </ul>

    <?php
        $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established"); //Register and change to a non root user
        $formFilledCorrectly = false;
        $topic = "";
        $subjectCode = "";
        $date = "";
        $startTime = "";
        $endTime = "";
        $sessionID = "";
        $currentDate = "";
        $currentTime = "";
        $location = "";
        $out = "";


        if (isset($_POST['topic'])) {
            $topic = $_POST['topic'];
        }

        if (isset($_POST['subjectCode'])) {
            $subjectCode = $_POST['subjectCode'];
        }

        if (isset($_POST['date'])) {
            $date = $_POST['date'];
        }

        if (isset($_POST['startTimeH'])) {
            $startTime .= $_POST['startTimeH'];
        }

        if (isset($_POST['startTimeM'])) {
            $startTime .= $_POST['startTimeM'];
            $startTime .= "00";
        }

        if (isset($_POST['endTimeH'])) {
            $endTime .= $_POST['endTimeH'];
        }

        if (isset($_POST['endTimeM'])) {
            $endTime .= $_POST['endTimeM'];
            $endTime .= "00";
        }

        if (isset($_POST['location'])) {
            $location = $_POST['location'];
        }

        if (isset($_POST['startTimeH']) && isset($_POST['startTimeM']) && isset($_POST['endTimeH']) && isset($_POST['endTimeM'])) {
            $formFilledCorrectly = true;
            $currentDate = date('Y-m-d', time());
            $currentTime = date('His', time());
            $currentTime += "070000"; //to convert it to Malaysia Time

            if ($date < $currentDate) //to check the selected date with current date
            {
                $formFilledCorrectly = false;
                $out .= "Date selected is not a valid date. Please select a date that is either today or later than current date. <br> ";
            } else {
                if ($date == $currentDate) {
                    if ($startTime <= $currentTime) {
                        $formFilledCorrectly = false;
                        $out .= "Time selected is not a valid time. Please select time that is later than current time. <br> ";
                    } else if (($startTime - $currentTime) <= "055900") {
                        $formFilledCorrectly = false;
                        $out .= "Tutors are not allowed to add tutor session that starts in less than 6 hours from the current time. <br> ";
                    }
                }

                if ($startTime >= $endTime) {
                    $formFilledCorrectly = false;
                    $out .= "Please select the correct end time. Duration of the session must be at least 5 minutes. <br> ";
                }
            }
        }

        if ($formFilledCorrectly == true) {
            $query = "INSERT INTO tutoringSession (topic,subjectCode,tutorID,date,startTime,endTime,location) VALUES('$topic','$subjectCode','{$_SESSION['loginUser']}','$date','$startTime','$endTime','$location');";
            $result = mysqli_query($dbc, $query) or die("Query Failed $query");

            $query = "SELECT ID FROM tutoringSession ORDER BY ID DESC LIMIT 1;";
            $result = mysqli_query($dbc, $query) or die("Query Failed");
            $IdDb = mysqli_fetch_assoc($result);
            $IdDb = $IdDb['ID'];
            $sessionID .= "SES";

            if ($IdDb == null) {
                $id = 1;
            } else {
                $id = $IdDb;
            }
            $sessionID .= $id;

            $query = "UPDATE tutoringSession SET sessionID='$sessionID' WHERE ID=$IdDb;";
            $result = mysqli_query($dbc, $query) or die("Query Failed $query");
            
            $successSessionID=$sessionID;
            $topic = "";
            $subjectCode = "";
            $date = "";
            $startTime = "";
            $endTime = "";
            $sessionID = "";
            $currentDate = "";
            $currentTime = "";
            $location = "";    
            
        }
        ?>
    <h2>Add New Session</h2>
    <div class="container">
        <form action='tutNewSession.php' method='POST'>
            <div class="row">
                <div class="col-25"><label>Topic: </label></div>
                <div class="col-75"><input type='text' name='topic' value='<?php echo $topic ?>' required maxlength="30">(Maximum 30 characters)</div>
            </div>
            <div class="row">
                <div class="col-25"><label>Subject Code (Optional): </label></div>
                <div class="col-75"><input type='text' name="subjectCode" value='<?php echo $subjectCode ?>' pattern="[A-Z]{4}[0-9]{4}" maxlength="8" placeholder="BITI1113"></div>
            </div>
            <div class="row">
                <div class="col-25"><label>Date: </label></div>
                <div class="col-75"><input type='date' name='date' value='<?php echo $date ?>' required></div>
            </div>
            <div class="row">
                <div class="col-25"><label>Start Time:</label></div>
                <div class="col-15"><select name='startTimeH' required>
                    <?php for ($i = 0; $i <= 9; $i++) echo "<option value=0" . $i . ">0" . $i . " </option>"; ?>
                    <?php for ($i = 10; $i <= 23; $i++) echo "<option value=" . $i . ">" . $i . " </option>"; ?>
                </select></div>
                <div class="col-15"><select name='startTimeM' required>
                    <?php for ($i = 0; $i <= 9; $i += 5) echo "<option value=0" . $i . ">0" . $i . " </option>"; ?>
                    <?php for ($i = 10; $i <= 59; $i += 5) echo "<option value=" . $i . ">" . $i . " </option>"; ?>
                </select></div>
            </div>
            <div class="row">
                <div class="col-25"><label>End Time: </label></div>
                <div class="col-15"><select name='endTimeH' required>
                    <?php for ($i = 0; $i <= 9; $i++) echo "<option value=0" . $i . ">0" . $i . " </option>"; ?>
                    <?php for ($i = 10; $i <= 23; $i++) echo "<option value=" . $i . ">" . $i . " </option>"; ?>
                </select></div>
                <div class="col-15"><select name='endTimeM' required>
                    <?php for ($i = 0; $i <= 9; $i += 5) echo "<option value=0" . $i . ">0" . $i . " </option>"; ?>
                    <?php for ($i = 10; $i <= 59; $i += 5) echo "<option value=" . $i . ">" . $i . " </option>"; ?>
                </select></div>
            </div>
            <div class="row">
                <div class="col-25"><label>Location:</label></div>
                <div class="col-75"><input type='text' name='location' value='<?php echo $location ?>' required maxlength="20">(Maximum 20 characters)</div>
            </div>
            <br>
            <div class="row" style="float:right;">
            <input type='submit' name="addNewTutoringSession" value='Add new tutoring session'>
    </div>
        </form><br>

    </div>
        
    <?php
       if ($formFilledCorrectly == true) {
        ?>
 <p>Tutoring session successfully added. SessionID for this tutoring session is <span class="important"><?php echo "$successSessionID"; ?></span>.</p> 
       <?php
    } else{
        ?>
        <div class="error"><?php echo"$out"; ?></div>
    <?php
    }
    ?>
        Note: <br> 
        1. Tutors can only create new tutoring session at least 6 hours before starting time.

<?php } else {
    ?>
    <br>
    <div class="prompt">You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</div>
<?php
    header("Refresh:5;URL=logOut.php");
    die();
}
?>