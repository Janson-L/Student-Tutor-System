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
    </head>
    <body>
       
        <?php
            $dbc=mysqli_connect('localhost','root','','utem_student_tutor_system') or die("Connection not established"); //Register and change to a non root user
            $formCheck=true;
            $topic="";
            $description="";
            $date="";
            $startTime="";
            $endTime="";
            
        ?>

        <form action='tutNewSession.php' method='GET'>
        <label>Topic: </label><input type='text' name='topic' value='<?php echo $topic ?>' required size="30"><br>
        <label>Description: </label><textarea name="description" rows="5" cols="25" size="50" value='<?php echo $topic ?>'></textarea> <br>
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
        
        <input type='submit' value='Submit Form'><br>
        </form>
        
        <?php
            if($formCheck==false){
                echo "<h5>$out</h5>";
            }

            if($successRegistration==true)
            {
                if($userType=="student"){
                    $query ="INSERT INTO Student (ID,StudentID,Name,MatrixNo,PhoneNo) VALUES('$id','$userID','$userName','$matrixNo','$phoneNo');";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                    $query ="INSERT INTO LoginCredentials (UserID,Password,LoginAttempt,AccountStatus) VALUES('$userID','$pass',0,1);";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                }
                else if($userType=="tutor"){
                    $query ="INSERT INTO Tutor (ID,TutorID,Name,MatrixNo,PhoneNo) VALUES('$id','$userID','$userName','$matrixNo','$phoneNo');";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                    $query ="INSERT INTO LoginCredentials (UserID,Password,LoginAttempt,AccountStatus) VALUES('$userID','$pass',0,1);";
                    $result = mysqli_query($dbc, $query) or die("Query Failed $query");
                }
                
                echo "<h5>$out</h5>";
            }
        ?>
    </body>
</html>