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
        $loginStatus=false;//Flag for denying 
        $i=0;
        $j=0;
        $out="";
        $userId="";

        $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established");

        if(isset($_GET['userId'])){
            $userId=$_GET['userId'];
            $query="SELECT userid FROM LoginCredentials WHERE UserID='$userId';";
            $result=mysqli_query($dbc, $query) or die("Query Failed $query");
            $userIdDb=mysqli_fetch_assoc($result);
            
            if ($userId === $userIdDb['userid']){
            $query="SELECT accountStatus FROM loginCredentials WHERE userID='$userId';";
            $result=mysqli_query($dbc, $query) or die("Query Failed $query");
            $accountStatusDb=mysqli_fetch_assoc($result);

            $query="SELECT loginAttempt FROM loginCredentials WHERE userID='$userId';";
            $result=mysqli_query($dbc, $query) or die("Query Failed $query");
            $loginAttemptDb=mysqli_fetch_assoc($result);
            
            if ($accountStatusDb['accountStatus']==0){
                $loginAttemptStatus=false;
            }

            }
        }

        if(isset($_GET['pass'])){
        $pass=$_GET['pass'];
        $query="SELECT password FROM LoginCredentials WHERE UserID='$userId';";
        $result=mysqli_query($dbc, $query) or die("Query Failed $query");
        $passDb=mysqli_fetch_assoc($result);
        }
        
        if ($loginAttemptStatus==true&&isset($_GET['pass'])&&isset($_GET['userId'])){
            if(($userId === $userIdDb['userid']) && ($pass === $passDb['password'])){
                echo "<h2>You are now logged in</h2>";  
                $loginStatus=true;
                $query ="UPDATE loginCredentials SET accountStatus=1 WHERE userid='$userId';";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query"); 
                $query ="UPDATE loginCredentials SET loginattempt=0 WHERE userid='$userId';";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");

                if(preg_match("/ADM/",$userId)){
                    $_SESSION['loginUser']="$userId";
                    $_SESSION['userClass']="ADM";
                    header("Location:admUI.php");
                    die();
                }
                else if (preg_match("/TUT/",$userId)){
                    $_SESSION['loginUser']="$userId";
                    $_SESSION['userClass']="TUT";
                    header("Location:tutUI.php");
                    die();
                }
                else if (preg_match("/STU/",$userId)){
                    $_SESSION['loginUser']="$userId";
                    $_SESSION['userClass']="STU";
                    header("Location:stuUI.php");
                    die();
                }
            }
            else if ($userId === $userIdDb['userid']){       
                $out .="Incorrect Credentials. Please try again or contact administrator for further assistance ";
                $newLoginAttempt= $loginAttemptDb['loginAttempt'] +1;
                if($newLoginAttempt>=3)
                {
                    $query ="UPDATE loginCredentials SET accountStatus=0 WHERE userid='$userId';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                }
                $query ="UPDATE loginCredentials SET loginattempt=$newLoginAttempt WHERE userid='$userId';";
                $result = mysqli_query($dbc, $query) or die("Query Failed $query");
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
        if($loginStatus!=true){
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



