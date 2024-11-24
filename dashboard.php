<?php 
// Include database connection
require("connection.php");
    
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
    <link rel="stylesheet" href="styles/dashboard.css">
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
    <div class="main-content">
        <div class="header">
            <h1>Dashboard</h1>
            <div class="search-container">
                <form method="GET" action="Dashboard.php">
                    <input type="text" name="search" placeholder="Search room number..." value="<?= htmlspecialchars($searchQuery) ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
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
</body>
</html>
