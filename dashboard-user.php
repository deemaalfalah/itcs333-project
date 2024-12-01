
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor View</title>
    <link rel="stylesheet" href="styles/dashboard-user.css">
    <link rel="stylesheet" href="styles/footers.css">
    <script src="scripts/sidebar-toggle.js" defer></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar Section -->
        <div class="sidebar">
            <div class="profile">
                <img src="picture/University_of_Bahrain_logo.png" alt="Instructor Picture" class="profile-pic">
                <h2>User Name</h2>
            </div>
            <nav class="nav-menu">
                <ul>
                <li>
                    <li><a href="dashboard-user.php">Dashboard</a></li>
                    <li><a href="room-booking.php">Room Booking</a></li>
                    <li><a href="#">My Account</a></li>
                    <li><a href="contact-us.php">Contact US</a></li>
                    <li><a href="#">Setting</a></li>
                    <li><a href="logout.php" class="logout-button">Logout</a></li>
                </ul>
            </nav>
        </div>
        
<?php
// Database connection
require("connection.php");

// Start session and verify the logged-in user
session_start();
if (!isset($_SESSION['currentUser'])) {
    die("User not logged in. Please log in first.");
}
$logged_in_user_id = $_SESSION['currentUser'];

try {
    // Fetch the booked rooms for the logged-in user
    $sql = "
        SELECT r.room_num, r.department, t.start_time, t.end_time, t.days 
        FROM transaction t
        JOIN rooms r ON t.room_num = r.room_num
        WHERE t.userid = ?";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(1, $logged_in_user_id, PDO::PARAM_INT);
    $stmt->execute();
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch results as an associative array
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<div class="main-content">
    <div class="rooms-container">
        <?php if (!empty($rooms)): ?>
            <?php foreach ($rooms as $room): ?>
                <div class="room">
                    <p><strong>Department:</strong> <?= htmlspecialchars($room['department']) ?></p>
                    <p><strong>Room Number:</strong> <?= htmlspecialchars($room['room_num']) ?></p>
                    <p><strong>Time:</strong> <?= htmlspecialchars($room['start_time']) ?> to <?= htmlspecialchars($room['end_time']) ?></p>
                    <p><strong>Days:</strong> <?= htmlspecialchars($room['days']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No rooms booked.</p>
        <?php endif; ?>
    </div>
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
    </div>
</body>

<!-- <footer style="margin-top:0;">
      <div class="left-footer">
        <i class="fa-solid fa-circle-question"></i>
        <a href="contact-us.php">contact us</a>
      </div>
      <div class="center">
        <a href="">Terms & Conditions</a>
        <p>|</p>
        <p>@2024 mark</p>
        <p>|</p>
        
        <a href="">Privacy & Policy</a>
      </div>
      <div class="right-footer">
        <i class="fa-solid fa-circle-info"></i>
      </div>
    </footer> -->



</html>
