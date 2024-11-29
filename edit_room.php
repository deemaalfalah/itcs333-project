<?php
require('connection.php');

// Check if room_num is provided in the URL
if (isset($_GET['room_num'])) {
    $room_num = htmlspecialchars($_GET['room_num']);

    // Fetch room data from the database
    $sql = "SELECT * FROM rooms WHERE room_num = :room_num";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':room_num', $room_num);
    $stmt->execute();
    $room = $stmt->fetch();

    // If room doesn't exist, redirect to manage rooms page
    if (!$room) {
        header('Location: manage_rooms.php');
        exit();
    }
}

// Handle the form submission (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_num = htmlspecialchars($_POST['room_num']);
    $department = htmlspecialchars($_POST['department']);
    $capacity = htmlspecialchars($_POST['capacity']);
    $lab = isset($_POST['lab']) ? 1 : 0;
    $smartboard = isset($_POST['smartboard']) ? 1 : 0;
    $datashow = isset($_POST['datashow']) ? 1 : 0;

    // Update room details in the database
    $stmt = $db->prepare("UPDATE rooms SET department = :department, capacity = :capacity, lab = :lab, smartboard = :smartboard, datashow = :datashow WHERE room_num = :room_num");
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':capacity', $capacity);
    $stmt->bindParam(':lab', $lab);
    $stmt->bindParam(':smartboard', $smartboard);
    $stmt->bindParam(':datashow', $datashow);
    $stmt->bindParam(':room_num', $room_num);

    // Execute the update query and redirect
    if ($stmt->execute()) {
        echo "<script>alert('Room updated successfully.'); window.location.href = 'dashboard-admin.php';</script>";
        exit(); // Ensure the rest of the script doesn't run after the redirect
    } else {
        echo "<script>alert('Failed to update room.'); window.history.back();</script>";
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
    

    <!-- Main Content -->
    <div class="container">
        <div class="main-content">
            <h3>Edit Room: <?= htmlspecialchars($room['room_num']) ?></h3>
            <form action="edit_room.php?room_num=<?= $room['room_num'] ?>" method="POST">
                <input type="hidden" name="room_num" value="<?= $room['room_num'] ?>">

                <label for="department">Department:</label>
                <input type="text" id="department" name="department" value="<?= htmlspecialchars($room['department']) ?>" required>

                <label for="capacity">Capacity:</label>
                <input type="number" id="capacity" name="capacity" value="<?= htmlspecialchars($room['capacity']) ?>" required>

                <label for="lab">Lab:</label>
                <input type="checkbox" id="lab" name="lab" <?= $room['lab'] ? 'checked' : '' ?>>

                <label for="smartboard">Smartboard:</label>
                <input type="checkbox" id="smartboard" name="smartboard" <?= $room['smartboard'] ? 'checked' : '' ?>>

                <label for="datashow">Datashow:</label>
                <input type="checkbox" id="datashow" name="datashow" <?= $room['datashow'] ? 'checked' : '' ?>>

                <button type="submit">Update Room</button>
            </form>
        </div>
    </div>
</body>
</html>
