<?php
SESSION_START(); 
?>

<html> 
    <head>
        <meta charset="utf-8">
        <title>UTeM Student Tutor System</title>
        <style>
        /*    body{
                text-align:center;
            }*/
        </style>
    </head>

    <body>
        <h1>UTeM Student Tutor System</h1>
        
        <?php
       
        $loginAttemptStatus = true; //Flag for checking login ability. Deny login if it is more than 3 times 
        $loginSuccessful=false;//Flag for denying 
        $validUser=false;
        $out="";
        $userID="";
        $pass="";
        $userIDDB="";
        $passDB="";
        $loginAttemptDB="";
        $accountStatusDB="";


        $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established");

        if(isset($_POST['userID'])){
            $userID=$_POST['userID'];
            $query="SELECT AdminID AS userID FROM admin UNION SELECT studentID FROM student UNION SELECT tutorID FROM tutor;";
            $result=mysqli_query($dbc, $query) or die("Query Failed $query");
            while($row=mysqli_fetch_assoc($result))
            {
                if($userID===$row['userID']){
                    $validUser=true;
                }
            }
            
            if($validUser==true)
        {    
            if(preg_match("/ADM/",$userID)){
                $query="SELECT adminID,password,loginAttempt,accountStatus FROM admin WHERE adminID='$userID';";
                $result=mysqli_query($dbc, $query) or die("Query Failed $query");
                $result=mysqli_fetch_assoc($result);
                $userIDDB=$result['adminID'];
                $passDB=$result['password'];
                $loginAttemptDB=$result['loginAttempt'];
                $accountStatusDB=$result['accountStatus'];
                if ($accountStatusDB==0){
                    $loginAttemptStatus=false; 
                }
                
            }
            else if (preg_match("/TUT/",$userID)){
                $query="SELECT tutorID,password,loginAttempt,accountStatus FROM Tutor WHERE tutorID='$userID';";
                $result=mysqli_query($dbc, $query) or die("Query Failed $query");
                $result=mysqli_fetch_assoc($result);
                $userIDDB=$result['tutorID'];
                $passDB=$result['password'];
                $loginAttemptDB=$result['loginAttempt'];
                $accountStatusDB=$result['accountStatus'];
                if ($accountStatusDB==0){
                    $loginAttemptStatus=false; 
                }
            }
            else if (preg_match("/STU/",$userID)){
                $query="SELECT studentID,password,loginAttempt,accountStatus FROM student WHERE studentID='$userID';";
                $result=mysqli_query($dbc, $query) or die("Query Failed $query");
                $result=mysqli_fetch_assoc($result);
                $userIDDB=$result['studentID'];
                $passDB=$result['password'];
                $loginAttemptDB=$result['loginAttempt'];
                $accountStatusDB=$result['accountStatus'];
                if ($accountStatusDB==0){
                    $loginAttemptStatus=false; 
                }
            }
        }  
            else{
                $out.="No such userID found in the system. Please register a new account or contact administrator for further assistance ";
                $validUser==false;
            }

            
        }

        if(isset($_POST['pass'])){
            $pass=$_POST['pass'];
        }
        
        if ($validUser==true&&isset($_POST['pass'])&&isset($_POST['userID'])){
            if(($userID === $userIDDB) && ($pass === $passDB)){ 
                if(preg_match("/ADM/",$userID)){
                    $query ="UPDATE admin SET accountStatus=1 WHERE adminid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query"); 
                    $query ="UPDATE admin SET loginattempt=0 WHERE adminid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                    $_SESSION['loginUser']="$userID";
                    header("Location:admUI.php");
                    die();
                 }
                 else if (preg_match("/TUT/",$userID)){
                    $query ="UPDATE tutor SET accountStatus=1 WHERE tutorid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query"); 
                    $query ="UPDATE tutor SET loginattempt=0 WHERE tutorid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                    $_SESSION['loginUser']="$userID";
                    header("Location:tutUI.php");
                    die();
                 }
                 else if (preg_match("/STU/",$userID)){
                    $query ="UPDATE tutor SET accountStatus=1 WHERE tutorid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query"); 
                    $query ="UPDATE tutor SET loginattempt=0 WHERE tutorid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                    $_SESSION['loginUser']="$userID";
                    header("Location:stuUI.php");
                    die();
                 }
             }
             else if ($userID === $userIDDB)
             {           
                 $newLoginAttempt= $loginAttemptDB+1;
                 if(preg_match("/ADM/",$userID)){
                    if($loginAttemptDB>=2) {
                        $query ="UPDATE admin SET accountStatus=0 WHERE adminid='$userID';";
                        $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                        $out.="This account has been blocked for entering the wrong password for more than 3 times. Please contact administrator for further assistance.";
                        
                    }
                    $query ="UPDATE admin SET loginattempt=$newLoginAttempt WHERE adminid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                    
                    }
                else if(preg_match("/TUT/",$userID)){
                    if($loginAttemptDB>=2) {
                        $query ="UPDATE tutor SET accountStatus=0 WHERE tutorid='$userID';";
                        $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                        $out.="This account has been blocked for entering the wrong password for more than 3 times. Please contact administrator for further assistance.";
                    }
                    $query ="UPDATE tutor SET loginattempt=$newLoginAttempt WHERE tutorid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                 }
                 else if (preg_match("/STU/",$userID)){
                    if($loginAttemptDB>=2) {
                        $query ="UPDATE student SET accountStatus=0 WHERE studentid='$userID';";
                        $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                        $out.="This account has been blocked for entering the wrong password for more than 3 times. Please contact administrator for further assistance.";
                    }
                    $query ="UPDATE student SET loginattempt=$newLoginAttempt WHERE studentid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                    }

                    if($loginAttemptDB<2){
                    $out .="Incorrect Credentials. Please try again or contact administrator for further assistance ";
                    }
            }
         }
        ?>

         <?php
        if($loginSuccessful!=true){
         ?>
        <h1>Login Form</h1>
        <h3>Please input your login credentials.</h3>

        <form action= 'login.php' method='POST'>
            UserID: <input type='text' name='userID' value='<?php echo $userID ?>' required><br>
            Password: <input type='password' name='pass' required><br>
            <input type='submit' value='Login'><br>
        </form> 

        <a href="registration.php">Click here to create a new account!</a>
        
        <?php 
         }
            echo "<h2>$out</h2>";
        ?>
        
    </body>
 
 </html>



