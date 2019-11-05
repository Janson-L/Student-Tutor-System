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
            <form action='tutNewSession.php' method='GET'>
                <button type='Submit'>Open a tutor session</button> <br>
            </form>
            <form action='tutShowTimetable.php' method='GET'>
                <button type='Submit'>Show registered tutoring session</button> <br>
            </form>
            <form action='logOut.php' method='GET'>
                <button type='Submit'>Log Out</button> <br>
            </form>
        </body>
    </html>
