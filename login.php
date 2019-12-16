<?php
SESSION_START(); 
?>

<html> 
    <head>
        <title>UTeM Student Tutor System</title>
        <link rel="stylesheet" href="css/form.css">
        <link rel="stylesheet" href="css/outStyle.css">
    </head>

    <body>
        <h1>UTeM Student Tutor System (USTS)</h1>
        
        <?php
        $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established");
       
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


        

        if(isset($_POST['userID'])){
            $userID=$_POST['userID'];
            $query="SELECT AdminID AS userID FROM admin UNION SELECT studentID FROM student UNION SELECT tutorID FROM tutor;";
            $result=mysqli_query($dbc, $query) or die("Query Failed");
            while($row=mysqli_fetch_assoc($result))
            {
                if($userID===$row['userID']){
                    $validUser=true;
                }
            }
            
            if($validUser==true)
        {    
            if(preg_match("/\AADM/",$userID)){
                $query="SELECT adminID,password,loginAttempt,accountStatus FROM admin WHERE adminID='$userID';";
                $result=mysqli_query($dbc, $query) or die("Query Failed");
                $result=mysqli_fetch_assoc($result);
                $userIDDB=$result['adminID'];
                $passDB=$result['password'];
                $loginAttemptDB=$result['loginAttempt'];
                $accountStatusDB=$result['accountStatus'];
                if ($accountStatusDB==0){
                    $loginAttemptStatus=false; 
                }
                
            }
            else if (preg_match("/\ATUT/",$userID)){
                $query="SELECT tutorID,password,loginAttempt,accountStatus FROM Tutor WHERE tutorID='$userID';";
                $result=mysqli_query($dbc, $query) or die("Query Failed");
                $result=mysqli_fetch_assoc($result);
                $userIDDB=$result['tutorID'];
                $passDB=$result['password'];
                $loginAttemptDB=$result['loginAttempt'];
                $accountStatusDB=$result['accountStatus'];
                if ($accountStatusDB==0){
                    $loginAttemptStatus=false; 
                }
            }
            else if (preg_match("/\ASTU/",$userID)){
                $query="SELECT studentID,password,loginAttempt,accountStatus FROM student WHERE studentID='$userID';";
                $result=mysqli_query($dbc, $query) or die("Query Failed");
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
                $out.="No such user registered. Please register a new account.";
                $validUser==false;
            }

            
        }

        if(isset($_POST['pass'])){
            $pass=$_POST['pass'];
        }
        
        if ($validUser==true&&isset($_POST['pass'])&&isset($_POST['userID'])){
            if(($userID === $userIDDB) && ($pass === $passDB)){ 
                if(preg_match("/\AADM/",$userID)){
                    $query ="UPDATE admin SET accountStatus=1 WHERE adminid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed"); 
                    $query ="UPDATE admin SET loginattempt=0 WHERE adminid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed");
                    $_SESSION['loginUser']="$userID";
                    mysqli_close($dbc);
                    header("Location:admUI.php");
                    die();
                 }
                 else if (preg_match("/\ATUT/",$userID)){
                    $query ="UPDATE tutor SET accountStatus=1 WHERE tutorid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed"); 
                    $query ="UPDATE tutor SET loginattempt=0 WHERE tutorid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed");
                    $_SESSION['loginUser']="$userID";
                    mysqli_close($dbc);
                    header("Location:tutUI.php");
                    die();
                 }
                 else if (preg_match("/\ASTU/",$userID)){
                    $query ="UPDATE tutor SET accountStatus=1 WHERE tutorid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed"); 
                    $query ="UPDATE tutor SET loginattempt=0 WHERE tutorid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed");
                    $_SESSION['loginUser']="$userID";
                    mysqli_close($dbc);
                    header("Location:stuUI.php");
                    die();
                 }
             }
             else if ($userID === $userIDDB)
             {           
                 $newLoginAttempt= $loginAttemptDB+1;
                 if(preg_match("/\AADM/",$userID)){
                    if($loginAttemptDB>=2) {
                        $query ="UPDATE admin SET accountStatus=0 WHERE adminid='$userID';";
                        $result = mysqli_query($dbc, $query) or die("Query Failed");
                        $out.="This account has been blocked for entering the wrong password for more than 3 times. Please contact administrator at admin@USTS.com for further assistance.";
                        
                    }
                    $query ="UPDATE admin SET loginattempt=$newLoginAttempt WHERE adminid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed");
                    
                    }
                else if(preg_match("/\ATUT/",$userID)){
                    if($loginAttemptDB>2) {
                        $query ="UPDATE tutor SET accountStatus=0 WHERE tutorid='$userID';";
                        $result = mysqli_query($dbc, $query) or die("Query Failed");
                        $out.="This account has been blocked for entering the wrong password for more than 3 times. Please contact administrator at admin@USTS.com for further assistance.";
                    }
                    $query ="UPDATE tutor SET loginattempt=$newLoginAttempt WHERE tutorid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed");
                 }
                 else if (preg_match("/\ASTU/",$userID)){
                    if($loginAttemptDB>2) {
                        $query ="UPDATE student SET accountStatus=0 WHERE studentid='$userID';";
                        $result = mysqli_query($dbc, $query) or die("Query Failed");
                        $out.="This account has been blocked for entering the wrong password for more than 3 times. Please contact administrator at admin@USTS.com for further assistance.";
                    }
                    $query ="UPDATE student SET loginattempt=$newLoginAttempt WHERE studentid='$userID';";
                    $result = mysqli_query($dbc, $query) or die("Query Failed");
                    }

                    if($loginAttemptDB<=2){
                    $out .="Incorrect Credentials. Please try again.";
                    }
            }
         }
        ?>

         <?php
        if($loginSuccessful!=true){
         ?>

        <h2>Login</h2>
        <div class="container">
        <form action= 'login.php' method='POST'>
            <div class="row">
            <div class="col-25">UserID: </div>
            <div class="col-75"><input type='text' name='userID' value='<?php echo $userID ?>' required><br> (Eg: STU5, TUT3, ADM1,...)
            </div>
        </div>
        <div class="row">
            <div class="col-25">Password: </div>
            <div class="col-75"><input type='password' name='pass' required><br>
            </div>
        </div>
        <div class="row" style="float:right;">
            <br><input type='submit' value='Login'><br>
        </div>
        </form> 
        <div class="row">
        <br><br><p>No account yet? Click <a href="registration.php">here</a> to create a new account!</p>
        </div>
    </div>
        <?php 
         }
        ?>
        <div class="error"><?php echo"$out"; ?> </div>
        
    </body>
 
 </html>



