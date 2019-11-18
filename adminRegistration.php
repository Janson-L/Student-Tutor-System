<html>
    <head>
        <style>
        </style>
    </head>
    <body>
        <h1>UTeM Student Tutor System</h1>
        <h3>Admin Registration Form</h3>

        <?php
            $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established"); //Register and change to a non root user
            $formCheck=true;

            $out=""; 
            $userName="";
            $matrixNo="";
            $phoneNo="";
            $userID="";
            $successRegistration=false;

            if(isset($_GET['userName'])){
                $userName=$_GET['userName'];
                $idquery="SELECT id FROM Admin ORDER BY ID DESC LIMIT 1;";
                $result=mysqli_query($dbc, $idquery) or die("Query Failed");
                $prevIdDb=mysqli_fetch_assoc($result);
                $userID.="ADM";

                if($prevIdDb['id']==null){
                $id=1;
                }
                else{
                $id=$prevIdDb['id']+1;
                }

                $userID.=$id;  
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
                    $out = "Registration Successful. New adminID is $userID and you can now login into the system.";
                }
            }
        ?>

        <?php if($successRegistration==false){ ?>
        <h5>Please key in the necessary details</h5>
        <form action='adminRegistration.php' method='GET'>

        <!--<label>UserID: </label><input type='text' name='userID' value='<?php echo $userID ?>'readonly><br> -->
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
                $query ="INSERT INTO Admin (ID,AdminID,Name,MatrixNo,PhoneNo,Password,LoginAttempt,AccountStatus) VALUES('$id','$userID','$userName','$matrixNo','$phoneNo','$pass',0,1);";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                echo "<h5>$out</h5>";
            }
        ?>
    </body>
</html>