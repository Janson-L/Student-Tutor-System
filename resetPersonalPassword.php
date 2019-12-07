<head>
    <title>USTS- Reset Password</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/outStyle.css">
</head>

<?php
SESSION_START();
if ((preg_match("/\AADM/", @$_SESSION['loginUser'])) ||(preg_match("/\ASTU/", @$_SESSION['loginUser']))||(preg_match("/\ATUT/", @$_SESSION['loginUser'])) ) {
    ?>
        <?php if (preg_match("/\AADM/", @$_SESSION['loginUser'])) { ?>
        <ul>
            <li><a href="admUI.php">Home</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Manage Users</a>
                <div class="dropdown-content">
                    <a href="admAddUser.php">Add User</a>
                    <a href="admManageUsers.php">Update and Delete Users</a>
                </div>
            </li>
            <li><a href="admManageTutorSession.php">Manage Tutor Session</a></li>
            <li class="dropdown active">
                <a href="javascript:void(0)" class="dropbtn">Manage Personal Information</a>
                <div class="dropdown-content">
                    <a href="editPersonalInfo.php">Edit Personal Information</a>
                    <a href="resetPersonalPassword.php">Reset Password</a>
                </div>
            </li>
            <li class></li>
            <li><a href="admSystemUsageStatistics.php">System Usage Statistics</a></li>
            <li style="float:right"><a href="logOut.php">Log Out</a></li>
        </ul>
        <?php } 
        else if (preg_match("/\ASTU/", @$_SESSION['loginUser'])) {?>
             <ul>
            <li><a href="stuUI.php">Home</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Tutor Session</a>
                <div class="dropdown-content">
                    <a href="stuSessionRegistration.php">Register/Deregister Tutor Session</a>
                    <a href="stuShowRegisteredSession.php">Show Registered Tutor Session</a>
                </div>
            </li>
            <li class="dropdown active">
                <a href="javascript:void(0)" class="dropbtn">Manage Personal Information</a>
                <div class="dropdown-content">
                    <a href="editPersonalInfo.php">Edit Personal Information</a>
                    <a href="resetPersonalPassword.php">Reset Password</a>
                </div>
            </li>
            <li><a href="stuSystemUsageStatistics.php">System Usage Statistics</a></li>
            <li style="float:right"><a href="logOut.php">Log Out</a></li>
        </ul>
        <?php } 
        else if (preg_match("/\ATUT/", @$_SESSION['loginUser'])){?>
            <ul>
            <li><a href="tutUI.php">Home</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Manage Tutor Session</a>
                <div class="dropdown-content">
                    <a href="tutNewSession.php">Add New Tutor Session</a>
                    <a href="tutShowSession.php">Show Tutor Session</a>
                </div>
            </li>
            <li class="dropdown active">
                <a href="javascript:void(0)" class="dropbtn">Manage Personal Information</a>
                <div class="dropdown-content">
                    <a href="editPersonalInfo.php">Edit Personal Information</a>
                    <a href="resetPersonalPassword.php">Reset Password</a>
                </div>
            </li>
            <li><a href="tutSystemUsageStatistics.php">System Usage Statistics</a></li>
            <li style="float:right"><a href="logOut.php">Log Out</a></li>
        </ul>
        <?php } ?>


    <?php
        $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
        $out = "";
        $validUpdate = false;
        if (isset($_POST['newPassword']) && isset($_POST['newPasswordRetype'])) {
            if ($_POST['newPassword'] === $_POST['newPasswordRetype']) {
                $validUpdate = true;
            } else {
                $out .= "Wrong Password. Please make sure password is keyed in correctly.";
            }
        }
        ?>
    <?php if ($validUpdate != true) { ?>
        <div class="container">
            <form method='POST'>
                <input type="text" name="userID" value=<?php echo "{$_SESSION['loginUser']}" ?> style="display:none">
                <div class="row">
                    <div class="col-25"><label>New Password:</label></div>
                    <div class="col-75"><input type="password" name="newPassword" required maxlength="12"></div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label>Retype New Password:</label></div>
                    <div class="col-75"><input type="password" name="newPasswordRetype" required maxlength="12"><br></div>
                </div>
                <input type="text" name="loginAttempt" value="0" style="display:none">
                <input type="text" name="accountStatus" value="1" style="display:none">
                <br>
                <div class="row">
                <div class="col-5" style="float:right;"><input type="submit" name="resetPasswordConfirm" value="Confirm"></div>

            </form>
            </div>

        </div>
    <?php } ?>

    <?php
        if ((isset($_POST['resetPasswordConfirm'])) && ($validUpdate == true)) {
            if (preg_match("/\ASTU/", $_POST['userID'])) {
                $query = "UPDATE student SET password='{$_POST['newPassword']}', loginAttempt='{$_POST['loginAttempt']}', accountStatus='{$_POST['accountStatus']}' WHERE studentID='{$_POST['userID']}';";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");
            } else if (preg_match("/\ATUT/", $_POST['userID'])) {
                $query = "UPDATE tutor SET password='{$_POST['newPassword']}', loginAttempt='{$_POST['loginAttempt']}', accountStatus='{$_POST['accountStatus']}' WHERE tutorID='{$_POST['userID']}';";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");
            } else if (preg_match("/\AADM/", $_POST['userID'])) {
                $query = "UPDATE admin SET password='{$_POST['newPassword']}', loginAttempt='{$_POST['loginAttempt']}', accountStatus='{$_POST['accountStatus']}' WHERE adminID='{$_POST['userID']}';";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");
            }
            mysqli_close($dbc);
            ?>
        <br>
        <div class="prompt">Update successful. You will now be redirected in 3 seconds.</div>
    <?php
            echo '<meta http-equiv="refresh" content="3">';
            die();
        } else { ?>
        <br>
        <div class="error"><?php echo "$out" ?></div>
    <?php
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