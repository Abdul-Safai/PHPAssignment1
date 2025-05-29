
<!DOCTYPE html>
<html>
  <head>
      <title> Student Directory - Add Student</title>
      <link rel="stylesheet" type="text/css" href="css/main.css" />
 
  </head>
  <body>
      <?php include ("header.php"); ?>

      <main>
        <h2>Add Student</h2>

        <form action="add_student.php" method="post" id="add_student_form"
            enctype="multipart/form-data">

            <div id="data">
                
                <label>First Name:</label>
                <input type="text" name="first_name" /><br />

                <label>Last Name:</label>
                <input type="text" name="last_name" /><br />

                <label>Email:</label>
                <input type="text" name="email" /><br />

                <label>Phone Number:</label>
                <input type="text" name="phone_number" /><br />

                <label>Program:</label>
                <input type="text" name="program" /><br />

            </div>
            
            <div id="buttons">
                
                <label>&nbsp;</label>
                <input type="submit" value="Save Student" /><br />
            
            </div>

        </form>

        <p><a href="index.php">View Students List</a></P>

      </main> 

      <?php include ("footer.php"); ?>
  </body>
</html>
