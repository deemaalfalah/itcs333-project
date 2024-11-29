<?php
// Include the database connection
require('connection.php');

// Initialize the $showModal variable
$showModal = false;
$error = false;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Get form data
    $department = $_POST['department'];
    $room_num = $_POST['room_num'];
    $capacity = $_POST['capacity'];
    $lab = isset($_POST['lab']) ? 1 : 0;
    $smartboard = isset($_POST['smartboard']) ? 1 : 0;
    $datashow = isset($_POST['datashow']) ? 1 : 0;

    // Handle file upload for image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        $imageData = null;
    }

    // Check if the room number already exists
    $checkSql = "SELECT * FROM rooms WHERE room_num = :room_num";
    $checkStmt = $db->prepare($checkSql);
    $checkStmt->bindParam(':room_num', $room_num);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        $error = "The room number $room_num already exists. Please choose a different number.";
    } else {
        $sql = "INSERT INTO rooms (department, room_num, capacity, lab, smartboard, datashow, image)
                VALUES (:department, :room_num, :capacity, :lab, :smartboard, :datashow, :image)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':room_num', $room_num);
        $stmt->bindParam(':capacity', $capacity);
        $stmt->bindParam(':lab', $lab);
        $stmt->bindParam(':smartboard', $smartboard);
        $stmt->bindParam(':datashow', $datashow);
        $stmt->bindParam(':image', $imageData, PDO::PARAM_LOB);

        if ($stmt->execute()) {
            $showModal = true;
        } else {
            $error = "There was an error adding the room.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
    <link rel="stylesheet" href="styles/add_room.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="add_room.php">Add Room</a></li>
           
            <li><a href="#">My Account</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="logout.php" class="logout-button">Logout</a></li>
        </ul>
    </div>

    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <h2>Add Room</h2>

            <label for="department">Department:</label>
            <select id="department" name="department" required>
                <option value="" disabled selected>Select Department</option>
                <option value="CE">Computer Engineering (CE)</option>
                <option value="IS">Information Systems (IS)</option>
                <option value="CS">Computer Science (CS)</option>
            </select>

            <label for="room_num">Room Number:</label>
            <input type="number" id="room_num" name="room_num" required>

            <label for="capacity">Capacity:</label>
            <input type="number" id="capacity" name="capacity" required>

            <label for="lab">Lab Room:</label>
            <input type="checkbox" id="lab" name="lab">

            <label for="smartboard">Smartboard:</label>
            <input type="checkbox" id="smartboard" name="smartboard">

            <label for="datashow">Datashow:</label>
            <input type="checkbox" id="datashow" name="datashow">

            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image">

            <button type="submit" name="submit">Add Room</button>
        </form>

        <!-- Success Modal -->
        <?php if ($showModal): ?>
            <div class="modal success">
                <h2>Room Added Successfully!</h2>
                <button onclick="this.parentElement.style.display='none'">Close</button>
            </div>
        <?php endif; ?>

        <!-- Error Modal -->
        <?php if (isset($error) && $error): ?>
            <div class="modal error">
                <h2><?php echo $error; ?></h2>
                <button onclick="this.parentElement.style.display='none'">Close</button>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
