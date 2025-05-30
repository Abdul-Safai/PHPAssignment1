<?php
  session_start();
  ?>
<!DOCTYPE html>
<html>
  <head>
      <title> Student Directory - Update Confirmation</title>
      <link rel="stylesheet" type="text/css" href="css/main.css" />
 
  </head>
  <body>
      <?php include ("header.php"); ?>

      <main>
        <h2>Student Update Confirmation</h2>
        <p>
            Thank you, <?php echo $_SESSION["fullName"]; ?>. Your student details have been successfully updated.

        </p>

        <p><a href="index.php">Back to Home</a></p>
      </main> 

      <?php include ("footer.php"); ?>
  </body>
</html>
