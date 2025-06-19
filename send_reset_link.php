<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("database.php");
require_once("message.php");

$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);

if (!$email) {
    $_SESSION['error'] = "Please enter a valid email address.";
    header("Location: forgot_password.php");
    exit();
}

$query = "SELECT * FROM registrations WHERE emailAddress = :email";
$statement = $db->prepare($query);
$statement->bindValue(":email", $email);
$statement->execute();
$user = $statement->fetch();
$statement->closeCursor();

if ($user) {
    $token = bin2hex(random_bytes(20));
    $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

    $update = "UPDATE registrations SET reset_token = :token, reset_expires = :expires WHERE emailAddress = :email";
    $stmt = $db->prepare($update);
    $stmt->bindValue(":token", $token);
    $stmt->bindValue(":expires", $expiry);
    $stmt->bindValue(":email", $email);
    $stmt->execute();
    $stmt->closeCursor();

    $reset_link = "http://localhost/PHPAssignment1/reset_password.php?token=$token";

    $subject = "Reset Your Password";
    $body = "Click the link below to reset your password:\n\n$reset_link\n\nLink expires in 1 hour.";

    try {
        send_email(
            $to_address = $email,
            $to_name = $user['userName'], // Corrected to userName (case sensitive)
            $from_address = "myclass.practice@gmail.com",
            $from_name = "Student Directory",
            $subject,
            $body,
            $is_body_html = false
        );
        $_SESSION['message'] = "If that email exists in our system, a reset link has been sent.";
    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to send email: " . $e->getMessage();
    }
} else {
    // For security, show the same message even if email not found
    $_SESSION['message'] = "If that email exists in our system, a reset link has been sent.";
}

header("Location: forgot_password.php");
exit();
