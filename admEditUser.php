<head>
    <title>USTS- Edit User</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/outStyle.css">
</head>
<?php
SESSION_START();
if ((preg_match("/\AADM/", @$_SESSION['loginUser'])) && (isset($_POST['editUser']) || (isset($_POST['editUserConfirm'])))) {
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

    <?php if (!isset($_POST['editUserConfirm'])) { ?>
        <h2>Edit User </h2>
        <div class="container">
            <form method='POST'>

                <div class="row">
                    <input type="text" name="userID" value="<?php echo $_POST['userID']; ?>" style="display:none">
                    <div class="col-25"><label>Name:</label></div>
                    <div class="col-75"><input type="text" name="name" value="<?php echo $_POST['name']; ?>" pattern="^[A-Za-z \/@]{3,50}$" required maxlength="50"> (3-50 Characters, no special characters except / and @)</div>
                </div>
                <div class="row">
                    <div class="col-25"><label>Matrix No:</label></div>
                    <div class="col-75"><input type="text" name="matrixNo" value="<?php echo $_POST['matrixNo']; ?>" pattern="[A-Z]{1}[0-9]{9}" placeholder="B123456789" required maxlength="10">(First Character must be capital letter and no space in between)</div>
                </div>
                <div class="row">
                    <div class="col-25"><label>Phone No:</label></div>
                    <div class="col-75"><input type="text" name="phoneNo" value="<?php echo $_POST['phoneNo']; ?>" pattern="[0-9]{10,15}" placeholder="0123456789" required maxlength="15"> (10-15 numbers)</div>
                </div>
                <br>
                <div class="row">
                    <div class="col-75"></div>
                    <div class="col-5"></div>
                    <div class="col-5"></div>
                    <div class="col-7"><input type="submit" name="editUserConfirm" value="Edit User"></div>
                    </form>
                   
                        <form method="POST" action="admManageUsers.php">
                        <div class="col-5"><input type="submit" value="Cancel"></div>
                        </form>

                </div>
            </div>
        </div>


    <?php }
           

            if (isset($_POST['editUserConfirm'])) {
                if (preg_match("/\ASTU/", $_POST['userID'])) {
                    $query = "UPDATE student SET name='{$_POST['name']}', matrixNo='{$_POST['matrixNo']}', phoneNo='{$_POST['phoneNo']}' WHERE studentID='{$_POST['userID']}' ORDER BY ID;";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                } else if (preg_match("/\ATUT/", $_POST['userID'])) {
                    $query = "UPDATE tutor SET name='{$_POST['name']}', matrixNo='{$_POST['matrixNo']}', phoneNo='{$_POST['phoneNo']}' WHERE tutorID='{$_POST['userID']}';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                }
                mysqli_close($dbc);
                ?>
                <br>
                <div class="prompt">Update successful. You will now be redirected back to Manage User UI in 3 seconds.</div>
            <?php
                    header("Refresh:3;URL=admManageUsers.php");
                    die();

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