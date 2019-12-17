<head>
    <title>USTS- Reset Password</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/outStyle.css">
</head>

<?php
SESSION_START();
if ((preg_match("/\AADM/", @$_SESSION['loginUser'])) && (isset($_POST['resetPassword']) || isset($_POST['resetPasswordConfirm']))) {
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
        <li><a href="admManageTutorSession.php">Manage Tutoring Session</a></li>
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
                <input type="text" name="userID" value=<?php echo "{$_POST['userID']}" ?> style="display:none">
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
                    <div class="col-75"></div>
                    <div class="col-5"></div>
                    <div class="col-5"></div>
                    <div class="col-5"><input type="submit" name="resetPasswordConfirm" value="Confirm"></div>

            </form>

            <form method="POST" action="admManageUsers.php">
                <div class="col-5"><input type="submit" value="Cancel"></div>
                </div>
            </form>
        </div>
    <?php } ?>

    <?php
        if ((isset($_POST['resetPasswordConfirm'])) && ($validUpdate == true)) {
            if (preg_match("/\ASTU/", $_POST['userID'])) {
                $query = "UPDATE student SET password='{$_POST['newPassword']}', loginAttempt='{$_POST['loginAttempt']}', accountStatus='{$_POST['accountStatus']}' WHERE studentID='{$_POST['userID']}';";
                $result = mysqli_query($dbc, $query) or die("Query Failed");
            } else if (preg_match("/\ATUT/", $_POST['userID'])) {
                $query = "UPDATE tutor SET password='{$_POST['newPassword']}', loginAttempt='{$_POST['loginAttempt']}', accountStatus='{$_POST['accountStatus']}' WHERE tutorID='{$_POST['userID']}';";
                $result = mysqli_query($dbc, $query) or die("Query Failed");
            }
            mysqli_close($dbc);
            ?>
        <br>
        <div class="prompt">Update successful. You will now be redirected back to Manage User UI in 3 seconds.</div>
    <?php
            header("Refresh:3;URL=admManageUsers.php");
            die();
        } else { ?>
        <br>
        <div class="error"><?php echo "$out" ?></div>
    <?php
        }
        ?>




<?php
} else if (preg_match("/\AADM/", @$_SESSION['loginUser'])) {
    ?>
    <br>
    <div class="prompt">You did not navigate the pages correctly. <br> You will be navigated back to Manage Users UI in 5 seconds.</div>
<?php
    header("Refresh:5;URL=admManageUsers.php");
    die();
} else {
    ?>
    <br>
    <div class="prompt">You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</div>
<?php
    header("Refresh:5;URL=logOut.php");
    die();
}
?>