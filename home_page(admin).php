<?php 
// Include database connection
require("connection.php");

// Handle delete request
if (isset($_GET['delete_room'])) {
    $room_id = $_GET['delete_room'];
    try {
        $sql = "DELETE FROM rooms WHERE room_id = :room_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':room_id', $room_id);
        $stmt->execute();
        echo "<script>alert('Room deleted successfully!');</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}

// Fetch all rooms
$rooms = [];
try {
    $searchQuery = '';
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchQuery = $_GET['search'];
        $sql = "SELECT * FROM rooms WHERE room_num LIKE :searchQuery";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':searchQuery', '%' . $searchQuery . '%');
    } else {
        $sql = "SELECT * FROM rooms";
        $stmt = $db->prepare($sql);
    }
    $stmt->execute();
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Link the external CSS file -->
    <link rel="stylesheet" href="styles/home_page(admin).css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Manage Rooms</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Manage Rooms</h1>
            <div class="search-container">
                <form method="GET" action="home_page(admin).php">
                    <input type="text" name="search" placeholder="Search room number..." value="<?= htmlspecialchars($searchQuery) ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>

        <div class="actions">
            <button onclick="location.href='add_room.php'">Add Room</button>
        </div>

        <!-- Room List -->
        <table class="room-list">
            <thead>
                <tr>
                    <th>Room Number</th>
                    <th>Department</th>
                    <th>Capacity</th>
                    <th>Lab</th>
                    <th>Smartboard</th>
                    <th>Datashow</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($rooms) > 0): ?>
                    <?php foreach ($rooms as $room): ?>
                        <tr>
                            <td><?= htmlspecialchars($room['room_num']) ?></td>
                            <td><?= htmlspecialchars($room['department']) ?></td>
                            <td><?= htmlspecialchars($room['capacity']) ?></td>
                            <td><?= $room['lab'] ? 'Yes' : 'No' ?></td>
                            <td><?= $room['smartboard'] ? 'Yes' : 'No' ?></td>
                            <td><?= $room['datashow'] ? 'Yes' : 'No' ?></td>
                            <td>
                                <button class="edit-btn" onclick="location.href='edit_room.php?room_id=<?= $room['room_id'] ?>'">Edit</button>
                                <button class="delete-btn" onclick="deleteRoom(<?= $room['room_id'] ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No rooms found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        // JavaScript function to handle room deletion
        function deleteRoom(roomId) {
            if (confirm("Are you sure you want to delete room " + roomId + "?")) {
                // Redirect to the current page with delete_room query parameter
                location.href = "home_page(admin).php?delete_room=" + roomId;
            }
        }
    </script>
</body>
</html>
