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
    $lab = isset($_POST['lab']) ? 1 : 0;  // If checkbox is checked, set to 1, otherwise 0
    $smartboard = isset($_POST['smartboard']) ? 1 : 0;
    $datashow = isset($_POST['datashow']) ? 1 : 0;

    // Handle file upload for image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Get image data
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        // Set default image or handle error
        $imageData = null;
    }

    // Check if the room number already exists
    $checkSql = "SELECT * FROM rooms WHERE room_num = :room_num";
    $checkStmt = $db->prepare($checkSql);
    $checkStmt->bindParam(':room_num', $room_num);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        // Room number already exists, show error message
        $error = "The room number $room_num already exists. Please choose a different number.";
    } else {
        // Prepare SQL query to insert room data
        $sql = "INSERT INTO rooms (department, room_num, capacity, lab, smartboard, datashow, image)
                VALUES (:department, :room_num, :capacity, :lab, :smartboard, :datashow, :image)";

        // Prepare the statement
        $stmt = $db->prepare($sql);

        // Bind parameters to the SQL query
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':room_num', $room_num);
        $stmt->bindParam(':capacity', $capacity);
        $stmt->bindParam(':lab', $lab);
        $stmt->bindParam(':smartboard', $smartboard);
        $stmt->bindParam(':datashow', $datashow);
        $stmt->bindParam(':image', $imageData, PDO::PARAM_LOB);  // Store the image as BLOB

        // Execute the query
        if ($stmt->execute()) {
            // If the room is added successfully, set $showModal to true to display the success modal
            $showModal = true;
        } else {
            // Handle error (you can display an error message here)
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
     <!-- Sidebar -->
<div class="sidebar">
        <h2>Admin Panel</h2>
        <div class="elem-inside">
        <ul>
            <li><a onclick="location.href='dashboard.php'">Dashboard</a></li>
            <li><a onclick="location.href='add_room.php'">Add Room</a></li>
            <li><a onclick="location.href='manage_rooms.php'">Manage Rooms</a></li>
            <li><a href="#">My Account</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="logout.php" class="logout-button">Logout</a>
            </li>
        </ul>
        </div>
    </div>

    <div class="container">
        <!-- Form for adding a new room -->
        <form method="POST" enctype="multipart/form-data">
            <label for="department">Department:</label>
            <input type="text" id="department" name="department" required><br>

            <label for="room_num">Room Number:</label>
            <input type="number" id="room_num" name="room_num" required><br>

            <label for="capacity">Capacity:</label>
            <input type="number" id="capacity" name="capacity" required><br>

            <label for="lab">Lab Room:</label>
            <input type="checkbox" id="lab" name="lab"><br>

            <label for="smartboard">Smartboard:</label>
            <input type="checkbox" id="smartboard" name="smartboard"><br>

            <label for="datashow">Datashow:</label>
            <input type="checkbox" id="datashow" name="datashow"><br>

            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image"><br>

            <button type="submit" name="submit">Add Room</button>
        </form>

        <!-- Success Modal -->
        <?php if ($showModal): ?>
            <div id="successModal" style="display: block;">
                <h2>Room Added Successfully!</h2>
                <button onclick="document.getElementById('successModal').style.display='none'">Close</button>
            </div>
        <?php endif; ?>

        <!-- Error Modal -->
        <?php if (isset($error) && $error): ?>
            <div id="errorModal" style="display: block;">
                <h2><?php echo $error; ?></h2>
                <button onclick="document.getElementById('errorModal').style.display='none'">Close</button>
            </div>
        <?php endif; ?>

    </div>
<!-- Right Section: Search Bar and Map -->
<div class="right-section">
            <div class="search-bar-container">
                <input type="text" class="search-bar" placeholder="Search rooms...">
            </div>
            <div class="map-container">
                <h2>College Map</h2>
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3876.1008081624653!2d50.51035831527856!3d26.047947889870854!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e49f5f78c4e8597%3A0x58cd2cb91f59d49a!2z2KfYr9mF2YHZiNmK2LfYr9mK2KkgQ29sbGVnZSBvZiBJbmZvcm1hdGlvbiBUZWNobm9sb2d5IC0gU2FraGlyIFNhY2FyaXMgQ2FtcHVz!5e0!3m2!1sen!2sus!4v1713940198768!5m2!1sen!2sus"
                    width="100%" 
                    height="400" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
</body>
</html>
