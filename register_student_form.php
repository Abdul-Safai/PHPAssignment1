<?php
require_once("database.php");

$user_name = filter_input(INPUT_POST, "user_name");
$password = filter_input(INPUT_POST, "password");
$email_address = filter_input(INPUT_POST, "email_address", FILTER_VALIDATE_EMAIL);

if ($user_name && $password && $email_address) {
    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the database
    $query = "INSERT INTO registrations (userName, password, emailAddress, failed_attempts, last_failed_login)
              VALUES (:userName, :password, :emailAddress, 0, NULL)";
    $statement = $db->prepare($query);
    $statement->bindValue(":userName", $user_name);
    $statement->bindValue(":password", $hashed_password);
    $statement->bindValue(":emailAddress", $email_address);
    $statement->execute();
    $statement->closeCursor();

    // Redirect to login page
    header("Location: login_form.php");
    exit();
} else {
    $error = "Invalid registration data. Please check all fields.";
    include("error.php");
    exit();
}
?>
