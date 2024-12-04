
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
                    <li><a href="edit-profile.php">My Account</a></li>
                    <li><a href="contact-us.php">Contact US</a></li>
                
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
                    <button class="cancel-button" onclick="showCancelPopup(<?= htmlspecialchars(json_encode($room)) ?>)">Cancel Reservation</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No rooms booked.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Cancel Reservation Popup -->
<div id="cancel-popup" class="popup">
    <div class="popup-content">
        <h3>Cancel Reservation</h3>
        <form id="cancel-form" method="POST" action="cancel-reservation.php">
            <input type="hidden" name="room_num" id="room-num">
            <div id="reservation-options"></div>
            <button type="submit">Cancel</button>
            <button type="button" onclick="closeCancelPopup()">Close</button>
        </form>
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


<script>
function showCancelPopup(room) {
    const popup = document.getElementById('cancel-popup');
    const roomNumInput = document.getElementById('room-num');
    const optionsContainer = document.getElementById('reservation-options');

    // Fetch all transactions for the room
    fetch(`get-reservations.php?room_num=${room.room_num}`)
        .then(response => response.json())
        .then(data => {
            roomNumInput.value = room.room_num;
            optionsContainer.innerHTML = '';

            // Create checkboxes for each reservation
            data.forEach(reservation => {
                const label = document.createElement('label');
                const checkbox = document.createElement('input');
                checkbox.type = 'radio';
                checkbox.name = 'reservation_id';
                checkbox.value = reservation.record_id;

                label.appendChild(checkbox);
                label.appendChild(
                    document.createTextNode(
                        `Date: ${reservation.start_date} - Time: ${reservation.start_time} to ${reservation.end_time}`
                    )
                );
                optionsContainer.appendChild(label);
            });

            popup.style.display = 'block';
        });
}

function closeCancelPopup() {
    const popup = document.getElementById('cancel-popup');
    popup.style.display = 'none';
}

</script>


</html>
