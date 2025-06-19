<?php
session_start();
require_once("database.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$token = $_POST["token"];
$password = $_POST["password"];
$confirm = $_POST["confirm_password"];

if ($password !== $confirm) {
    die("Passwords do not match.");
}

$query = "SELECT * FROM registrations WHERE reset_token = :token AND reset_expires > NOW()";
$statement = $db->prepare($query);
$statement->bindValue(":token", $token);
$statement->execute();
$user = $statement->fetch();
$statement->closeCursor();

if (!$user) {
    die("Invalid or expired token.");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$update = "UPDATE registrations SET password = :password, reset_token = NULL, reset_expires = NULL WHERE registrationID = :registrationID";
$stmt = $db->prepare($update);
$stmt->bindValue(":password", $hash);
$stmt->bindValue(":registrationID", $user["registrationID"]);
$stmt->execute();
$stmt->closeCursor();

echo "Password updated successfully! <a href='login_form.php'>Login</a>";
?>
