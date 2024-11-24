<?php
// Include database connection
require("connection.php");

// Initialize the variable with a default value
$showModal = false;

// Check if the room was added successfully
if (isset($_GET['added']) && $_GET['added'] == 'true') {
    $showModal = true;
}

// Handle the form submission to add a room
if (isset($_POST['add_room'])) {
    $room_num = $_POST['room_num'];
    $department = $_POST['department'];
    $capacity = $_POST['capacity'];
    $lab = isset($_POST['lab']) ? 1 : 0;
    $smartboard = isset($_POST['smartboard']) ? 1 : 0;
    $datashow = isset($_POST['datashow']) ? 1 : 0;

    try {
        // Insert the new room into the database
        $sql = "INSERT INTO rooms (room_num, department, capacity, lab, smartboard, datashow) 
                VALUES (:room_num, :department, :capacity, :lab, :smartboard, :datashow)";
        $stmt = $db->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':room_num', $room_num);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':capacity', $capacity);
        $stmt->bindParam(':lab', $lab);
        $stmt->bindParam(':smartboard', $smartboard);
        $stmt->bindParam(':datashow', $datashow);

        // Execute the statement
        $stmt->execute();

        // Redirect to the same page with an added=true query parameter to show the success modal
        echo "<script>window.location='add_room.php?added=true';</script>";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
    <!-- Link to External CSS -->
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
            <li><a href="#">Logout</a></li>
        </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h2>Add Room</h2>

        <!-- Form for adding room -->
        <form method="POST">
            <div class="form-group">
                <label for="room_num">Room Number:</label>
                <input type="text" name="room_num" id="room_num" required>
            </div>

            <div class="form-group">
                <label>Department:</label>
                <div class="radio-group">
                    <label><input type="radio" name="department" value="IS" required> IS</label>
                    <label><input type="radio" name="department" value="CS"> CS</label>
                    <label><input type="radio" name="department" value="CE"> CE</label>
                </div>
            </div>

            <div class="form-group">
                <label for="capacity">Capacity:</label>
                <input type="number" name="capacity" id="capacity" required min="1">
            </div>

            <div class="form-group">
                <label>Features:</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="lab" value="Lab"> Lab</label>
                    <label><input type="checkbox" name="smartboard" value="Smartboard"> Smartboard</label>
                    <label><input type="checkbox" name="datashow" value="Datashow"> Datashow</label>
                </div>
            </div>

            <button type="submit" name="add_room" class="submit-btn">Add Room</button>
        </form>
    </div>

    <!-- Modal for Success Message -->
    <?php if ($showModal): ?>
        <div class="modal">
            <div class="modal-content">
                <h3>Room Added Successfully!</h3>
                <button onclick="window.location.href='add_room.php'">OK</button>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>

