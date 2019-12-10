<head>
    <title>USTS- Show Student List</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/outStyle.css">
</head>

<?php
SESSION_START();
if ((preg_match("/\ATUT/", @$_SESSION['loginUser'])) && (isset($_POST['showStudentList']))) {
    $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
    //$_SESSION['loginUser'];
    $index = 1;
    ?>
    <ul>
        <li><a href="tutUI.php">Home</a></li>
        <li class="dropdown active">
            <a href="javascript:void(0)" class="dropbtn">Manage Tutor Session</a>
            <div class="dropdown-content">
                <a href="tutNewSession.php">Add New Tutor Session</a>
                <a href="tutShowSession.php">Show Tutor Session</a>
            </div>
        </li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Manage Personal Information</a>
            <div class="dropdown-content">
                <a href="editPersonalInfo.php">Edit Personal Information</a>
                <a href="resetPersonalPassword.php">Reset Password</a>
            </div>
        </li>
        <li><a href="tutSystemUsageStatistics.php">System Usage Statistics</a></li>
        <li style="float:right"><a href="logOut.php">Log Out</a></li>
    </ul>
    <h2>Student List for topic: <?php echo " {$_POST['topic']}"; ?> (<?php echo "{$_POST['sessionID']}"; ?>) </h2><br>
    <div class="container">
        <table>
            <tr>
                <th>No.</th>
                <th>Student ID</th>
                <th>Student Name</th>
            </tr>

            <tr>

                <?php
                    $query = "SELECT t.studentID,s.name FROM student s, session_student t WHERE s.studentID=t.studentID AND t.sessionID='{$_POST['sessionID']}';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed");
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                    <td>
                        <?php
                                echo "$index";
                                $index++;
                                ?>
                    </td>
                    <td><?php echo "{$row['studentID']}"; ?></td>
                    <td><?php echo "{$row['name']}"; ?></td>
            </tr>
        <?php
            }
            ?>
        </table><br>
        <form method="POST" action="tutShowSession.php">
            <div class="row" style="float:right;"><input type='submit' value='Back'></div>
        </form>
        <br><br>
    </div>
<?php
} else if (preg_match("/\ATUT/", @$_SESSION['loginUser'])) {
    ?>
    <br>
    <div class="prompt">You did not navigate the pages correctly. <br> You will be navigated back to Show Session UI in 5 seconds.</div>
<?php
    header("Refresh:5;URL=tutShowSession.php");
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