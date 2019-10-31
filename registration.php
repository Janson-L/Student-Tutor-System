<html>
    <head>
        <style>
        </style>
    </head>
    <body>
        <h1>UTeM Student Tutor System</h1>
        <h3>Registration Form</h3>
        

        <?php
            $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established"); //Register and change to a non root user
            $formCheck=true;

            $out="";
            $userType=""; 
            $userName="";
            $matrixNo="";
            $phoneNo="";
            $userID="";
            $successRegistration=false;

            if (isset($_GET['userType'])){
            $userType=$_GET['userType'];

                if ($userType=="student")
                {
                    $idquery="SELECT id FROM Student ORDER BY ID DESC LIMIT 1;";
                    $result=mysqli_query($dbc, $idquery) or die("Query Failed");
                    $prevIdDb=mysqli_fetch_assoc($result);
                    $userID.="STU";

                    if($prevIdDb['id']==null){
                        $id=1;
                    }
                    else{
                        $id=$prevIdDb['id']+1;
                    }
                    $userID.=$id;
                }
                else if ($userType=="tutor")
                {
                    $idquery="SELECT id FROM Tutor ORDER BY ID DESC LIMIT 1;";
                    $result=mysqli_query($dbc, $idquery) or die("Query Failed");
                    $prevIdDb=mysqli_fetch_assoc($result);
                    $userID.="TUT";

                    if($prevIdDb['id']==null){
                        $id=1;
                    }
                    else{
                        $id=$prevIdDb['id']+1;
                    }
                    $userID.=$id;
                }
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
                    $formCheck=false;
                    $out .="Incorrect Password. Please make sure password is the same.";
                }     
                else
                {
                    $pass=$_GET['pass'];
                    $successRegistration=true;
                    $out = "Registration Successful. Your userID is $userID and you can now login into the system.";
                }
            }
        ?>

        <?php if($successRegistration==false){ ?>
        <h5>Please key in the necessary details</h5>
        <form action='registration.php' method='GET'>

        <!--<label>UserID: </label><input type='text' name='userID' value='<?php echo $userID ?>'readonly><br> -->
        <label>User Type</label>
        <select name='userType' required>
        <option <?php if($userType=="student") echo 'selected="selected"'; ?>value='student'>Student</option>
        <option <?php if($userType=="tutor") echo 'selected="selected"'; ?>value='tutor'>Tutor</option>
        </select><br>

        <label>Name: </label><input type='text' name='userName' value='<?php echo $userName ?>' pattern="[A-Za-z\s\W]{5,30}" required><br>
        <label>Matrix No: </label><input type='text' name='matrixNo' value='<?php echo $matrixNo ?>' pattern="[A-Z]{1}[0-9]{9}" required ><br>
        <label>Phone No: </label><input type='text' name='phoneNo' value='<?php echo $phoneNo ?>' pattern="[0-9]{10,15}" required ><br>
        <label>Password: </label><input type='password' name='pass' required><br>
        <label>Retype Password: </label><input type='password' name='passRetype' required><br>   
        <input type='submit' value='Submit Form'><br>
        </form>
        
        <?php } ?>
        <?php
            if($formCheck==false){
                echo "<h5>$out</h5>";
            }

            if($successRegistration==true)
            {
                if($userType=="student"){
                    $query ="INSERT INTO Student (ID,StudentID,Name,MatrixNo,PhoneNo) VALUES('$id','$userID','$userName','$matrixNo','$phoneNo');";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                    $query ="INSERT INTO LoginCredentials (UserID,Password,LoginAttempt,AccountStatus) VALUES('$userID','$pass',0,1);";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                }
                else if($userType=="tutor"){
                    $query ="INSERT INTO Tutor (ID,TutorID,Name,MatrixNo,PhoneNo) VALUES('$id','$userID','$userName','$matrixNo','$phoneNo');";
                    $result = mysqli_query($dbc, $query) or die("Query Failed$query");
                    $query ="INSERT INTO LoginCredentials (UserID,Password,LoginAttempt,AccountStatus) VALUES('$userID','$pass',0,1);";
                    $result = mysqli_query($dbc, $query) or die("Query Failed$query");
                }
                
                echo "<h5>$out</h5>";
            }
        ?>
    </body>
</html>