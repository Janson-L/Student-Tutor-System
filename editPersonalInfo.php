<head>
    <title>USTS- Edit Personal Info</title>
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
    
        if (preg_match("/\AADM/", @$_SESSION['loginUser'])){
            $query="SELECT adminID AS userID, name, matrixNo, phoneNo FROM admin WHERE adminID='{$_SESSION['loginUser']}';";
            $result=mysqli_query($dbc,$query) or die("Query Failed");
        }
        else if (preg_match("/\ASTU/", @$_SESSION['loginUser'])){
            $query="SELECT studentID AS userID, name, matrixNo, phoneNo FROM student WHERE studentID='{$_SESSION['loginUser']}';";
            $result=mysqli_query($dbc,$query) or die("Query Failed");
        }
        else if (preg_match("/\ATUT/", @$_SESSION['loginUser'])){
            $query="SELECT tutorID AS userID, name, matrixNo, phoneNo FROM tutor WHERE tutorID='{$_SESSION['loginUser']}';";
            $result=mysqli_query($dbc,$query) or die("Query Failed");
        }
        $row=mysqli_fetch_assoc($result);
    ?>

    <?php if (!isset($_POST['editInfoConfirm'])) { ?>
        <h2>Edit Personal Info </h2>
        <div class="container">
            <form method='POST'>
                <div class="row">
                    <input type="text" name="userID" value="<?php echo $row['userID']; ?>" style="display:none">
                    <div class="col-25"><label>Name:</label></div>
                    <div class="col-75"><input type="text" name="name" value="<?php echo $row['name']; ?>" pattern="[A-Za-z /@]{3,50}" required maxlength="50"> (3-50 Characters, no special characters except / and @)</div>
                </div>
                <div class="row">
                    <div class="col-25"><label>Matrix No:</label></div>
                    <div class="col-75"><input type="text" name="matrixNo" value="<?php echo $row['matrixNo']; ?>" pattern="[A-Z]{1}[0-9]{9}" placeholder="B123456789" required maxlength="10"></div>
                </div>
                <div class="row">
                    <div class="col-25"><label>Phone No:</label></div>
                    <div class="col-75"><input type="text" name="phoneNo" value="<?php echo $row['phoneNo']; ?>" pattern="[0-9]{10,15}" placeholder="0123456789" required maxlength="15"> (10-15 numbers)</div>
                </div>
                <br>
                    <div class="row" style="float:right;"><input type="submit" name="editInfoConfirm" value="Update Information"></div>
                <br><br> 
                </form>
                </div>   
 


    <?php } ?>
    <?php
        if (isset($_POST['editInfoConfirm'])) {
            if (preg_match("/\ASTU/", $_POST['userID'])) {
                $query = "UPDATE student SET name='{$_POST['name']}', matrixNo='{$_POST['matrixNo']}', phoneNo='{$_POST['phoneNo']}' WHERE studentID='{$_POST['userID']}';";
                $result = mysqli_query($dbc, $query) or die("Query Failed");
            } else if (preg_match("/\ATUT/", $_POST['userID'])) {
                $query = "UPDATE tutor SET name='{$_POST['name']}', matrixNo='{$_POST['matrixNo']}', phoneNo='{$_POST['phoneNo']}' WHERE tutorID='{$_POST['userID']}';";
                $result = mysqli_query($dbc, $query) or die("Query Failed");
            } else if(preg_match("/\AADM/", $_POST['userID'])){
                $query = "UPDATE admin SET name='{$_POST['name']}', matrixNo='{$_POST['matrixNo']}', phoneNo='{$_POST['phoneNo']}' WHERE adminID='{$_POST['userID']}';";
                $result = mysqli_query($dbc, $query) or die("Query Failed");
            }
            mysqli_close($dbc);
            ?>
        <br>
        <div class="prompt">Update successful. You will now be redirected in 3 seconds.</div>
    <?php
            echo '<meta http-equiv="refresh" content="3">';
            die();
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