<?php
    SESSION_START(); 
    //$_SESSION['loginUser']
    //$_SESSION['userClass']
    $out="";
    $out .= $_SESSION['loginUser'];
    $out .= $_SESSION['userClass'];
    echo "<h6> $out<h6>";
?>
    <html>
        <head>
            <style>
            </style>
        </head>
        <body>
            <h2>Student UI</h2>
            <form action='stuSessionSignUp.php' method='GET'>
                <button type='Submit'>Sign up for tutoring session</button> <br>
            </form>
            <form action='stuShowRegisteredSession.php' method='GET'>
                <button type='Submit'>Show registered tutoring session</button> <br>
            </form>
            <form action='logOut.php' method='GET'>
                <button type='Submit'>Log Out</button> <br>
            </form>
        </body>
    </html>
