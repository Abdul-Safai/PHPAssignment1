<?php
    require_once('database.php');
    //get the data from the form 
    $student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);

     // select the student from the database
        $query = 'SELECT * FROM students WHERE ID = :student_id';

            $statement = $db->prepare($query);
            $statement->bindValue(':student_id', $student_id);
        
            $statement->execute();
            $student = $statement->fetch();
            $statement->closeCursor(); 


?>
<!DOCTYPE html>
<html>
  <head>
      <title> Student Directory - Update Student</title>
      <link rel="stylesheet" type="text/css" href="css/main.css" />
 
  </head>
  <body>
      <?php include ("header.php"); ?>

      <main>
        <h2>Update Student</h2>

        <form action="update_student.php" method="post" id="update_student_form"
            enctype="multipart/form-data">

            <div id="data">

                <input type="hidden" name= "student_id"
                value="<?php $student['ID']; ?>" />
                
                <label>First Name:</label>
                <input type="text" name="first_name" 
                    value="<?php echo $student['firstName'];?>" /><br />

                <label>Last Name:</label>
                <input type="text" name="last_name" 
                    value="<?php echo $student['lastName'];?>" /><br />

                <label>Email:</label>
                <input type="text" name="email"
                    value="<?php echo $student['email'];?>"  /><br />

                <label>Phone Number:</label>
                <input type="text" name="phone_number"
                    value="<?php echo $student['phoneNumber'];?>"  /><br />

                <label>Program:</label>
                <input type="text" name="program" 
                    value="<?php echo $student['program'];?>" /><br />

            </div>
            
            <div id="buttons">
                
                <label>&nbsp;</label>
                <input type="submit" value="Update Student" /><br />
            
            </div>

        </form>

        <p><a href="index.php">View Students List</a></P>

      </main> 

      <?php include ("footer.php"); ?>
  </body>
</html>
