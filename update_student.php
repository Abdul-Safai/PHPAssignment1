<?php
session_start();

 $student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);

//get data from the form
$first_name = filter_input(INPUT_POST, 'first_name');
//altrnative
//$first_name = $_POST['first_name];
$last_name = filter_input(INPUT_POST, 'last_name');
$email = filter_input(INPUT_POST, 'email');
$phone_number = filter_input(INPUT_POST, 'phone_number');
$program = filter_input(INPUT_POST, 'program');

require_once('database.php');
 $queryStudents = 'SELECT * FROM students';
  $statement1 = $db->prepare($queryStudents);
  $statement1->execute();
  $students = $statement1->fetchALl();

  $statement1->closeCursor();

  foreach ($students as $student)
  {
        if ($email == $student["email"] && $student_id != $student["ID"])
        {
            $_SESSION["add_error"] = "Invalid data, Dulicate Email Address. Try again.";
            $url = "error.php";
            header("Location: ". $url);
            die();
        }
  }

  if ($first_name == null || $last_name == null ||
     $email == null || $phone_number == null || $program == null)
     {
        $_SESSION["add_error"] = "Invalid student data, Check all fields and try again.";
            $url = "error.php";
            header("Location: ". $url);
            die();
     }
     else
    {

            require_once('database.php');

            //Update the student to the database

            $query = 'UPDATE students
               SET firstName = :firstName, 
               lastName = :lastName,
               email = :email,
               phoneNumber = :phoneNumber,
               program = :program
               WHERE ID = : ID';

            $statement = $db->prepare($query);
            $statement->bindValue(':ID', $student_id);
            $statement->bindValue(':firstName', $first_name);
            $statement->bindValue(':lastName', $last_name);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':phoneNumber', $phone_number);
            $statement->bindValue(':program', $program);

            $statement->execute();
            $statement->closeCursor(); 
    }
$_SESSION["fullName"] = $first_name . " " . $last_name;

//redirect to confirmation page

$url = "update_confirmation.php";
header("Location: " . $url);
die(); // releases add_contact.php from memory

?>