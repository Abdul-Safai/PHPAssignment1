<?php
session_start();

require_once('database.php');
require_once('image_util.php');

// Debug: check if file was uploaded under 'file1'
if (!isset($_FILES['file1'])) {
    die('Debug: No image uploaded ($_FILES["file1"] is missing)');
}

// Get student ID
$student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);

// Get other form data
$first_name = filter_input(INPUT_POST, 'first_name');
$last_name = filter_input(INPUT_POST, 'last_name');
$email = filter_input(INPUT_POST, 'email');
$phone_number = filter_input(INPUT_POST, 'phone_number');
$program = filter_input(INPUT_POST, 'program');
$type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);

$image = $_FILES['file1'];

$base_dir = 'images/';
$image_name = ''; // Default empty, we'll get old image if no new upload

// Check for duplicate email (excluding current student)
$queryStudents = 'SELECT * FROM students WHERE email = :email AND ID != :student_id';
$statement1 = $db->prepare($queryStudents);
$statement1->bindValue(':email', $email);
$statement1->bindValue(':student_id', $student_id, PDO::PARAM_INT);
$statement1->execute();
$duplicate = $statement1->fetch();
$statement1->closeCursor();

if ($duplicate) {
    $_SESSION["add_error"] = "Invalid data, Duplicate Email Address. Try again.";
    header("Location: error.php");
    exit();
}

// Validate required fields (not empty or null)
if (empty($first_name) || empty($last_name) || empty($email) || empty($phone_number) || empty($program) || $type_id === false || $type_id === null) {
    $_SESSION["add_error"] = "Invalid student data, Check all fields and try again.";
    header("Location: error.php");
    exit();
}

// Get existing imageName for student (to keep if no new upload)
$queryGetImage = 'SELECT imageName FROM students WHERE ID = :student_id';
$statement2 = $db->prepare($queryGetImage);
$statement2->bindValue(':student_id', $student_id, PDO::PARAM_INT);
$statement2->execute();
$oldImage = $statement2->fetchColumn();
$statement2->closeCursor();

$image_name = $oldImage ?: 'placeholder_100.png';

// If new image is uploaded
if ($image && $image['error'] === UPLOAD_ERR_OK) {
    $original_filename = basename($image['name']);
    $upload_path = $base_dir . $original_filename;

    if (!move_uploaded_file($image['tmp_name'], $upload_path)) {
        die('Debug: Failed to move uploaded file in update_student.php');
    }

    process_image($base_dir, $original_filename);

    $dot_pos = strrpos($original_filename, '.');
    $image_name = substr($original_filename, 0, $dot_pos) . '_100' . substr($original_filename, $dot_pos);
}

// Update student record
$query = 'UPDATE students
    SET firstName = :firstName,
        lastName = :lastName,
        email = :email,
        phoneNumber = :phoneNumber,
        program = :program,
        typeID = :typeID,
        imageName = :imageName
    WHERE ID = :student_id';

$statement = $db->prepare($query);
$statement->bindValue(':firstName', $first_name);
$statement->bindValue(':lastName', $last_name);
$statement->bindValue(':email', $email);
$statement->bindValue(':phoneNumber', $phone_number);
$statement->bindValue(':program', $program);
$statement->bindValue(':typeID', $type_id, PDO::PARAM_INT);
$statement->bindValue(':imageName', $image_name);
$statement->bindValue(':student_id', $student_id, PDO::PARAM_INT);
$statement->execute();
$statement->closeCursor();

$_SESSION["fullName"] = $first_name . " " . $last_name;

header("Location: update_confirmation.php");
exit();
?>
