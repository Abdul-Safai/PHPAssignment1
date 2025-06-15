<?php
session_start();
require_once("database.php");

// Get student ID from POST
$student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
if (!$student_id) {
    header("Location: index.php");
    exit;
}

// Fetch student info
$query = 'SELECT * FROM students WHERE ID = :student_id';
$statement = $db->prepare($query);
$statement->bindValue(':student_id', $student_id);
$statement->execute();
$student = $statement->fetch();
$statement->closeCursor();

if (!$student) {
    echo "Student not found.";
    exit;
}

// Convert _100 image to _400 version
$imageName = $student['imageName'];
$dotPosition = strrpos($imageName, '.');
$baseName = substr($imageName, 0, $dotPosition);
$extension = substr($imageName, $dotPosition);

if (str_ends_with($baseName, '_100')) {
    $baseName = substr($baseName, 0, -4);
}
$imageName_400 = $baseName . '_400' . $extension;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Details</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
    <?php include("header.php"); ?>

    <div class="container">
        <h2>Student Details</h2>

        <img class="student-image" src="<?php echo htmlspecialchars('./images/' . $imageName_400); ?>" 
             alt="<?php echo htmlspecialchars($student['firstName'] . ' ' . $student['lastName']); ?>" />

        <div class="student-info">
            <p><strong>ID:</strong> <?php echo htmlspecialchars($student['ID']); ?></p>
            <p><strong>First Name:</strong> <?php echo htmlspecialchars($student['firstName']); ?></p>
            <p><strong>Last Name:</strong> <?php echo htmlspecialchars($student['lastName']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($student['phoneNumber']); ?></p>
            <p><strong>Program:</strong> <?php echo htmlspecialchars($student['program']); ?></p>
        </div>

        <a class="back-link" href="index.php">← Back to Student List</a>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
