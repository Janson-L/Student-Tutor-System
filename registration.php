<h1>UTeM Student Tutor System</h1>

        <?php
            $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established"); //Register and change to a non root user
            $formCorrectCheck=true;

            $out="";
            $userType=""; 
            $userName="";
            $matrixNo="";
            $phoneNo="";
            $userID="";
            $successRegistration=false;

            if (isset($_GET['userType'])){
                $userType=$_GET['userType'];
            }

            if(isset($_GET['userName'])){
                $userName=$_GET['userName'];
            }

            if(isset($_GET['matrixNo'])){
                $matrixNo=$_GET['matrixNo'];
            }
          
            if(isset($_GET['phoneNo'])){
                $phoneNo=$_GET['phoneNo'];
            }

            if(isset($_GET['pass'])&&isset($_GET['passRetype']))
            {
                if($_GET['pass']!=$_GET['passRetype'])
                {
                    $formCorrectCheck=false;
                    $out .="Incorrect Password. Please make sure password is the same.";
                }     
                else
                {
                    $pass=$_GET['pass'];
                    $successRegistration=true;
                }
            }
        ?>

        <?php if($successRegistration==false){ ?>
        <h3>Registration Form</h3>
        <h5>Please key in the necessary details</h5>
        <form action='registration.php' method='GET'>

        <label>User Type</label>
        <select name='userType' required>
        <option <?php if($userType=="student") echo 'selected="selected"'; ?>value='student'>Student</option>
        <option <?php if($userType=="tutor") echo 'selected="selected"'; ?>value='tutor'>Tutor</option>
        </select><br>

        <label>Name: </label><input type='text' name='userName' value='<?php echo $userName ?>' pattern="[A-Za-z /@]{5,30}" placeholder="Jason (Max. 30 characters)" required maxlength="30"><br>
        <label>Matrix No: </label><input type='text' name='matrixNo' value='<?php echo $matrixNo ?>' pattern="[A-Z]{1}[0-9]{9}" required maxlength="9"><br>
        <label>Mobile Phone No: </label><input type='text' name='phoneNo' value='<?php echo $phoneNo ?>' pattern="[0-9]{10,15}" placeholder="0123456789 (Max. 15 characters)" required maxlength="15"><br>
        <label>Password: </label><input type='password' name='pass' required><br>
        <label>Retype Password: </label><input type='password' name='passRetype' required><br>   
        <input type='submit' value='Submit Form'><br>
        </form>
        
        <?php } ?>
        <?php
            if($successRegistration==true)
            {
                if($userType=="student"){
                    $query ="INSERT INTO Student (Name,MatrixNo,PhoneNo,Password,LoginAttempt,AccountStatus) VALUES('$userName','$matrixNo','$phoneNo','$pass',0,1);";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");

                    $query="SELECT ID FROM Student ORDER BY ID DESC LIMIT 1;";
                    $result=mysqli_query($dbc, $query) or die("Query Failed");
                    $IdDb=mysqli_fetch_assoc($result);
                    $IdDb=$IdDb['ID'];
                    $userID.="STU";

                    if($IdDb==null){
                       $id=1;
                    }
                    else{
                        $id=$IdDb;
                    }
                    $userID.=$id;
                    
                    $query="UPDATE student SET studentID='$userID' WHERE ID=$IdDb;";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query"); 
                }
                else if($userType=="tutor"){
                    $query ="INSERT INTO Tutor (Name,MatrixNo,PhoneNo,Password,LoginAttempt,AccountStatus) VALUES('$userName','$matrixNo','$phoneNo','$pass',0,1);";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");

                    $query="SELECT ID FROM Tutor ORDER BY ID DESC LIMIT 1;";
                    $result=mysqli_query($dbc, $query) or die("Query Failed");
                    $IdDb=mysqli_fetch_assoc($result);
                    $IdDb=$IdDb['ID'];
                    $userID.="TUT";

                    if($IdDb==null){
                       $id=1;
                    }
                    else{
                        $id=$IdDb;
                    }
                    $userID.=$id;
                    
                    $query="UPDATE tutor SET tutorID='$userID' WHERE ID=$IdDb;";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                }

                $out = "Registration Successful. Your userID is $userID and you can now login into the system. <br> You will be redirected to the login page in 5 seconds";
                echo "<h5>$out</h5>";
                header("Refresh:5;URL=login.php");
                die();
            }
            else{
            echo "<h5>$out</h5>";
            }
        ?>
    </body>
</html>