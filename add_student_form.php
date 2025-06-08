<?php
require_once('database.php');

// Get all types from the types table for the dropdown
$query = 'SELECT * FROM types ORDER BY studentType';
$statement = $db->prepare($query);
$statement->execute();
$types = $statement->fetchAll();
$statement->closeCursor();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Directory - Add Student</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
<?php include("header.php"); ?>

<main>
    <h2>Add New Student</h2>

    <form action="add_student.php" method="post" id="add_student_form" enctype="multipart/form-data">

        <div id="data">
            <label>First Name:</label>
            <input type="text" name="first_name" required /><br />

            <label>Last Name:</label>
            <input type="text" name="last_name" required /><br />

            <label>Email:</label>
            <input type="email" name="email" required /><br />

            <label>Phone Number:</label>
            <input type="text" name="phone_number" required /><br />

            <label>Program:</label>
            <input type="text" name="program" required /><br />

            <label>Upload Image:</label>
            <input type="file" name="file1" /><br />

            <label>Type:</label>
            <select name="type_id" required>
                <option value="">--Select Type--</option>
                <?php foreach ($types as $type): ?>
                    <option value="<?php echo $type['typeID']; ?>">
                        <?php echo htmlspecialchars($type['studentType']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br />
        </div>

        <div id="buttons">
            <label>&nbsp;</label>
            <input type="submit" value="Add Student" /><br />
        </div>

    </form>

    <p><a href="index.php">View Students List</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
