<?php
require_once("database.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Directory - Register</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Register</h2>
        <form action="register_student.php" method="post">
            <div id="data">
                <label>Username:</label>
                <input type="text" name="user_name" required /><br />

                <label>Password:</label>
                <input type="password" name="password" required /><br />

                <label>Email Address:</label>
                <input type="email" name="email_address" required /><br />
            </div>

            <div id="buttons">
                <input type="submit" value="Register" /><br />
            </div>
        </form>
    </main>

    <?php include("footer.php"); ?>
</body>
</html>
