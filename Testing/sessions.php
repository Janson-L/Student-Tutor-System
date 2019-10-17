<?php
SESSION_START(); //Must have on every page to use the session

$_SESSION['sessionName']="Ken"; //array

echo "User name: {$_SESSION['sessionName']}";

//                Array need to be in {} to be output

?>