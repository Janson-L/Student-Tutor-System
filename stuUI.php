<?php
    SESSION_START(); 
    //$_SESSION['loginUser']
    //$_SESSION['userClass']
    $out="";
    $out .= $_SESSION['loginUser'];
    $out .= $_SESSION['userClass'];
?>

<html>
    <head>
        <style>
        </style>
    </head>
    <body>
        <h2>Student UI</h2>
        <form action='studentSessionSignUp.php' method='GET'>
            <button type='Submit'>Sign up for tutoring session</button>
        </form>
    </body>
</html>

<?php
    echo "<h2> $out</h2>";
?>
