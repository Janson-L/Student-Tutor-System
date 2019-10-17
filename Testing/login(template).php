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

        $out="";

        define('USERID','Ken');
        define('PASS','abc');
        
        if(isset($_GET['userId'])){
            $userId=$_GET['userId'];
        }
        else{
            $loginCredentialCheck = false;
            $out .="Missing Username ";
        }

        if(isset($_GET['pass'])){
        $pass=$_GET['pass'];
        }
        else{
            $loginCredentialCheck = false;
            $out .="Missing Password ";
        }
        
        if ($loginCredentialCheck==true){
            if(($userId == USERID) && ($pass == PASS)){
                $out .="You are now logged in";  
                $loginStatus=true; 
            }else{
                $out .="Incorrect Credentials. Contact administrator for further assistance";
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



