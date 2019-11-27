<?php
SESSION_START();
if (preg_match("/ADM/", @$_SESSION['loginUser'])) {
    ?>

    <?php
        $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
    ?>

    
    <?php if(isset($_POST['deleteUser'])){ 
        ?>
    <h2>Confirmation</h2>
    Are you sure you want to delete <?php echo "{$_POST['name']} ({$_POST['userID']})" ?> ?<br>
    <form method='POST'>
        <input type="text" name="userID" value="<?php echo $_POST['userID']; ?>" style="display:none">
        <input type="text" name="name" value="<?php echo $_POST['name']; ?>" style="display:none">
        <input type="submit" name="deleteUserConfirm" value="Confirm">
    </form>
    <form method='POST' action='admManageUsers.php'>
        <input type="submit" value="Cancel">
    </form>
    <?php } ?>

    <?php
        if (isset($_POST['deleteUserConfirm'])) {
            echo"Initiate";
            if (preg_match("/\ASTU/", $_POST['userID'])) {
                $query = "DELETE FROM student WHERE studentID='{$_POST['userID']}';";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                echo "Update successful. You will now be redirected back to Manage User UI.";
                header("Refresh:5;URL=admManageUsers.php");
            } else if (preg_match("/\ATUT/", $_POST['userID'])) {
                $query = "DELETE FROM tutor WHERE tutorID='{$_POST['userID']}';";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                echo "Update successful. You will now be redirected back to Manage User UI.";
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