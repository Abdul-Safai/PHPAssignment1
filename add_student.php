<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once('database.php');
require_once('image_util.php');

$first_name = filter_input(INPUT_POST, 'first_name');
$last_name = filter_input(INPUT_POST, 'last_name');
$email = filter_input(INPUT_POST, 'email');
$phone_number = filter_input(INPUT_POST, 'phone_number');
$program = filter_input(INPUT_POST, 'program');
$type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);
$image = $_FILES['file1'] ?? null;

// Validate required fields
if ($first_name === null || $last_name === null || $email === null || $phone_number === null || $program === null || $type_id === null) {
    $_SESSION["add_error"] = "Invalid student data, Check all fields and try again.";
    header("Location: error.php");
    exit();
}

// Check for duplicate email
$query = 'SELECT COUNT(*) FROM students WHERE email = :email';
$statement = $db->prepare($query);
$statement->bindValue(':email', $email);
$statement->execute();
$emailCount = $statement->fetchColumn();
$statement->closeCursor();

if ($emailCount > 0) {
    $_SESSION["add_error"] = "Duplicate email address. Try again.";
    header("Location: error.php");
    exit();
}

// Handle image upload
$image_name = null;
if ($image && $image['error'] === UPLOAD_ERR_OK) {
    $base_dir = 'images/';
    $original_filename = basename($image['name']);
    $upload_path = $base_dir . $original_filename;
    move_uploaded_file($image['tmp_name'], $upload_path);
    process_image($base_dir, $original_filename);

    $dot_position = strrpos($original_filename, '.');
    $name_without_ext = substr($original_filename, 0, $dot_position);
    $extension = substr($original_filename, $dot_position);
    $image_name = $name_without_ext . '_100' . $extension;
}

// INSERT the new student
$query = 'INSERT INTO students
    (firstName, lastName, email, phoneNumber, program, typeID, imageName)
    VALUES
    (:firstName, :lastName, :email, :phoneNumber, :program, :typeID, :imageName)';

$statement = $db->prepare($query);
$statement->bindValue(':firstName', $first_name);
$statement->bindValue(':lastName', $last_name);
$statement->bindValue(':email', $email);
$statement->bindValue(':phoneNumber', $phone_number);
$statement->bindValue(':program', $program);
$statement->bindValue(':typeID', $type_id, PDO::PARAM_INT);
$statement->bindValue(':imageName', $image_name);
$statement->execute();
$statement->closeCursor();

$_SESSION["fullName"] = $first_name . " " . $last_name;
header("Location: confirmation.php");
exit();
?>
