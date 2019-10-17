<?php

$dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established");

$query="SELECT userid FROM LoginCredentials WHERE UserID='STU001';";

$result=mysqli_query($dbc, $query) or die("Query Failed");

$row=mysqli_fetch_assoc($result);

if($row['userid']==='STU001'){
    echo "Fetch is Successful";
}else{
    echo"Fetch is successful but it's in varchar and not string";
}


/*
//fetch one row per call
while($row=mysqli_fetch_assoc($result)){
//print the object
//print_r($row);


echo"{$row['userid']}<br>";
}

*/
mysqli_close($dbc);
?>