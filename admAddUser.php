<head>
        <title>USTS-Add User</title>
        <link rel="stylesheet" href="css/navbar.css">
        <link rel="stylesheet" href="css/form.css">
        <link rel="stylesheet" href="css/outStyle.css">
</head>
<?php
SESSION_START();
if (preg_match("/\AADM/", @$_SESSION['loginUser'])) {

    ?>
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
        <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Manage Personal Information</a>
                <div class="dropdown-content">
                    <a href="editPersonalInfo.php">Edit Personal Information</a>
                    <a href="resetPersonalPassword.php">Reset Password</a>
                </div>
            </li>
        <li><a href="admSystemUsageStatistics.php">System Usage Statistics</a></li>
        <li style="float:right"><a href="logOut.php">Log Out</a></li>
    </ul>


    <?php
        $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
        $out = "";
$userType = "";
$userName = "";
$matrixNo = "";
$phoneNo = "";
$userID = "";
$successRegistration = false;
$formCheckUserName=false;
$formCheckMatrixNo=false;
$formCheckPhoneNo=false;
$formCheckPassword=false;


if (isset($_POST['userType'])) {
    $userType = $_POST['userType'];
}

if (isset($_POST['userName'])) {
    $userName = ucwords($_POST['userName']);
    if(preg_match("/^[A-Za-z \/@]{3,50}+$/", $userName)){
        $formCheckUserName=true;
    }
    else{
        $out .= "<br>Name must between 3-50 characters and can only contain alphabets, / and @";
    }
}

if (isset($_POST['matrixNo'])) {
    $matrixNo = $_POST['matrixNo'];
    if(preg_match("/[A-Z]{1}[0-9]{9}/", $matrixNo))
    {
        $formCheckMatrixNo=true;
    }
    else{
        $out .= "<br>First Character of Matrix Number must be capital letter and no space in between.";
        $formCheckMatrixNo = false;
    }
}

if (isset($_POST['phoneNo'])) {
    $phoneNo = $_POST['phoneNo'];
    if(preg_match("/[0-9]{10,15}/", $phoneNo)){
        $formCheckPhoneNo=true;
    }
    else{
        $out .= "<br>Phone number must be numbers only and between 10-15 numbers.";
        $formCheckPhoneNo = false;
    }
}

if (isset($_POST['pass']) && isset($_POST['passRetype'])) {
    if ($_POST['pass'] != $_POST['passRetype']) {
        $out .= "<br>Incorrect Password. Please make sure both password is the same.";
        $formCheckPassword = false;
    } else {
        $pass = $_POST['pass'];
        $formCheckPassword = true;
    }

    if($formCheckMatrixNo&&$formCheckPassword&&$formCheckPhoneNo&&$formCheckUserName){
        $successRegistration=true;
    }
        }
        ?>
<?php if ($successRegistration == false) {?>
    <h2>Registration Form</h2>
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
                        <input type='text' name='userName' value='<?php echo $userName ?>' required maxlength="50"> (3-50 Characters, no special characters except / and @)
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label>Matrix No: </label>
                    </div>
                    <div class="col-75">
                        <input type='text' name='matrixNo' value='<?php echo $matrixNo ?>' placeholder="B123456789" required maxlength="10">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label>Mobile Phone No: </label>
                    </div>
                    <div class="col-75">
                        <input type='text' name='phoneNo' value='<?php echo $phoneNo ?>' placeholder="0123456789" required maxlength="15"> (10-15 numbers)
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label>Password: </label>
                    </div>
                    <div class="col-75">
                        <input type='password' name='pass' required> (Maximum 12 Characters)
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
                <div class="row" style="float:right;">
                        <br><input type='submit' value='Register'>
                </div>
            <br><br>
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
            ?>
            <div class=prompt> Registration Successful! <br><br>
            
            <span class="superImportant">userID:<span class="important"> <?php echo "$userID"; ?> </span> </span>.
            
            <br><br>You will be redirected in 10 seconds.</div>
            <?php
            header("Refresh:10;URL=admAddUser.php");
        } else {
            mysqli_close($dbc);
        ?>
            
            <div class="error"><?php echo "$out";?></div>
        <?php
        }
        ?>
<?php } 
else { 
    ?>
    <br>
    <div class="prompt">You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</div>
    <?php
    header("Refresh:5;URL=logOut.php");
    die();
}
?>