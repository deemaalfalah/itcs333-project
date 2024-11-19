<?php
if (isset($_POST['sbtn'])) {
    $modalMessage = "";
    $showModal = false;
    try {
        require("connection.php");
        $db->beginTransaction();

        // Collect form data
        $department = $_POST['department'];
        $room_num = $_POST['room_number'];
        $capacity = $_POST['capacity'];

        // Set checkbox values to 1 if checked, otherwise set to 0
        $lab = isset($_POST['lab']) ? 1 : 0;
        $smartboard = isset($_POST['smartboard']) ? 1 : 0;
        $datashow = isset($_POST['datashow']) ? 1 : 0;

        // Check if room number already exists
        $stmt = $db->prepare("SELECT COUNT(*) FROM rooms WHERE room_num = :room_num");
        $stmt->bindParam(":room_num", $room_num);
        $stmt->execute();
        $roomExists = $stmt->fetchColumn();

        if ($roomExists > 0) {
            // Room number already exists
            $modalMessage = "Error: The room number $room_num already exists. Please choose another room number.";
            $showModal = true;
        } else {
            // Insert new room if room number is unique
            $sql = "INSERT INTO rooms (department, room_num, capacity, lab, smartboard, datashow)
                    VALUES (:department, :room_num, :capacity, :lab, :smartboard, :datashow)";
            $stmt = $db->prepare($sql);

            // Bind the parameters correctly
            $stmt->bindParam(":department", $department);
            $stmt->bindParam(":room_num", $room_num);
            $stmt->bindParam(":capacity", $capacity);
            $stmt->bindParam(":lab", $lab);
            $stmt->bindParam(":smartboard", $smartboard);
            $stmt->bindParam(":datashow", $datashow);

            // Execute the query
            $stmt->execute();
            $db->commit();

            // Success message
            $modalMessage = "Room added successfully!";
            $showModal = true;
        }
    } catch (PDOException $e) {
        $db->rollBack();
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
    <link rel="stylesheet" href="styles/add_room.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-content">
            <h2>Account</h2>
            <ul>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1>Add Room</h1>
            <div class="search-container">
                <form method="GET" action="#">
                    <input type="text" placeholder="Search..." name="search" required>
                    <button type="submit">Search</button>
                </form>
            </div>
        </header>

        <!-- Form Container -->
        <div class="form-container">
            <fieldset>
                <legend>Room Details</legend>
                <form method="POST">
                    <!-- Department Selection -->
                    <div class="form-group">
                        <label>Department:</label>
                        <div class="radio-group">
                            <label><input type="radio" name="department" value="IS" required> IS</label>
                            <label><input type="radio" name="department" value="CS"> CS</label>
                            <label><input type="radio" name="department" value="CE"> CE</label>
                        </div>
                    </div>

                    <!-- Room Number -->
                    <div class="form-group">
                        <label for="room_number">Room Number:</label>
                        <input type="text" id="room_number" name="room_number" required>
                    </div>

                    <!-- Capacity -->
                    <div class="form-group">
                        <label for="capacity">Capacity:</label>
                        <input type="number" id="capacity" name="capacity" min="1" placeholder="Enter capacity" required>
                    </div>

                    <!-- Features -->
                    <div class="form-group">
                        <label>Features:</label>
                        <div class="checkbox-group">
                            <label><input type="checkbox" name="lab" value="Lab"> Lab</label>
                            <label><input type="checkbox" name="smartboard" value="Smartboard"> Smartboard</label>
                            <label><input type="checkbox" name="datashow" value="Datashow"> Datashow</label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" name="sbtn" class="submit-btn">Add Room</button>
                </form>
            </fieldset>
        </div>
    </div>

    <!-- Modal for Success/Error Message (Optional) -->
    <?php if ($showModal): ?>
        <div class="modal">
            <div class="modal-content">
                <span class="close" onclick="document.querySelector('.modal').style.display='none'">&times;</span>
                <p><?php echo $modalMessage; ?></p>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
