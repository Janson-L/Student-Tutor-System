<?php
SESSION_START();
if (preg_match("/\AADM/", @$_SESSION['loginUser'])) {
?>
<?php 
    $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
    $userClass="";

?>

    <?php if(!isset($_POST['editUserConfirm'])){ ?>
        <h2>Edit User </h2>
    <form method='POST'>
    <?php if (preg_match("/\ASTU/", $_POST['userID'])){
    ?>
        <label>Student ID:</label><input type="text" name="userID" value="<?php echo $_POST['userID']; ?>" readonly><br>
    <?php
    }
    else if (preg_match("/\ATUT/", $_POST['userID'])){?>
        <label>Tutor ID:</label><input type="text" name="userID" value="<?php echo $_POST['userID']; ?>" readonly><br>
    <?php } ?>
        
        <label>Name:</label><input type="text" name="name" value="<?php echo $_POST['name']; ?>"pattern="[A-Za-z /@]{3,30}" required maxlength="30" > (3-30 Characters, no special characters except / and @)<br>
        <label>Matrix No:</label><input type="text" name="matrixNo" value="<?php echo $_POST['matrixNo']; ?>"pattern="[A-Z]{1}[0-9]{9}" placeholder="B123456789"required maxlength="10"><br>
        <label>Phone No:</label><input type="text" name="phoneNo" value="<?php echo $_POST['phoneNo']; ?>"pattern="[0-9]{10,15}" placeholder="0123456789" required maxlength="15"> (10-15 numbers)<br>

        <label></label><input type="submit" name="editUserConfirm" value="Edit User">
    </form>

    <form method='POST' action='admManageUsers.php'>
        <input type="submit" value="Back to Manage Users UI">
    </form>
    <?php } ?>
<?php
    if(isset($_POST['editUserConfirm']))
    {
        var_dump($_POST);
        if(preg_match("/\ASTU/", $_POST['userID']))
        {
            $query="UPDATE student SET name='{$_POST['name']}', matrixNo='{$_POST['matrixNo']}', phoneNo='{$_POST['phoneNo']}' WHERE studentID='{$_POST['userID']}';";
            $result=mysqli_query($dbc,$query) or die("Query Failed $query");
            echo"Update successful. You will now be redirected back to Manage User UI.";
            header("Refresh:5;URL=admManageUsers.php");
        }
        else if (preg_match("/\ATUT/", $_POST['userID']))
        {
            $query="UPDATE tutor SET name='{$_POST['name']}', matrixNo='{$_POST['matrixNo']}', phoneNo='{$_POST['phoneNo']}' WHERE tutorID='{$_POST['userID']}';";
            $result=mysqli_query($dbc,$query) or die("Query Failed $query");
            echo"Update successful. You will now be redirected back to Manage User UI.";
            header("Refresh:5;URL=admManageUsers.php");
        }
        
    }
        ?>
<?php
} else {
    echo "<h3>You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</h3>";
    header("Refresh:5;URL=logOut.php");
    die();
}
?>