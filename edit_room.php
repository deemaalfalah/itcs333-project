<?php
// Include database connection
require("connection.php");

if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];
    try {
        // Fetch room details based on room_id
        $sql = "SELECT * FROM rooms WHERE room_id = :room_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':room_id', $room_id, PDO::PARAM_INT);
        $stmt->execute();
        $room = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch room details

        if (!$room) {
            // Room not found, redirect or show error
            echo "<script>alert('Room not found!'); window.location='manage_rooms.php';</script>";
            exit;
        }

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // Redirect if room_id is not set
    echo "<script>alert('Room ID is missing!'); window.location='manage_rooms.php';</script>";
    exit;
}

if (isset($_POST['sbtn'])) {
    $department = $_POST['department'];
    $room_num = $_POST['room_number'];
    $capacity = $_POST['capacity'];
    $lab = isset($_POST['lab']) ? 1 : 0;
    $smartboard = isset($_POST['smartboard']) ? 1 : 0;
    $datashow = isset($_POST['datashow']) ? 1 : 0;

    try {
        // Update room details in the database
        $sql = "UPDATE rooms SET department = :department, room_num = :room_num, capacity = :capacity, 
                lab = :lab, smartboard = :smartboard, datashow = :datashow WHERE room_id = :room_id";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':room_num', $room_num);
        $stmt->bindParam(':capacity', $capacity);
        $stmt->bindParam(':lab', $lab);
        $stmt->bindParam(':smartboard', $smartboard);
        $stmt->bindParam(':datashow', $datashow);
        $stmt->bindParam(':room_id', $room_id);

        // Execute the update
        $stmt->execute();

        // Success message
        echo "<script>alert('Room updated successfully!'); window.location='manage_rooms.php';</script>";

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
    <title>Edit Room</title>
    <link rel="stylesheet" href="styles/edit_room.css">
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

    <!-- Main Content Container -->
    <div class="container">
        

        <!-- Form Container -->
        <div class="form-container">
            <fieldset>
                <legend>Edit Room</legend>
                <form method="POST">
                    <!-- Department Selection -->
                    <div class="form-group">
                        <label>Department:</label>
                        <div class="radio-group">
                            <label><input type="radio" name="department" value="IS" <?= ($room['department'] == 'IS') ? 'checked' : ''; ?> required> IS</label>
                            <label><input type="radio" name="department" value="CS" <?= ($room['department'] == 'CS') ? 'checked' : ''; ?>> CS</label>
                            <label><input type="radio" name="department" value="CE" <?= ($room['department'] == 'CE') ? 'checked' : ''; ?>> CE</label>
                        </div>
                    </div>

                    <!-- Room Number -->
                    <div class="form-group">
                        <label>Room Number:</label>
                        <input type="text" name="room_number" value="<?= htmlspecialchars($room['room_num']) ?>" required>
                    </div>

                    <!-- Capacity -->
                    <div class="form-group">
                        <label>Capacity:</label>
                        <input type="number" name="capacity" value="<?= htmlspecialchars($room['capacity']) ?>" required>
                    </div>

                    <!-- Facilities (Checkboxes) -->
                    <div class="form-group">
                        <label>Facilities:</label>
                        <label><input type="checkbox" name="lab" <?= $room['lab'] ? 'checked' : ''; ?>> Lab</label>
                        <label><input type="checkbox" name="smartboard" <?= $room['smartboard'] ? 'checked' : ''; ?>> Smartboard</label>
                        <label><input type="checkbox" name="datashow" <?= $room['datashow'] ? 'checked' : ''; ?>> Datashow</label>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">
                        <button type="submit" name="sbtn">Update Room</button>
                    </div>
                </form>
            </fieldset>
        </div>
    </div>
</body>
</html>

