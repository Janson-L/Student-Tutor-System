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
        $out="";
        $userId="";
        $pass="";

        $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established");

        if(isset($_GET['userId'])){
            $userId=$_GET['userId'];
            if(preg_match("/ADM/",$userId)){
                $query="SELECT adminID,password,loginAttempt,accountStatus FROM admin WHERE adminID='$userId';";
                $result=mysqli_query($dbc, $query) or die("Query Failed $query");
                $result=mysqli_fetch_assoc($result);
                $userIdDb=$result['adminID'];
                $passDb=$result['password'];
                $loginAttemptDb=$result['loginAttempt'];
                $accountStatusDb=$result['accountStatus'];
                
            }
            else if (preg_match("/TUT/",$userId)){
                $query="SELECT tutorID,password,loginAttempt,accountStatus FROM Tutor WHERE tutorID='$userId';";
                $result=mysqli_query($dbc, $query) or die("Query Failed $query");
                $result=mysqli_fetch_assoc($result);
                $userIdDb=$result['tutorID'];
                $passDb=$result['password'];
                $loginAttemptDb=$result['loginAttempt'];
                $accountStatusDb=$result['accountStatus'];
            }
            else if (preg_match("/STU/",$userId)){
                $query="SELECT studentID,password,loginAttempt,accountStatus FROM student WHERE studentID='$userId';";
                $result=mysqli_query($dbc, $query) or die("Query Failed $query");
                $result=mysqli_fetch_assoc($result);
                $userIdDb=$result['studentID'];
                $passDb=$result['password'];
                $loginAttemptDb=$result['loginAttempt'];
                $accountStatusDb=$result['accountStatus'];
                echo "$userIdDb $passDb $loginAttemptDb $accountStatusDb";
            }  

            if ($accountStatusDb==0){
                $loginAttemptStatus=false; 
            }
        }

        if(isset($_GET['pass'])){
            $pass=$_GET['pass'];
        }
        
        if ($loginAttemptStatus==true&&isset($_GET['pass'])&&isset($_GET['userId'])){
            if(($userId === $userIdDb) && ($pass === $passDb)){ 
                if(preg_match("/ADM/",$userId)){
                    $query ="UPDATE admin SET accountStatus=1 WHERE adminid='$userId';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query"); 
                    $query ="UPDATE admin SET loginattempt=0 WHERE adminid='$userId';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                    $_SESSION['loginUser']="$userId";
                    $_SESSION['userClass']="ADM";
                    header("Location:admUI.php");
                    die();
                 }
                 else if (preg_match("/TUT/",$userId)){
                    $query ="UPDATE tutor SET accountStatus=1 WHERE tutorid='$userId';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query"); 
                    $query ="UPDATE tutor SET loginattempt=0 WHERE tutorid='$userId';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                    $_SESSION['loginUser']="$userId";
                    $_SESSION['userClass']="TUT";
                    header("Location:tutUI.php");
                    die();
                 }
                 else if (preg_match("/STU/",$userId)){
                    $query ="UPDATE tutor SET accountStatus=1 WHERE tutorid='$userId';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query"); 
                    $query ="UPDATE tutor SET loginattempt=0 WHERE tutorid='$userId';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                    $_SESSION['loginUser']="$userId";
                    $_SESSION['userClass']="STU";
                    header("Location:stuUI.php");
                    die();
                 }
             }
             else if ($userId === $userIdDb){       
                 
                 $newLoginAttempt= $loginAttemptDb +1;
                 if(preg_match("/ADM/",$userId)){
                    if($newLoginAttempt>=3) {
                        $query ="UPDATE admin SET accountStatus=0 WHERE adminid='$userId';";
                        $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                    }
                    $query ="UPDATE admin SET loginattempt=$newLoginAttempt WHERE adminid='$userId';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                 }
                 else if(preg_match("/TUT/",$userId)){
                    if($newLoginAttempt>=3) {
                        $query ="UPDATE tutor SET accountStatus=0 WHERE tutorid='$userId';";
                        $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                    }
                    $query ="UPDATE tutor SET loginattempt=$newLoginAttempt WHERE tutorid='$userId';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                 }
                 else if (preg_match("/STU/",$userId)){
                    if($newLoginAttempt>=3) {
                        $query ="UPDATE student SET accountStatus=0 WHERE studentid='$userId';";
                        $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                    }
                    $query ="UPDATE student SET loginattempt=$newLoginAttempt WHERE studentid='$userId';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                 }

                 $out .="Incorrect Credentials. Please try again or contact administrator for further assistance ";
             }
             else{
                 $out.="No such userID found in the system. Please register a new account or contact administrator for further assistance ";
             }
         }
         else if ($loginAttemptStatus==false){
             $out.="This account has been blocked due to excessive times of inputting the wrong password. Please contact administrator for further assistance.";
         }

        ?>

         <?php
        if($loginSuccessful!=true){
         ?>
        <h1>Login Form</h1>
        <h3>Please input your login credentials.</h3>

        <form action= 'login.php' method='GET'>
            UserID: <input type='text' name='userId' value='<?php echo $userId ?>' required><br>
             Password: <input type='password' name='pass' required><br>
             <input type='submit' value='Login'><br>
        </form> 
        
        <?php 
         }
            echo "<h2>$out</h2>";
        ?>
        
    </body>
 
 </html>



