<?php
session_start();

require_once 'image_util.php'; //the process_image function

$image_dir = 'images';
$image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

if (isset($_FILES['file1']))
{
    $filename = $_FILES['file1']['name'];

    if (!empty($filename))
    {
        $source = $_FILES['file1']['tmp_name'];

        $target = $image_dir_path . DIRECTORY_SEPARATOR . $filename;

        move_uploaded_file($source, $target);

        //create the '400' and '100' versions of the image
        process_image($image_dir_path, $filename);
    }
}
//get data from the form
$first_name = filter_input(INPUT_POST, 'first_name');
//altrnative
//$first_name = $_POST['first_name];
$last_name = filter_input(INPUT_POST, 'last_name');
$email = filter_input(INPUT_POST, 'email');
$phone_number = filter_input(INPUT_POST, 'phone_number');
$program = filter_input(INPUT_POST, 'program');

$file_name = $_FILES['file1']['name'];

// Adjust the filename
   
$i = strrpos($filename, '.');
$image_name = substr($filename, 0, $i);
$ext = substr($filename, $i);

$image_name_100 =  $image_name . '_100' . $ext;

require_once('database.php');
 $queryStudents = 'SELECT * FROM students';
  $statement1 = $db->prepare($queryStudents);
  $statement1->execute();
  $students = $statement1->fetchALl();

  $statement1->closeCursor();

  foreach ($students as $student)
  {
        if ($email == $student["email"])
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

            //Add the contact to the database

            $query = 'INSERT INTO students
                (firstName, lastName, email, phoneNumber, program, imageName)
                VALUES
                (:firstName, :lastName, :email, :phoneNumber, :program, :imageName)';
            $statement = $db->prepare($query);
            $statement->bindValue(':firstName', $first_name);
            $statement->bindValue(':lastName', $last_name);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':phoneNumber', $phone_number);
            $statement->bindValue(':program', $program);
            $statement->bindValue(':imageName', $image_name_100);

            $statement->execute();
            $statement->closeCursor(); 
    }
$_SESSION["fullName"] = $first_name . " " . $last_name;

//redirect to confirmation page

$url = "confirmation.php";
header("Location: " . $url);
die(); // releases add_contact.php from memory

?>