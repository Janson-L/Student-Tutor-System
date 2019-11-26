<?php
SESSION_START();
if (preg_match("/ADM/", @$_SESSION['loginUser'])) {
?>
<?php
    $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
    $searchType = "";
    $searchQuery = "";
    $searchTable = 0;

    if (isset($_POST['searchType'])) {
        $searchType = $_POST['searchType'];
    }

    if (isset($_POST['searchQuery'])) {
        $searchQuery = $_POST['searchQuery'];
    }
?>
<h2>Delete User</h2>
<form method='POST'>
        <label>Search Type</label>
        <select name='searchType' required>
            <option <?php if ($searchType == "userIDSearch") echo 'selected="selected"'; ?>value='userIDSearch'>Search by userID</option>
            <option <?php if ($searchType == "userNameSearch") echo 'selected="selected"'; ?>value='userNameSearch'>Search by user name</option>
        </select>

        <input type='text' name='searchQuery' value='<?php echo $searchQuery ?>' pattern="[A-Za-z0-9 ]{0,30}" placeholder="(Maximum 30 characters)" maxlength="30">
        <input type='submit' name='search' value='Search'>
    </form>
    <form method='POST' action='admManageUsers.php'>
        <input type='submit' value='Refresh'><br>
</form>

    <?php
        if (isset($_POST['search'])) {
            if ($_POST['searchType'] == "userIDSearch") {
                $searchTable = 1;
                $searchQuery = $_POST['searchQuery'];
            } 
            else if ($_POST['searchType'] == "userNameSearch") {
                $searchTable = 2;
                $searchQuery = $_POST['searchQuery'];
            }
        
  if ($searchTable == 1) { ?>
        <h3>Search by User ID</h3>
    <?php } else if ($searchTable == 2) { ?>
        <h3>Search by User Name</h3>
    <?php } ?>
    <table border='1'>
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Matrix No</th>
            <th>Phone No</th>
            <th>Login Attempt</th>
            <th>Account Status</th>
            <th>Edit</th>
            <th>Reset Password</th>
            <th>Delete</th>
        </tr>
        <?php
            if($searchTable==1)
            {
                $query="SELECT adminID AS userID, name, matrixNo, phoneno,loginAttempt, accountStatus FROM admin UNION
                SELECT studentID, name, matrixNo, phoneno,loginAttempt, accountStatus FROM student UNION
                SELECT tutorID, name, matrixNo, phoneno,loginAttempt, accountStatus FROM tutor WHERE userID='$searchQuery';";
            }
            else if($searchTable==2)
            {
                $query="SELECT adminID AS userID, name, matrixNo, phoneno,loginAttempt, accountStatus FROM admin UNION
                SELECT studentID, name, matrixNo, phoneno,loginAttempt, accountStatus FROM student UNION
                SELECT tutorID, name, matrixNo, phoneno,loginAttempt, accountStatus FROM tutor WHERE name LIKE'%$searchQuery%';";
            }
            $result = mysqli_query($dbc, $query) or die("Query Failed $query");

            while ($row = mysqli_fetch_assoc($result)) {
                ?>
            <tr>
                <form method='POST'>
                    <td><?php echo $row['userID']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['matrixNo']; ?></td>
                    <td><?php echo $row['phoneNo']; ?></td>
                    <td><?php echo $row['loginAttempt']; ?></td>
                    <td><?php echo $row['accountStatus']; ?></td>
                    <td>
                        <input type="text" name="userID" value="<?php echo $row['userID']; ?>" style="display:none">
                        <input type="text" name="name" value="<?php echo $row['name']; ?>" style="display:none">
                        <input type="text" name="matrixNo" value="<?php echo $row['matrixNo']; ?>" style="display:none">
                        <input type="text" name="phoneNo" value="<?php echo $row['phoneNo']; ?>" style="display:none">
                        <input type="text" name="loginAttempt" value="<?php echo $row['loginAttempt']; ?>" style="display:none">
                        <input type="text" name="accountStatus" value="<?php echo $row['accountStatus']; ?>" style="display:none">
                        <input type="submit" name="edit" value="Edit">        
                    </td>
                    <td>
                        <input type="submit" name="resetPassword" value="Reset Password">        
                    </td>
                    <td>
                        <input type="submit" name="deleteUser" value="Delete User">        
                    </td>
                </form>
            </tr>
            <?php } ?>
            </table> <br>
            <?php } ?>


<form method='POST' action='admUI.php'>
<input type="submit" value="Back to Admin UI">
</form>

<?php
} 
else {
    echo "<h3>You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</h3>";
    header("Refresh:5;URL=logOut.php");
    die();
}
?>