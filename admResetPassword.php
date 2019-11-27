<?php
SESSION_START();
if (preg_match("/ADM/", @$_SESSION['loginUser'])) {
?>

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
            echo"Update successful. You will now be redirected back to Manage User UI.";
            header("Refresh:5;URL=admManageUsers.php");
        }
        else if (preg_match("/\ATUT/", $_POST['userID']))
        {
            $query="UPDATE tutor SET name='{$_POST['newPassword']}', loginAttempt='{$_POST['loginAttempt']}', accountStatus='{$_POST['accountStatus']}' WHERE tutorID='{$_POST['userID']}';";
            $result=mysqli_query($dbc,$query) or die("Query Failed $query");
            echo"Update successful. You will now be redirected back to Manage User UI.";
            header("Refresh:5;URL=admManageUsers.php");
        }
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