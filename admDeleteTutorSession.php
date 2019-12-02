<?php
SESSION_START();
if (preg_match("/\AADM/", @$_SESSION['loginUser'])) {
?>

<head>
    <link rel="stylesheet" href="css/style.css">
    </head>
    <ul>
        <li><a href="admUI.php">Home</a></li>
        <li><a href="admAddUser.php">Add User</a></li>
        <li><a href="admManageTutorSession.php">Manage Tutor Session</a></li>
        <li class="active"><a href="admManageUsers.php">Manage Users</a></li>
        <li style="float:right"><a href="logOut.php">Log Out</a></li>
    </ul> <br>


<?php 
    $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
    $userClass="";

?>

    <?php if(!isset($_POST['deleteSessionConfirm'])){ ?>
        <h2>Confirmation </h2>
        Are you sure you want to delete <?php echo "{$_POST['topic']} ({$_POST['sessionID']})" ?> ?<br>
    <form method='POST'>
        <input type="text" name="sessionID" value="<?php echo $_POST['sessionID']; ?>" style="display:none"><br>
        <label></label><input type="submit" name="deleteSessionConfirm" value="Confirm">
    </form>

    <form method='POST' action='admManageTutorSession.php'>
        <input type="submit" value="Cancel">
    </form>
    <?php } ?>
<?php
    if(isset($_POST['deleteSessionConfirm']))
    {
       $query="DELETE FROM tutoringSession WHERE sessionID='{$_POST['sessionID']}';";
       $result=mysqli_query($dbc,$query) or die("Query Failed $query");
       echo"Update successful. You will now be redirected back to Manage User UI.";
       header("Refresh:5;URL=admManageTutorSession.php");
       die();
    }
        ?>

<?php
} else {
    echo "<h3>You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</h3>";
    header("Refresh:5;URL=logOut.php");
    die();
}
?>