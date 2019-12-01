<?php
SESSION_START();
if (preg_match("/\AADM/", @$_SESSION['loginUser'])) {
?>

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

?>
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
<body>
    <br /><br />
        <div style="width:900px;">
        <div id="piechart" style="width:900px;height:500px;"></div>
    <br />
</body>


<table border='1'>
    <tr>
        <th>No. of tutoring sessions listed:</th>
        <td><?php echo "$noOfTutoringSession"; ?></td>
    </tr>
</table><br>

<table border='1'>
    <tr>
        <th>Total duration of tutoring sessions:</th>
        <td><?php echo "$durationd"; ?></td>
    </tr>
</table>

<?php
} else {
    echo "<h3>You don't have the privilege to view this page. You will be logged out and redirected to the login page in 5 seconds.<br> Please login with the correct account.</h3>";
    header("Refresh:5;URL=logOut.php");
    die();
}
?>