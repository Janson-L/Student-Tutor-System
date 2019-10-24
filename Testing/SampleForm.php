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
            $userName="";
            $matrixNo="";
            $phoneNo="";
            $userID=""; //need to be connected to database to fetch last userID, do some processing to generate new ID

            if(isset($_GET['userName'])){
                $userName=$_GET['userName'];
            }

            else{
                $formCheck=false;
                $out .="Missing Name ";
            }

            if(isset($_GET['matrixNo'])){
                $userName=$_GET['matrixNo'];
            }

            else{
                $formCheck=false;
                $out .="Missing Matrix No ";
            }

            if(isset($_GET['phoneNo'])){
                $userName=$_GET['phoneNo'];
            }

            else{
                $formCheck=false;
                $out .="Missing Phone No ";
            }
        ?>

        <form action='SampleForm.php' method='GET'>
        <label>User Type</label>
        <select name='userType' required>
        <option value='student'>Student</option>
        <option value='tutor'>Tutor</option>
        </select><br>

        <label>Name: </label><input type='text' name='userName' value='<?php echo $userName ?>' required><br>
        <label>Matrix No: </label><input type='text' name='matrixNo' value='<?php echo $matrixNo ?>' required ><br>
        <label>Phone No: </label><input type='text' name='phoneNo' value='<?php echo $phoneNo ?>' required ><br>
        <label>UserID: </label><input type='text' name='userID' value='<?php echo $userID ?>'readonly><br>
        <label>Password: </label><input type='password' name='pass' required><br>
        <label>Retype Password: </label><input type='password' name='passRetype' required><br>
        <input type='submit' value='Submit Form'><br>
        </form>


        <?php
            echo "<h5>$out</h5>";
        ?>
    </body>
</html>