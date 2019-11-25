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
            <h2>Tutor UI</h2>
            <form action='tutNewSession.php' method='POST'>
                <button type='Submit'>Add new tutoring session</button> <br>
            </form>
            <form action='tutShowSession.php' method='POST'>
                <button type='Submit'>Show tutoring session</button> <br>
            </form>
            <form action='logOut.php' method='POST'>
                <button type='Submit'>Log Out</button> <br>
            </form>
        </body>
    </html>
