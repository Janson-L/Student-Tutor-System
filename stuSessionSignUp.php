<?php
    SESSION_START(); 
    //$_SESSION['loginUser']
    //$_SESSION['userClass']
    $out="";
    $out .= $_SESSION['loginUser'];
    $out .= $_SESSION['userClass'];
    echo "<h6> $out<h6>";
?>

<?php
    $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established"); 
    
?>
