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
       
        $loginCredentialCheck = true;
        $loginStatus=false;
        $i=0;
        $j=0;
        $out="";

        $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established");

        if(isset($_GET['userId'])){
            $userId=$_GET['userId'];
            $query="SELECT userid FROM LoginCredentials WHERE UserID='$userId';";
            $result=mysqli_query($dbc, $query) or die("Query Failed");
            $userIdDb=mysqli_fetch_assoc($result);
        }
        else{
           
            if($i>=1){
            $loginCredentialCheck = false;
            $out .="Missing Username ";
            }
        }

        if(isset($_GET['pass'])){
        $pass=$_GET['pass'];
        $query="SELECT password FROM LoginCredentials WHERE UserID='$userId';";
        $result=mysqli_query($dbc, $query) or die("Query Failed");
        $passDb=mysqli_fetch_assoc($result);
        }
        
        else{
            $loginCredentialCheck = false;
            if($j>=1){
            $out .="Missing Password ";
            }
        }
        
        if ($loginCredentialCheck==true){
            if(($userId === $userIdDb['userid']) && ($pass === $passDb['password'])){
                $out .="You are now logged in";  
                $loginStatus=true; 
            }else{       
                $out .="Incorrect Credentials. Please try again or contact administrator for further assistance";
            }
        }

        ?>

        <?php
        if($loginStatus!=true){
        ?>
        <h1>Login Form</h1>
        <h3>Please input your login credentials.</h3>

        <form action= 'login.php' method='GET'>
            UserID: <input type='text' name='userId'><br>
            Password: <input type='password' name='pass'><br>
            <input type='submit' value='Login'><br>
        </form> 
        
        <?php 
        } 
        ?>

        <?php
            echo "<h2>$out</h2>";
        ?>

        
    </body>
 
 </html>



