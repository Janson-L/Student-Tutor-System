<?php 
    SESSION_START(); 
    //$_SESSION['loginUser']
    //$_SESSION['userClass']
    $out="";
    $out .= $_SESSION['loginUser'];
    $out .= $_SESSION['userClass'];
    echo "<h6> $out<h6>";

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
    }

    if(isset($_GET['endTimeH'])){
        $endTime.=$_GET['endTimeH'];
    }

    if(isset($_GET['endTimeM'])){
        $endTime.=$_GET['endTimeM'];
    }

    if($formFilledCorrectly==true)
    {
        $startTime.="00";
        $endTime.="00";
        //$query ="INSERT INTO tutoringSession (topic,subjectCode,tutorID,date, startTime, endTime) VALUES('$topic','$matrixNo','$phoneNo','$pass',0,1);";
        //$result = mysqli_query($dbc, $query) or die("Query Failed $query");
        $out .="Start Time= $startTime ";
        $out .="End Time= $endTime ";
        $out .="Date= $date ";

        echo "$out";
    }


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
                <?php for($i=0;$i<=9;$i++) echo "<option value=0".$i.">0".$i." </option>";?>
                <?php for($i=10;$i<=59;$i++) echo "<option value=".$i.">".$i." </option>";?>
            </select>
            <br>
            <label>End Time: </label>
            <select name='endTimeH' required>
                <?php for($i=0;$i<=9;$i++) echo "<option value=0".$i.">0".$i." </option>";?>
                <?php for($i=10;$i<=23;$i++) echo "<option value=".$i.">".$i." </option>";?>
            </select>
            <select name='endTimeM' required>
                <?php for($i=0;$i<=9;$i++) echo "<option value=0".$i.">0".$i." </option>";?>
                <?php for($i=10;$i<=59;$i++) echo "<option value=".$i.">".$i." </option>";?>
            </select><br>
            
            <input type='submit' value='Add new tutoring session'><br>
        </form>
        
        <?php
            
        ?>
    </body>
</html>