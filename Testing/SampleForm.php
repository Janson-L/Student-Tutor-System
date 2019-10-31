<html>
    <head>
        <style>
        </style>
    </head>
    <body>
        <h1>UTeM Student Tutor System</h1>
        <h3>Registration From</h3>
        <h5>Please key in the necessary details</h5>

        <?php
            $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established"); //Register and change to a non root user
            $formCheck=true;

            $out="";
            $userType=""; 
            $userName="";
            $matrixNo="";
            $phoneNo="";
            $userID=""; //need to be connected to database to fetch last userID, do some processing to generate new ID
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
                    $formCheck=false;
                    $out .="Incorrect Password. Please make sure password is the same.";
                }     
                else
                {
                    $successRegistration=true;
                }
            }
        ?>

        <form action='SampleForm.php' method='GET'>
        <label>User Type</label>
        <select name='userType' required>
        <option <?php if($userType=="student") echo 'selected="selected"'; ?>value='student'>Student</option>
        <option <?php if($userType=="tutor") echo 'selected="selected"'; ?>value='tutor'>Tutor</option>
    

        </select><br>

        <label>Name: </label><input type='text' name='userName' value='<?php echo $userName ?>' pattern="[A-Za-z\s\W]{5,30}" required><br>
        <label>Matrix No: </label><input type='text' name='matrixNo' value='<?php echo $matrixNo ?>' pattern="[A-Z]{1}[0-9]{9}" required ><br>
        <label>Phone No: </label><input type='text' name='phoneNo' value='<?php echo $phoneNo ?>' pattern="[0-9]{10,11}" required ><br>
        <label>UserID: </label><input type='text' name='userID' value='<?php echo $userID ?>'readonly><br>
        <label>Password: </label><input type='password' name='pass' required><br>
        <label>Retype Password: </label><input type='password' name='passRetype' required><br>   
        <input type='submit' value='Submit Form'><br>
        </form>

        <?php
            if($formCheck==false){
                echo "<h5>$out</h5>";
            }

            if($successRegistration==true)
            {
                echo "<h5>Successful Registration</h5>";
            }
        ?>
    </body>
</html>