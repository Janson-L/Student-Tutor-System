<?php 
    SESSION_START(); 
    //$_SESSION['loginUser']
    //$_SESSION['userClass']
?>
<body>
    <h3>New Tutor Session Form</h3>
</body>
<?php
    $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established"); //Register and change to a non root user
    $formFilledCorrectly=false;
    $topic="";
    $subjectCode="";
    $date="";
    $startTime="";
    $endTime=""; 
    $sessionID="";
    $currentDate="";
    $currentTime="";
    $location="";
    $out="";


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

    if(isset($_GET['location'])){
        $location=$_GET['location'];
    }

    if(isset($_GET['startTimeH'])&&isset($_GET['startTimeM'])&&isset($_GET['endTimeH'])&&isset($_GET['endTimeM']))
    {   
        $formFilledCorrectly=true;
        $currentDate=date('Y-m-d', time());
        $currentTime= date('His', time());
        $currentTime+="070000"; //to convert it to Malaysia Time
        
        if($date < $currentDate) //to check the selected date with current date
        {
            $formFilledCorrectly=false;
            $out.="Date selected is not a valid date. Please select a date that is either today or later than current date. <br> ";
        }
        else{
            if($date==$currentDate){
                if($startTime <= $currentTime)
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

            if($startTime>=$endTime)
            {
                $formFilledCorrectly=false;
                $out.="Please select the correct end time. Duration of the session must be at least 5 minutes. <br> ";
            }
        }   
    }

    if($formFilledCorrectly==true)
    {
        $query="INSERT INTO tutoringSession (topic,subjectCode,tutorID,date,startTime,endTime,location) VALUES('$topic','$subjectCode','{$_SESSION['loginUser']}','$date','$startTime','$endTime','$location');";
        $result=mysqli_query($dbc, $query) or die("Query Failed $query");

        $query="SELECT ID FROM tutoringSession ORDER BY ID DESC LIMIT 1;";
                    $result=mysqli_query($dbc, $query) or die("Query Failed");
                    $IdDb=mysqli_fetch_assoc($result);
                    $IdDb=$IdDb['ID'];
                    $sessionID.="SES";

                    if($IdDb==null){
                       $id=1;
                    }
                    else{
                        $id=$IdDb;
                    }
                    $sessionID.=$id;
                    
                    $query="UPDATE tutoringSession SET sessionID='$sessionID' WHERE ID=$IdDb;";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query"); 

        $out.="Tutoring session successfully added. SessionID for this tutoring session is $sessionID <br>";
        $topic="";
        $subjectCode="";
        $date="";
        $startTime="";
        $endTime=""; 
        $sessionID="";
        $currentDate="";
        $currentTime="";
        $location=""; 
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
            <label>Location:</label><input type='text' name='location' value='<?php echo $location ?>' required size="20"><br>
            
            <input type='submit' value='Add new tutoring session'><br>
        </form>

        <form action='tutUI.php' method='GET'>
            <button type='Submit'>Back to Tutor UI page</button> <br>
        </form>
    </body>
</html>

<?php
    echo "$out";
?>