<?php
session_start();

require_once('database.php');

// Trim input
$user_name = trim(filter_input(INPUT_POST, 'user_name'));
$password = trim(filter_input(INPUT_POST, 'password'));

$query = "SELECT * FROM registrations WHERE LOWER(userName) = LOWER(:userName)";
$statement = $db->prepare($query);
$statement->bindValue(':userName', $user_name);
$statement->execute();
$row = $statement->fetch();
$statement->closeCursor();

if ($row) {
    // Time comparison
    $now = new DateTime();
    $last_failed = $row['last_failed_login'] ? new DateTime($row['last_failed_login']) : null;
    $interval = $last_failed ? $now->getTimestamp() - $last_failed->getTimestamp() : 9999;

    // Reset failed attempts if lockout expired
    if ($row['failed_attempts'] >= 3 && $interval >= 300) {
        $query = "UPDATE registrations SET failed_attempts = 0, last_failed_login = NULL WHERE userName = :userName";
        $statement = $db->prepare($query);
        $statement->bindValue(':userName', $user_name);
        $statement->execute();
        $statement->closeCursor();

        $row['failed_attempts'] = 0;
        $row['last_failed_login'] = null;
    }

    // Still in lockout window
    if ($row['failed_attempts'] >= 3 && $interval < 300) {
        $remaining = 300 - $interval;
        $_SESSION['login_error'] = "Account locked. Try again in " . ceil($remaining / 60) . " minute(s).";
        $_SESSION['lockout_until'] = time() + $remaining;
        header("Location: login_form.php");
        exit();
    }

    // ✅ Check password
    if (password_verify($password, $row['password'])) {
        // Reset failed attempts
        $query = "UPDATE registrations SET failed_attempts = 0, last_failed_login = NULL WHERE userName = :userName";
        $statement = $db->prepare($query);
        $statement->bindValue(':userName', $user_name);
        $statement->execute();
        $statement->closeCursor();

        unset($_SESSION['login_error']);
        unset($_SESSION['lockout_until']);

        $_SESSION['isLoggedIn'] = true;
        $_SESSION['userName'] = $row['userName'];

        header("Location: login_confirmation.php");
        exit();
    } else {
        // Increment failed attempts
        $failed_attempts = $row['failed_attempts'] + 1;
        $query = "UPDATE registrations SET failed_attempts = :failed_attempts, last_failed_login = NOW() WHERE userName = :userName";
        $statement = $db->prepare($query);
        $statement->bindValue(':failed_attempts', $failed_attempts, PDO::PARAM_INT);
        $statement->bindValue(':userName', $user_name);
        $statement->execute();
        $statement->closeCursor();

        if ($failed_attempts >= 3) {
            $_SESSION['login_error'] = "Too many failed attempts. Please wait 5 minutes before trying again.";
            $_SESSION['lockout_until'] = time() + 300;
        } else {
            $_SESSION['login_error'] = "Invalid username or password.";
        }

        header("Location: login_form.php");
        exit();
    }
} else {
    $_SESSION['login_error'] = "Invalid username or password.";
    header("Location: login_form.php");
    exit();
}
?>
