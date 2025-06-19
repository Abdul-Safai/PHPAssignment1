<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
<?php include("header.php"); ?>

<main>
    <h2>Forgot Password</h2>

    <?php
    if (isset($_SESSION['message'])) {
        echo '<p style="color:green;">' . htmlspecialchars($_SESSION['message']) . '</p>';
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red;">' . htmlspecialchars($_SESSION['error']) . '</p>';
        unset($_SESSION['error']);
    }
    ?>

    <form action="send_reset_link.php" method="post">
        <label>Email Address:</label>
        <input type="email" name="email" required />
        <button type="submit">Send Reset Link</button>
    </form>

    <p><a href="login_form.php">Back to Login</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
