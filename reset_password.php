<?php
require_once("database.php");

$token = $_GET['token'] ?? '';

if (!$token) {
    die("Missing reset token.");
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
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
</head>
<body>
  <h2>Reset Your Password</h2>
  <form action="update_password.php" method="post">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>" />
    <label>New Password:</label>
    <input type="password" name="password" required />
    <label>Confirm Password:</label>
    <input type="password" name="confirm_password" required />
    <button type="submit">Update Password</button>
  </form>
</body>
</html>
