<?php 
    SESSION_START(); 
    //$_SESSION['loginUser']
    //$_SESSION['userClass']
    $_SESSION['loginUser']="TUT1"; //testing TUT1
    $_SESSION['userClass']="TUT";
    $out .= $_SESSION['loginUser'];
    $out .= $_SESSION['userClass'];
    echo "<h6> $out<h6>";
    $out="";

    $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established"); //Register and change to a non root user
    $formFilledCorrectly=true;
    $topic="";
    $subjectCode="";
    $date="";
    $startTime="";
    $endTime=""; 

    if (isset($_GET['topic'])){
        $topic=$_GET['topic'];
    }

    if(isset($_GET['subjectCode'])){
        $subjectCode=$_GET['subjectCode'];
    }

    if(isset($_GET['date'])){
        $date=$_GET['date'];
    }

    if(isset($_GET['startTimeH'])){
        $startTime.=$_GET['startTimeH'];
    }

    if(isset($_GET['startTimeM'])){
        $startTime.=$_GET['startTimeM'];
        $startTime.="00";
    }

    if(isset($_GET['endTimeH'])){
        $endTime.=$_GET['endTimeH'];
    }

    if(isset($_GET['endTimeM'])){
        $endTime.=$_GET['endTimeM'];
        $endTime.="00";
    }

    $currentDate=date('Y-m-d', time());
    $currentTime= date('His', time());
    $currentTime+="070000"; //to convert it to Malaysia Time
    
    if($date < $currentDate) //to check the selected date with current date
    {
        $formFilledCorrectly=false;
        $out.="Date selected is not a valid date. Please select a date that is either today or later than current date. <br> ";
    }
    else{
        if($startTime < $currentTime)
        {
            $formFilledCorrectly=false;
            $out.="Time selected is not a valid time. Please select time that is later than current time. <br> ";
        }
        else if(($startTime-$currentTime)<="015900")
        {
            $formFilledCorrectly=false;
            $out.="Tutors are not allowed to add tutor session that starts in less than 2 hours from the current time. <br> ";
        }
    }   

    if($formFilledCorrectly==true)
    {
        //$query ="INSERT INTO tutoringSession (topic,subjectCode,tutorID,date, startTime, endTime) VALUES('$topic','$matrixNo','$phoneNo','$pass',0,1);";
        //$result = mysqli_query($dbc, $query) or die("Query Failed $query");
        

        
    }

    $out .="Start Time= $startTime <br>";
    $out .="End Time= $endTime <br>";
    $out .="Date=$date  <br>";
    $out .="Current Date=$currentDate <br>";
    $out .="Current Time=$currentTime <br>";

?>

<html>
    <head>
    </head>
    <body>
        <form action='tutNewSession.php' method='GET'>
            <label>Topic: </label><input type='text' name='topic' value='<?php echo $topic ?>' required size="30"><br>
            <label>Subject Code(if applicable): </label><input type='text' name="subjectCode" value='<?php echo $subjectCode ?>'></textarea> <br>
            <label>Date: </label><input type='date' name='date' value='<?php echo $date ?>' required ><br>
            <label>Start Time:</label> 
            <select name='startTimeH' required>
                <?php for($i=0;$i<=9;$i++) echo "<option value=0".$i.">0".$i." </option>";?>
                <?php for($i=10;$i<=23;$i++) echo "<option value=".$i.">".$i." </option>";?>
            </select>
            <select name='startTimeM' required>
                <?php for($i=0;$i<=9;$i+=5) echo "<option value=0".$i.">0".$i." </option>";?>
                <?php for($i=10;$i<=59;$i+=5) echo "<option value=".$i.">".$i." </option>";?>
            </select>
            <br>
            <label>End Time: </label>
            <select name='endTimeH' required>
                <?php for($i=0;$i<=9;$i++) echo "<option value=0".$i.">0".$i." </option>";?>
                <?php for($i=10;$i<=23;$i++) echo "<option value=".$i.">".$i." </option>";?>
            </select>
            <select name='endTimeM' required>
                <?php for($i=0;$i<=9;$i+=5) echo "<option value=0".$i.">0".$i." </option>";?>
                <?php for($i=10;$i<=59;$i+=5) echo "<option value=".$i.">".$i." </option>";?>
            </select><br>
            
            <input type='submit' value='Add new tutoring session'><br>
        </form>
    </body>
</html>

<?php
    echo "$out";
?>