<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
$first_name = filter_input(INPUT_POST, 'first_name');
$last_name = filter_input(INPUT_POST, 'last_name');
$email = filter_input(INPUT_POST, 'email');
$phone_number = filter_input(INPUT_POST, 'phone_number');
$program = filter_input(INPUT_POST, 'program');
$type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);
$image = $_FILES['file1'];

require_once('database.php');

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

require_once('image_util.php');

// Get current image name from database
$query = 'SELECT imageName FROM students WHERE ID = :student_id';
$statement = $db->prepare($query);
$statement->bindValue(':student_id', $student_id, PDO::PARAM_INT);
$statement->execute();
$current = $statement->fetch();
$current_image_name = $current['imageName'] ?? null;
$statement->closeCursor();

$image_name = $current_image_name;

// Handle new image upload
if ($image && $image['error'] === UPLOAD_ERR_OK) {
    $base_dir = 'images/';

    // Delete old images if exist
    if ($current_image_name) {
        $dot = strrpos($current_image_name, '_100.');
        if ($dot !== false) {
            $original_name = substr($current_image_name, 0, $dot) . substr($current_image_name, $dot + 4);
            $original = $base_dir . $original_name;
            $img_100 = $base_dir . $current_image_name;
            $img_400 = $base_dir . substr($current_image_name, 0, $dot) . '_400' . substr($current_image_name, $dot + 4);

            if (file_exists($original)) unlink($original);
            if (file_exists($img_100)) unlink($img_100);
            if (file_exists($img_400)) unlink($img_400);
        }
    }

    // Upload and process new image
    $original_filename = basename($image['name']);
    $upload_path = $base_dir . $original_filename;
    move_uploaded_file($image['tmp_name'], $upload_path);
    process_image($base_dir, $original_filename);

    $dot_position = strrpos($original_filename, '.');
    $name_without_ext = substr($original_filename, 0, $dot_position);
    $extension = substr($original_filename, $dot_position);
    $image_name = $name_without_ext . '_100' . $extension;
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
