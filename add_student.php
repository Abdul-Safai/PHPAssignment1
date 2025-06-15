<?php

session_start();

// Get inputs
$first_name = filter_input(INPUT_POST, 'first_name');
$last_name = filter_input(INPUT_POST, 'last_name');
$email = filter_input(INPUT_POST, 'email');
$phone_number = filter_input(INPUT_POST, 'phone_number');
$program = filter_input(INPUT_POST, 'program');
$type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);

require_once('database.php');
require_once('image_util.php');

$base_dir = 'images/';

// Basic validation
if (!$first_name || !$last_name || !$email || !$phone_number || !$program || !$type_id) {
    error_redirect("Please fill all required fields.");
}

// Check duplicate email
$query = 'SELECT COUNT(*) FROM students WHERE email = :email';
$stmt = $db->prepare($query);
$stmt->bindValue(':email', $email);
$stmt->execute();
$count = $stmt->fetchColumn();
$stmt->closeCursor();

if ($count > 0) {
    error_redirect("Duplicate email address. Try a different one.");
}

// Default image name (placeholder)
$image_name = 'placeholder_100.png';

// Handle file upload and debug
if (isset($_FILES['image'])) {
    $fileError = $_FILES['image']['error'];
    if ($fileError === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $original_name = basename($_FILES['image']['name']);

        // Check upload folder writable
        if (!is_writable($base_dir)) {
            error_redirect("Upload directory not writable.");
        }

        // Clean filename and add timestamp to avoid overwrite
        $safe_name = time() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "", $original_name);
        $upload_path = $base_dir . $safe_name;

        if (move_uploaded_file($tmp_name, $upload_path)) {
            // Call process_image function
            process_image($base_dir, $safe_name);

            // Use thumbnail for DB
            $dot_pos = strrpos($safe_name, '.');
            $thumb_name = substr($safe_name, 0, $dot_pos) . '_100' . substr($safe_name, $dot_pos);

            // Check if thumbnail was created
            if (file_exists($base_dir . $thumb_name)) {
                $image_name = $thumb_name;
            } else {
                error_redirect("Thumbnail image was not created.");
            }
        } else {
            error_redirect("Failed to move uploaded file.");
        }
    } elseif ($fileError !== UPLOAD_ERR_NO_FILE) {
        // If any error other than no file selected
        error_redirect("Upload error code: $fileError");
    }
} else {
    // No file uploaded, will use placeholder
}

// Check placeholder images, create if missing
if (!file_exists($base_dir . 'placeholder_100.png') || !file_exists($base_dir . 'placeholder_400.png')) {
    if (!file_exists($base_dir . 'placeholder.png')) {
        error_redirect("Placeholder image missing.");
    }
    process_image($base_dir, 'placeholder.png');
}
// Insert into DB
$insert_sql = 'INSERT INTO students (firstName, lastName, email, phoneNumber, program, typeID, imageName)
               VALUES (:firstName, :lastName, :email, :phoneNumber, :program, :typeID, :imageName)';
$insert_stmt = $db->prepare($insert_sql);
$insert_stmt->bindValue(':firstName', $first_name);
$insert_stmt->bindValue(':lastName', $last_name);
$insert_stmt->bindValue(':email', $email);
$insert_stmt->bindValue(':phoneNumber', $phone_number);
$insert_stmt->bindValue(':program', $program);
$insert_stmt->bindValue(':typeID', $type_id);
$insert_stmt->bindValue(':imageName', $image_name);
$insert_stmt->execute();
$insert_stmt->closeCursor();

$_SESSION["fullName"] = $first_name . " " . $last_name;
header("Location: confirmation.php");
exit();
