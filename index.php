<?php
  session_start();
  require("database.php");
  $queryStudents = 'SELECT * FROM students';
  $statement1 = $db->prepare($queryStudents);
  $statement1->execute();
  $students = $statement1->fetchALl();

  $statement1->closeCursor();

  ?>
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
                <th>&nbsp;</th> <!--for edit button-->
                 <th>&nbsp;</th> <!--for delete button-->
              
            </tr>
            <?php foreach ($students as $student): ?>
              <tr>  
                <td><?php echo $student['ID']; ?></td>
                <td><?php echo $student['firstName']; ?></td>
                <td><?php echo $student['lastName']; ?></td>
                <td><?php echo $student['email']; ?></td>
                <td><?php echo $student['phoneNumber']; ?></td>
                <td><?php echo $student['program']; ?></td>
                <td>
                    <form action="update_student_form.php" method="post">
                        <input type="hidden" name="student_id"
                            value="<?php echo $student['ID']; ?>" />
                        <input type="submit" value="Update" />
                    </form>

                </td> <!-- for edit button -->
                <td>
                    <form action="delete_student.php" method="post">
                        <input type="hidden" name="student_id"
                            value="<?php echo $student['ID']; ?>" />
                        <input type="submit" value="Delete" />
                    </form>

                </td> <!-- for delete button -->

            </tr>

            <?php endforeach; ?>
        </table>
        <p><a href="add_student_form.php">Add Student</a></p>
      </main> 

      <?php include ("footer.php"); ?>
  </body>
</html>
