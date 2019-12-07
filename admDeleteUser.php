<head>
    <title>USTS- Delete User</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/outStyle.css">
    <link rel="stylesheet" href="css/form.css">
</head>

<?php
SESSION_START();
if ((preg_match("/\AADM/", @$_SESSION['loginUser'])) && (isset($_POST['deleteUser']) || isset($_POST['deleteUserConfirm']))) {
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
    ?>

    
    <?php if(isset($_POST['deleteUser'])){ 
        ?>
    <h2>Confirmation</h2>
    <div class="container">
    <div class="prompt">Are you sure you want to delete <?php echo "{$_POST['name']} ({$_POST['userID']})" ?> ?</div>
    <form method='POST'>
        <input type="text" name="userID" value="<?php echo $_POST['userID']; ?>" style="display:none">
        <input type="text" name="name" value="<?php echo $_POST['name']; ?>" style="display:none">
        <div class="row">
        <div class="col-35"></div>
        <div class="col-25"><input type="submit" name="deleteUserConfirm" value="Confirm"></div>
    </form>
    <form method='POST' action='admManageUsers.php'>
        <div class="col-15"><input type="submit" value="Cancel"></div>
    </div>
    </div>

    </form>

    </div>
    <?php } ?>

    <?php
        if (isset($_POST['deleteUserConfirm'])) {
            if (preg_match("/\ASTU/", $_POST['userID'])) {
                $query = "DELETE FROM student WHERE studentID='{$_POST['userID']}';";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                
            } else if (preg_match("/\ATUT/", $_POST['userID'])) {
                $query = "DELETE FROM tutor WHERE tutorID='{$_POST['userID']}';";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");
            }
            mysqli_close($dbc);
            ?>
            <br>
            <div class="prompt">Delete successful. You will now be redirected back to Manage User UI in 3 seconds.</div>
            <?php
            header("Refresh:3;URL=admManageUsers.php");
            die();
            
        }
            ?>

<?php
} 
else if (preg_match("/\AADM/", @$_SESSION['loginUser'])){
    ?>
    <br>
    <div class="prompt">You did not navigate the pages correctly. <br> You will be navigated back to Manage Users UI in 5 seconds.</div>
<?php
    header("Refresh:5;URL=admManageUsers.php");
    die();
}
else { 
    ?>
    <br>
    <div class="prompt">You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</div>
    <?php
    header("Refresh:5;URL=logOut.php");
    die();
}
?>