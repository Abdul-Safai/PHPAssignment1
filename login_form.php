<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Students Directory - Login</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
<?php include("header.php"); ?>

<main>
    <h2>Login</h2>

    <?php
    $lockout_active = false;
    // Show lockout message if session lockout_until is set and not expired
    if (isset($_SESSION['lockout_until'])) {
        $remaining = $_SESSION['lockout_until'] - time();
        if ($remaining > 0) {
            echo '<p style="color:red;">Account locked. Try again in ' . ceil($remaining / 60) . ' minute(s).</p>';
            $lockout_active = true;
        } else {
            // Lockout expired, clear session variable
            unset($_SESSION['lockout_until']);
        }
    }

    if (isset($_SESSION['login_error'])) {
        echo '<p style="color:red;">' . htmlspecialchars($_SESSION['login_error']) . '</p>';
        unset($_SESSION['login_error']);
    }

    if (isset($_SESSION['message'])) {
        echo '<p style="color:green;">' . htmlspecialchars($_SESSION['message']) . '</p>';
        unset($_SESSION['message']);
    }
    ?>

    <form action="login.php" method="post" id="login_form">
        <div id="data">
            <label>Username:</label>
            <input type="text" name="user_name" required <?php if ($lockout_active) echo 'disabled'; ?> /><br />

            <label>Password:</label>
            <input type="password" name="password" required <?php if ($lockout_active) echo 'disabled'; ?> /><br />
        </div>

        <div id="buttons">
            <label>&nbsp;</label>
            <input type="submit" value="Login" <?php if ($lockout_active) echo 'disabled'; ?> /><br />
        </div>
    </form>

    <p><a href="register_student_form.php">Register</a></p>
    <p><a href="forgot_password.php">Forgot Password?</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
