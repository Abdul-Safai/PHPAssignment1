<?php
  session_start();
  require("database.php");
<!DOCTYPE html>
<html>
  <head>
      <title> Student Directory - Home</title>
      <link rel="stylesheet" type="text/css" href="css/main.css" />
 
  </head>
  <body>
      <?php include ("header.php"); ?>

      <main>
        <h2>Students List</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Program</th>
              
            </tr>
        </table>
      </main> 

      <?php include ("footer.php"); ?>
  </body>
</html>
