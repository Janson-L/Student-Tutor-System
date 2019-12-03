<?php
SESSION_START();
if (preg_match("/\AADM/", @$_SESSION['loginUser'])) {
?>

<head>
    <link rel="stylesheet" href="css/style.css">
    </head>
    <ul>
        <li><a href="admUI.php">Home</a></li>
        <li class="dropdown active">
            <a href="javascript:void(0)" class="dropbtn">Manage Users</a>
            <div class="dropdown-content">
            <a href="admAddUser.php">Add User</a>
            <a href="admManageUsers.php">Manage Users</a>
        </div>
        </li>
        <li><a href="admManageTutorSession.php">Manage Tutor Session</a></li>
        <li><a href="admSystemUsageStatistics.php">System Usage Statistics</a></li>
        <li style="float:right"><a href="logOut.php">Log Out</a></li>
    </ul>



<?php
    $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
    $validUpdate=false;
    if(isset($_POST['newPassword'])&&isset($_POST['newPasswordRetype']))
    {
        if($_POST['newPassword']===$_POST['newPasswordRetype'])
        {
            $validUpdate=true;
        }
        else{
            echo"Wrong Password. Please make sure password is keyed in correctly.";
        }
    }
?>
<?php if($validUpdate!=true){ ?>
<form method='POST'>
    <input type="text" name="userID" value=<?php echo $_POST['userID'] ?> style="display:none">
    <label>New Password:</label><input type="text" name="newPassword" required maxlength="12"><br>
    <label>Retype New Password:</label><input type="text" name="newPasswordRetype" required maxlength="12"><br>
    <input type="text" name="loginAttempt" value="0" style="display:none">
    <input type="text" name="accountStatus" value="1" style="display:none">
    <input type="submit" name="resetPasswordConfirm" value="Confirm">
</form>
<form method='POST' action='admManageUsers.php'>
    <input type="submit" value="Cancel">
</form>
<?php } ?>

<?php
    if((isset($_POST['resetPasswordConfirm']))&&($validUpdate==true))
    {
        if(preg_match("/\ASTU/", $_POST['userID']))
        {
            $query="UPDATE student SET password='{$_POST['newPassword']}', loginAttempt='{$_POST['loginAttempt']}', accountStatus='{$_POST['accountStatus']}' WHERE studentID='{$_POST['userID']}';";
            $result=mysqli_query($dbc,$query) or die("Query Failed $query");
        }
        else if (preg_match("/\ATUT/", $_POST['userID']))
        {
            $query="UPDATE tutor SET name='{$_POST['newPassword']}', loginAttempt='{$_POST['loginAttempt']}', accountStatus='{$_POST['accountStatus']}' WHERE tutorID='{$_POST['userID']}';";
            $result=mysqli_query($dbc,$query) or die("Query Failed $query");
        }
            mysqli_close($dbc);
            echo"Update successful. You will now be redirected back to Manage User UI in 3 seconds.";
            header("Refresh:3;URL=admManageUsers.php");
            die();
    }
?>

<?php
}
else {
    echo "<h3>You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</h3>";
    header("Refresh:5;URL=logOut.php");
   die();
}
?>