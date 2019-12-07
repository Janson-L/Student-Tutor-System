<head>
    <title>USTS- System Usage Statistics </title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/outStyle.css">
</head>

<?php
SESSION_START();
if (preg_match("/\AADM/", @$_SESSION['loginUser'])) {
?>
    <ul>
        <li><a href="admUI.php">Home</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Manage Users</a>
            <div class="dropdown-content">
            <a href="admAddUser.php">Add User</a>
            <a href="admManageUsers.php">Update and Delete Users</a>
        </div>
        </li>
        <li><a href="admManageTutorSession.php">Manage Tutor Session</a></li>
        <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Manage Personal Information</a>
                <div class="dropdown-content">
                    <a href="editPersonalInfo.php">Edit Personal Information</a>
                    <a href="resetPersonalPassword.php">Reset Password</a>
                </div>
            </li>
        <li class="active"><a href="admSystemUsageStatistics.php">System Usage Statistics</a></li>
        <li style="float:right"><a href="logOut.php">Log Out</a></li>
    </ul>



<?php
    $dbc = mysqli_connect('localhost', 'root', '', 'utem_student_tutor_system') or die("Connection not established");
    $duration=0;
    function format_time_output($t)
        {
            return sprintf("%02d%s%02d%s", floor($t / 3600), ' hour(s) ', ($t / 60) % 60,' minute(s)');
        }

    $query="SELECT COUNT(studentID) AS noOfStudent FROM student;";
    $result=mysqli_query($dbc,$query) or die("Query Failed $query");
    $row = mysqli_fetch_assoc($result);
    $noOfStudent=$row['noOfStudent'];

    $query="SELECT COUNT(tutorID) AS noOfTutor FROM tutor;";
    $result=mysqli_query($dbc,$query) or die("Query Failed $query");
    $row = mysqli_fetch_assoc($result);
    $noOfTutor=$row['noOfTutor'];

    $query="SELECT COUNT(sessionID) AS tutoringSessionNo FROM tutoringSession;";
    $result=mysqli_query($dbc,$query) or die("Query Failed $query");
    $row = mysqli_fetch_assoc($result);
    $noOfTutoringSession=$row['tutoringSessionNo'];

    $query="SELECT startTime, endTime FROM tutoringSession;";
    $result=mysqli_query($dbc,$query) or die("Query Failed $query");
    
    while($row = mysqli_fetch_assoc($result))
    {
        $duration +=(strtotime($row['endTime']) - strtotime($row['startTime']));
    }
    $durationd=format_time_output($duration);

    mysqli_close($dbc);
?>

<h2>System Usage Statistics</h2>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>  
           <script type="text/javascript">  
           google.charts.load('current', {'packages':['corechart']});  
           google.charts.setOnLoadCallback(drawChart);  
           function drawChart()  
           {  
                var data = google.visualization.arrayToDataTable([  
                          ['User Type', 'Number'],  
                          ['Student',<?php echo $noOfStudent; ?>],
                          ['Tutor',<?php echo $noOfTutor; ?>]  
                ]);  
                var options = {  
                      title: 'Percentage of User Type',  
                     };  
                var chart = new google.visualization.PieChart(document.getElementById('piechart'));  
                chart.draw(data, options);  
           }  
           </script>  

<div class="container">
        <div style="width:900px;">
        <div id="piechart" style="width:900px;height:500px;"></div>

<table>
    <tr>
        <th>No. of tutoring sessions listed:</th>
        <td><?php echo "$noOfTutoringSession"; ?></td>
        
    
    </tr>
    <tr>
    <th>Total duration of tutoring sessions:</th>
        <td><?php echo "$durationd"; ?></td>
    </tr>
</table>
</div>

<?php
}else { 
    ?>
    <br>
    <div class="prompt">You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</div>
    <?php
    header("Refresh:5;URL=logOut.php");
    die();
}
?>