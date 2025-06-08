<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Directory - Student Added</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Student Added</h2>
        <p>
            Thank you, <?php echo htmlspecialchars($_SESSION["fullName"] ?? "Student"); ?>. Your student record has been successfully added.
        </p>
        <p><a href="index.php">Back to Home</a></p>
    </main>

    <?php include("footer.php"); ?>
</body>
</html>
