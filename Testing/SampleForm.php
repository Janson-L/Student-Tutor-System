<html>
    <head>
        <style>
        </style>
    </head>
    <body>
        <h1>UTeM Student Tutor System</h1>
        <h3>Registration From</h3>
        <h5>Please key in the necessary details</h5>

        <form action='SampleForm.php' method='GET'>
        <label>User Type</label>
        <select name='userType' value='<?php echo $userType ?>'>
        <option value='student'>Student</option>
        <option value='tutor'>Tutor</option>
        </select><br>

        <label>Name: </label><input type='text' name='userName' value='<?php echo $userName ?>'><br>
        <label>Matrix No: </label><input type='text' name='matrixNo' value='<?php echo $matrixNo ?>'><br>
        <label>Password: </label><input type='password' name='pass'><br>
        <label>Retype Password: </label><input type='password' name='pass'><br>



        </form>

    </body>
</html>