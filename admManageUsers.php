<head>
    <title>USTS-Update and Delete User</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/outStyle.css">
</head>
<?php
SESSION_START();
if (preg_match("/\AADM/", @$_SESSION['loginUser'])) {
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
        <li><a href="admManageTutorSession.php">Manage Tutoring Session</a></li>
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
    <h2>Update and Delete User</h2>
    <div class="container">
        <form method='POST'>
            <div class="row">
                <div class="col-15"><label>Search Type:</label></div>
                <div class="col-15"><select name='searchType' required>
                        <option <?php if ($searchType == "userNameSearch") echo 'selected="selected"'; ?>value='userNameSearch'>Search by user name</option>
                        <option <?php if ($searchType == "userIDSearch") echo 'selected="selected"'; ?>value='userIDSearch'>Search by userID</option>
                    </select>
                </div>

                <div class="col-25"><input type='text' name='searchQuery' value='<?php echo $searchQuery ?>' required pattern="^[A-Za-z \/@]{1,50}$" maxlength="30">(Maximum 30 characters)</div> 
                <div class="col-5"><input type='submit' name='search' value='Search'></div>
        </form>
        <form method='POST' action='admManageUsers.php'>
            <div class="col-5"><input type='submit' value='Refresh'></div>
        </form>
    </div>


    <?php
        if (isset($_POST['search'])) {
            if ($_POST['searchType'] == "userIDSearch") {
                $searchTable = 1;
            } else if ($_POST['searchType'] == "userNameSearch") {
                $searchTable = 2;
            }
            else{
                $searchTable=0;
            }
        }
        ?>

    <?php
        if ($searchTable == 0) { ?>
            <h3>Show All</h3>
        <?php } else if ($searchTable == 1) { ?>
        <h3>Search by User ID</h3>
    <?php } else if ($searchTable == 2) { ?>
        <h3>Search by User Name</h3>
    <?php } ?>
    <?php
        if($searchTable==0)
        {
            $query="SELECT studentID AS userID, name, matrixNo, phoneNo,loginAttempt, accountStatus FROM student UNION
            SELECT tutorID, name, matrixNo, phoneNo,loginAttempt, accountStatus FROM tutor;";
        }
      
        if (isset($_POST['search'])) {
            
            if ($searchTable == 1) {
                $query = "SELECT studentID AS userID, name, matrixNo, phoneNo,loginAttempt, accountStatus FROM student WHERE studentID='$searchQuery' UNION
                SELECT tutorID, name, matrixNo, phoneNo,loginAttempt, accountStatus FROM tutor WHERE tutorID='$searchQuery';";
            } else if ($searchTable == 2) {
                $query = "SELECT studentID AS userID, name, matrixNo, phoneNo,loginAttempt, accountStatus FROM student WHERE name LIKE'%$searchQuery%' UNION
                SELECT tutorID, name, matrixNo, phoneNo,loginAttempt, accountStatus FROM tutor WHERE name LIKE'%$searchQuery%';";
            }
        }
            $result = mysqli_query($dbc, $query) or die("Query Failed");
            if (mysqli_num_rows($result) > 0) {
                $currentDate = date('Y-m-d', time());
                $currentTime = date('His', time());
                $currentTime += "070000";
                ?>
            <table>
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
                    } else {
                        echo "No result is found. Please make sure you have entered the correct search term.";
                    }


                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                <tr>
                    <td><?php echo $row['userID']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['matrixNo']; ?></td>
                    <td><?php echo $row['phoneNo']; ?></td>
                    <td><?php echo $row['loginAttempt']; ?></td>
                    <td><?php echo $row['accountStatus']; ?></td>
                    <td>
                        <form method='POST' action='admEditUser.php'>
                            <input type="text" name="userID" value="<?php echo $row['userID']; ?>" style="display:none">
                            <input type="text" name="name" value="<?php echo $row['name']; ?>" style="display:none">
                            <input type="text" name="matrixNo" value="<?php echo $row['matrixNo']; ?>" style="display:none">
                            <input type="text" name="phoneNo" value="<?php echo $row['phoneNo']; ?>" style="display:none">
                            <input type="text" name="loginAttempt" value="<?php echo $row['loginAttempt']; ?>" style="display:none">
                            <input type="text" name="accountStatus" value="<?php echo $row['accountStatus']; ?>" style="display:none">
                            <input type="submit" name="editUser" value="Edit User">
                        </form>
                    </td>
                    <td>
                        <form method='POST' action='admResetPassword.php'>
                            <input type="text" name="userID" value="<?php echo $row['userID']; ?>" style="display:none">
                            <input type="text" name="name" value="<?php echo $row['name']; ?>" style="display:none">
                            <input type="text" name="matrixNo" value="<?php echo $row['matrixNo']; ?>" style="display:none">
                            <input type="text" name="phoneNo" value="<?php echo $row['phoneNo']; ?>" style="display:none">
                            <input type="text" name="password" value="<?php echo $row['password']; ?>" style="display:none">
                            <input type="text" name="loginAttempt" value="<?php echo $row['loginAttempt']; ?>" style="display:none">
                            <input type="text" name="accountStatus" value="<?php echo $row['accountStatus']; ?>" style="display:none">
                            <input type="submit" name="resetPassword" value="Reset Password">
                        </form>
                    </td>
                    <td>
                        <form method='POST' action='admDeleteUser.php'>
                            <input type="text" name="userID" value="<?php echo $row['userID']; ?>" style="display:none">
                            <input type="text" name="name" value="<?php echo $row['name']; ?>" style="display:none">
                            <input type="text" name="matrixNo" value="<?php echo $row['matrixNo']; ?>" style="display:none">
                            <input type="text" name="phoneNo" value="<?php echo $row['phoneNo']; ?>" style="display:none">
                            <input type="text" name="loginAttempt" value="<?php echo $row['loginAttempt']; ?>" style="display:none">
                            <input type="text" name="accountStatus" value="<?php echo $row['accountStatus']; ?>" style="display:none">
                            <input type="submit" name="deleteUser" value="Delete User">
                        </form>
                    </td>
                </tr>
        <?php }
             ?>
            </table>
            </div> <br>

            <?php
                mysqli_close($dbc);
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