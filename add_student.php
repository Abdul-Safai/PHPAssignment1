<?php
session_start();

// Get form data
$first_name = filter_input(INPUT_POST, 'first_name');
$last_name = filter_input(INPUT_POST, 'last_name');
$email = filter_input(INPUT_POST, 'email');
$phone_number = filter_input(INPUT_POST, 'phone_number');
$program = filter_input(INPUT_POST, 'program');
$type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);

// Correctly get uploaded image from $_FILES['image']
$image = isset($_FILES['image']);

require_once('database.php');
require_once('image_util.php');

$base_dir = 'images/';

// Check for duplicate email
$queryStudents = 'SELECT * FROM students';
$statement1 = $db->prepare($queryStudents);
$statement1->execute();
$contacts = $statement1->fetchAll();
$statement1->closeCursor();

foreach ($students as $student) {
    if ($email === $student["email"]) {
        $_SESSION["add_error"] = "Invalid data, Duplicate Email Address. Try again.";
        header("Location: error.php");
        die();
    }
}

// Validate input
if ($first_name == null || $last_name == null || $email == null ||
    $phone_number == null || $program == null || $type_id == null) {
    $_SESSION["add_error"] = "Invalid student data, Check all fields and try again.";
    header("Location: error.php");
    exit();
}

$image_name = '';  // default empty

// Handle image upload
if ($image && $image['error'] === UPLOAD_ERR_OK) {
    $original_filename = basename($image['name']);
    $upload_path = $base_dir . $original_filename;

    if (move_uploaded_file($image['tmp_name'], $upload_path)) {
        process_image($base_dir, $original_filename);

        $dot_pos = strrpos($original_filename, '.');
        $new_image_name = substr($original_filename, 0, $dot_pos) . '_100' . substr($original_filename, $dot_pos);
        $image_name = $name_100;
    }
} else {
    // Use placeholder if no image uploaded
    $placeholder = 'placeholder.png';
    $placeholder_100 = 'placeholder_100.png';
    $placeholder_400 = 'placeholder_400.png';

    if (!file_exists($base_dir . $placeholder_100) || !file_exists($base_dir . $placeholder_400)) {
        process_image($base_dir, $placeholder);
    }

    $image_name = $placeholder_100;
}

// Insert student into database
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
$statement->bindValue(':typeID', $type_id);
$statement->bindValue(':imageName', $image_name);
$statement->execute();
$statement->closeCursor();

$_SESSION["fullName"] = $first_name . " " . $last_name;
header("Location: confirmation.php");
exit();
?>
