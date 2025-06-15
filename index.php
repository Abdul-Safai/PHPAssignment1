<?php
session_start();

if (!isset($_SESSION["isLoggedIn"])) {
    header("Location: login_form.php");
    die();
}

require_once("database.php");

// Optionally join tables if you have related info (e.g., programs table) — currently just selecting students
$queryStudents = 'SELECT * FROM students';
$statement1 = $db->prepare($queryStudents);
$statement1->execute();
$students = $statement1->fetchAll();
$statement1->closeCursor();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Directory - Home</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Student List</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Program</th>
                <th>Photo</th>
                <th>&nbsp;</th> <!-- for update -->
                <th>&nbsp;</th> <!-- for delete -->
                <th>&nbsp;</th> <!-- for view details -->
            </tr>

            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['ID']); ?></td>
                    <td><?php echo htmlspecialchars($student['firstName']); ?></td>
                    <td><?php echo htmlspecialchars($student['lastName']); ?></td>
                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                    <td><?php echo htmlspecialchars($student['phoneNumber']); ?></td>
                    <td><?php echo htmlspecialchars($student['program']); ?></td>
                    <td>
                        <img src="<?php echo htmlspecialchars('./images/' . $student['imageName']); ?>" 
                             alt="<?php echo htmlspecialchars($student['firstName'] . ' ' . $student['lastName']); ?>" />
                    </td>
                    <td>
                        <form action="update_student_form.php" method="post">
                            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['ID']); ?>" />
                            <input type="submit" value="Update" />
                        </form>
                    </td>
                    <td>
                        <form action="delete_student.php" method="post">
                            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['ID']); ?>" />
                            <input type="submit" value="Delete" />
                        </form>
                    </td>
                    <td>
                        <form action="student_details.php" method="post">
                            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['ID']); ?>" />
                            <input type="submit" value="View Details" />
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p><a href="add_student_form.php">Add Student</a></p>
        <p><a href="logout.php">Logout</a></p>
    </main>

    <?php include("footer.php"); ?>
</body>
</html>
