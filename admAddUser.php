<?php
SESSION_START();
if (preg_match("/\AADM/", @$_SESSION['loginUser'])) {

    ?>

    <head>
        <title>USTS-Add User</title>
        <link rel="stylesheet" href="css/navbar.css">
        <link rel="stylesheet" href="css/form.css">
    </head>
    <ul>
        <li><a href="admUI.php">Home</a></li>
        <li class="dropdown active">
            <a href="javascript:void(0)" class="dropbtn">Manage Users</a>
            <div class="dropdown-content">
                <a href="admAddUser.php">Add User</a>
                <a href="admManageUsers.php">Update and Delete Users</a>
            </div>
        </li>
        <li><a href="admManageTutorSession.php">Manage Tutor Session</a></li>
        <li><a href="admSystemUsageStatistics.php">System Usage Statistics</a></li>
        <li style="float:right"><a href="logOut.php">Log Out</a></li>
    </ul>


    <?php
        $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
        $formCorrectCheck = true;
        $out = "";
        $userType = "";
        $userName = "";
        $matrixNo = "";
        $phoneNo = "";
        $userID = "";
        $successRegistration = false;

        if (isset($_POST['userType'])) {
            $userType = $_POST['userType'];
        }

        if (isset($_POST['userName'])) {
            $userName = $_POST['userName'];
        }

        if (isset($_POST['matrixNo'])) {
            $matrixNo = $_POST['matrixNo'];
        }

        if (isset($_POST['phoneNo'])) {
            $phoneNo = $_POST['phoneNo'];
        }

        if (isset($_POST['pass']) && isset($_POST['passRetype'])) {
            if ($_POST['pass'] != $_POST['passRetype']) {
                $formCorrectCheck = false;
                $out .= "Incorrect Password. Please make sure password is the same.";
            } else {
                $pass = $_POST['pass'];
                $successRegistration = true;
            }
        }
        ?>
    <?php if ($successRegistration != true) { ?>
        <h2>Add New User</h2>
        <div class="container">
            <form action='admAddUser.php' method='POST'>
                <div class="col-25">
                    <label>User Type:</label>
                </div>
                <div class="col-75">
                    <select name='userType' required>
                        <option <?php if ($userType == "student") echo 'selected="selected"'; ?>value='student'>Student</option>
                        <option <?php if ($userType == "tutor") echo 'selected="selected"'; ?>value='tutor'>Tutor</option>
                        <option <?php if ($userType == "admin") echo 'selected="selected"'; ?>value='admin'>Admin</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label>Name: </label>
                    </div>
                    <div class="col-75">
                        <input type='text' name='userName' value='<?php echo $userName ?>' pattern="[A-Za-z /@]{3,30}" required maxlength="30"> (3-30 Characters, no special characters except / and @)
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label>Matrix No: </label>
                    </div>
                    <div class="col-75">
                        <input type='text' name='matrixNo' value='<?php echo $matrixNo ?>' pattern="[A-Z]{1}[0-9]{9}" placeholder="B123456789" required maxlength="10">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label>Mobile Phone No: </label>
                    </div>
                    <div class="col-75">
                        <input type='text' name='phoneNo' value='<?php echo $phoneNo ?>' pattern="[0-9]{10,15}" placeholder="0123456789" required maxlength="15"> (10-15 numbers)
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label>Password: </label>
                    </div>
                    <div class="col-75">
                        <input type='password' name='pass' required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label>Retype Password: </label>
                    </div>
                    <div class="col-75">
                        <input type='password' name='passRetype' required>
                    </div>
                </div>
                <div class="row">
                        <br><input type='submit' value='Submit Form'>
                </div>

        </form>
        </div>

    <?php
        }
        if ($successRegistration == true) {
            if ($userType == "student") {
                $query = "INSERT INTO Student (Name,MatrixNo,PhoneNo,Password,LoginAttempt,AccountStatus) VALUES('$userName','$matrixNo','$phoneNo','$pass',0,1);";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");

                $query = "SELECT ID FROM Student ORDER BY ID DESC LIMIT 1;";
                $result = mysqli_query($dbc, $query) or die("Query Failed");
                $IdDb = mysqli_fetch_assoc($result);
                $IdDb = $IdDb['ID'];
                $userID .= "STU";

                if ($IdDb == null) {
                    $id = 1;
                } else {
                    $id = $IdDb;
                }
                $userID .= $id;

                $query = "UPDATE student SET studentID='$userID' WHERE ID=$IdDb;";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");
            } else if ($userType == "tutor") {
                $query = "INSERT INTO Tutor (Name,MatrixNo,PhoneNo,Password,LoginAttempt,AccountStatus) VALUES('$userName','$matrixNo','$phoneNo','$pass',0,1);";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");

                $query = "SELECT ID FROM Tutor ORDER BY ID DESC LIMIT 1;";
                $result = mysqli_query($dbc, $query) or die("Query Failed");
                $IdDb = mysqli_fetch_assoc($result);
                $IdDb = $IdDb['ID'];
                $userID .= "TUT";

                if ($IdDb == null) {
                    $id = 1;
                } else {
                    $id = $IdDb;
                }
                $userID .= $id;

                $query = "UPDATE tutor SET tutorID='$userID' WHERE ID=$IdDb;";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");
            } else if ($userType == "admin") {
                $query = "INSERT INTO admin (Name,MatrixNo,PhoneNo,Password,LoginAttempt,AccountStatus) VALUES('$userName','$matrixNo','$phoneNo','$pass',0,1);";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");

                $query = "SELECT ID FROM Admin ORDER BY ID DESC LIMIT 1;";
                $result = mysqli_query($dbc, $query) or die("Query Failed");
                $IdDb = mysqli_fetch_assoc($result);
                $IdDb = $IdDb['ID'];
                $userID .= "ADM";

                if ($IdDb == null) {
                    $id = 1;
                } else {
                    $id = $IdDb;
                }
                $userID .= $id;

                $query = "UPDATE admin SET adminID='$userID' WHERE ID=$IdDb;";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");
            }
            mysqli_close($dbc);
            echo "Registration Successful!";
            header("Refresh:2;URL=admAddUser.php");
        } else {
            mysqli_close($dbc);
            echo "<h5>$out</h5>";
        }
        ?>
<?php } else {
    echo "<h3>You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</h3>";
    header("Refresh:5;URL=logOut.php");
    die();
}

?>