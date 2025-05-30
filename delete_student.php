<?php
    require_once('database.php');
    //get the data from the form 
    $student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);

    // code to delete student from database
    //validate inputs
    if ($student_id != false)
    {
        // delete the student from the database
        $query = 'DELETE FROM students WHERE ID = :student_id';

            $statement = $db->prepare($query);
            $statement->bindValue(':student_id', $student_id);
        
            $statement->execute();
            $statement->closeCursor(); 
    }
    // reload index page
    $url = "index.php";
    header("Location: " . $url);
    die(); 

?>