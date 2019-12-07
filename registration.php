<head>
        <title>Registration</title>
        <link rel="stylesheet" href="css/form.css">
        <link rel="stylesheet" href="css/outStyle.css">
</head>


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

<?php if ($successRegistration == false) { ?>
    
    <h2>Registration Form</h2>
    <div class="container">
            <form action='registration.php' method='POST'>
                <div class="col-25">
                    <label>User Type:</label>
                </div>
                <div class="col-75">
                    <select name='userType' required>
                        <option <?php if ($userType == "student") echo 'selected="selected"'; ?>value='student'>Student</option>
                        <option <?php if ($userType == "tutor") echo 'selected="selected"'; ?>value='tutor'>Tutor</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label>Name(as in NRIC): </label>
                    </div>
                    <div class="col-75">
                        <input type='text' name='userName' value='<?php echo $userName ?>' pattern="[A-Za-z /@]{3,50}" required maxlength="50"> (3-50 Characters, no special characters except / and @)
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
                        <br><input type='submit' value='Submit Form'>
                </div>

        </form>
       
    <form action='login.php' method='POST'>
    <div class="row"><input type='submit' value='Return to Login Page'></div>
    </form>
</div>
<?php } ?>
<?php
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
    }
?>
   <div class="prompt">Registration Successful. Your userID is 
   <span class="important"> <?php echo "$userID"; ?> </span>. <br><br> This userID will be used to login to your account. <br><br>
   You will be redirected to the login page in 10 seconds. </div>

<?php
    mysqli_close($dbc);
    header("Refresh:10;URL=login.php");
    die();
} else { ?>
    <div class="error"><?php echo"$out"?></div> 
<?php
}
?>