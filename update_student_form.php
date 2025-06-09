<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('database.php');

// get the student id from POST (or GET if you prefer)
$student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);

// fetch student data
$query = 'SELECT * FROM students WHERE ID = :student_id';
$statement = $db->prepare($query);
$statement->bindValue(':student_id', $student_id, PDO::PARAM_INT);
$statement->execute();
$student = $statement->fetch();
$statement->closeCursor();

// fetch all types for dropdown
$queryTypes = 'SELECT * FROM types ORDER BY studentType';
$statementTypes = $db->prepare($queryTypes);
$statementTypes->execute();
$types = $statementTypes->fetchAll();
$statementTypes->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Directory - Update Student</title>
    <link rel="stylesheet" href="css/main.css" />
</head>
<body>
<?php include("header.php"); ?>

<main>
    <h2>Update Student</h2>

    <form action="update_student.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['ID']); ?>" />

        <label>First Name:</label>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($student['firstName']); ?>" /><br />

        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($student['lastName']); ?>" /><br />

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" /><br />

        <label>Phone Number:</label>
        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($student['phoneNumber']); ?>" /><br />

        <label>Program:</label>
        <input type="text" name="program" value="<?php echo htmlspecialchars($student['program']); ?>" /><br />

        <?php if (!empty($student['imageName'])): ?>
            <label>Current Image:</label>
            <img src="images/<?php echo htmlspecialchars($student['imageName']); ?>" alt="Student Photo" style="height: 100px;"><br />
        <?php endif; ?>

        <label>Update Image:</label>
        <input type="file" name="file1" /><br />

        <label>Type:</label>
        <select name="type_id">
            <?php foreach ($types as $type): ?>
                <option value="<?php echo $type['typeID']; ?>" <?php if ($type['typeID'] == $student['typeID']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($type['studentType']); ?>
                </option>
            <?php endforeach; ?>
        </select><br />

        <input type="submit" value="Update Student" />
    </form>

    <p><a href="index.php">View Students List</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
