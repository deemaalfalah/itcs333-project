
<?php
session_start();

if (isset($_SESSION['success_message'])) {
    echo "<div class='success-message'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
    unset($_SESSION['success_message']); // Clear the message after displaying it
}

if (isset($_SESSION['error_message'])) {
    echo "<div class='error-message'>" . htmlspecialchars($_SESSION['error_message']) . "</div>";
    unset($_SESSION['error_message']); // Clear the message after displaying it
}


 if (isset($_SESSION['success_message']) || isset($_SESSION['error_message'])): ?>
    <div class="message-overlay"></div> <!-- Overlay background -->
    <div class="<?= isset($_SESSION['success_message']) ? 'success-message' : 'error-message' ?>">
        <?= isset($_SESSION['success_message']) ? htmlspecialchars($_SESSION['success_message']) : htmlspecialchars($_SESSION['error_message']) ?>
    </div>
    <?php
    unset($_SESSION['success_message']);
    unset($_SESSION['error_message']);
    ?>
<?php endif;
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor View</title>
    <link rel="stylesheet" href="styles/dashboard-user.css">
    <link rel="stylesheet" href="styles/dashboard-user.css?v=1.1">
    <script src="scripts/sidebar-toggle.js" defer></script>
</head>
<body>
<?php
 // Start the session at the top
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; // Use 'Guest' if not logged in
$profile_picture = $user['profile_image'] ?? 'default.png';

if (isset($_SESSION['currentUser'])) {
    $userid = $_SESSION['currentUser'];
    try {
        require('connection.php');
        $sql = "SELECT username, profile_image FROM users WHERE userid = ?";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(1, $userid, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $username = $user['username'] ?? '';
        $profile_picture = $user['profile_image'] ?? 'picture\images.png';
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // Default values if no user is logged in
    $username = "Guest";
    $profile_picture = $user['profile_image'] ?? 'picture\images.png';
}
?>
    <div class="container">
        <!-- Sidebar Section -->
        <div class="sidebar">


        

<!-- Hamburger button for mobile view -->
<button class="hamburger">&#9776;</button>


            <div class="profile">
            <img src="<?php echo 'uploads/profile_image/' . htmlspecialchars($profile_picture); ?>" 
             alt="Profile Picture" 
             class="profile-pic">
                <h2><?php echo htmlspecialchars($username); ?></h2>            </div>
            <nav class="nav-menu">
                <ul>
                    <li><a href="dashboard-user.php">Dashboard</a></li>
                    <li><a href="room-booking.php">Room Booking</a></li>
                    <li><a href="edit-profile.php">My Account</a></li>
                    <li><a href="change-password.php">Change password</a></li>
                    <li><a href="contact-us.php">Contact US</a></li>
                    <li><a href="logout.php" class="logout-button">Logout</a></li>
                </ul>
            </nav>
        </div>

        <?php
        // Database connection
        if (!isset($_SESSION['currentUser'])) {
            die("User not logged in. Please log in first.");
        }
        $logged_in_user_id = $_SESSION['currentUser'];
        
        try {
            // Check if a search query was submitted
            $searchQuery = isset($_POST['room-number']) ? trim($_POST['room-number']) : null;
        
            if ($searchQuery) {
                // Fetch rooms matching the search query for the logged-in user
                $sql = "
                    SELECT r.room_num, r.department, t.start_time, t.end_time, t.days 
                    FROM transaction t
                    JOIN rooms r ON t.room_num = r.room_num
                    WHERE t.userid = ? AND r.room_num = ?";
                $stmt = $db->prepare($sql);
                $stmt->bindValue(1, $logged_in_user_id, PDO::PARAM_INT);
                $stmt->bindValue(2, $searchQuery, PDO::PARAM_STR);
            } else {
                // Fetch all rooms for the logged-in user
                $sql = "
                    SELECT r.room_num, r.department, t.start_time, t.end_time, t.days 
                    FROM transaction t
                    JOIN rooms r ON t.room_num = r.room_num
                    WHERE t.userid = ?";
                $stmt = $db->prepare($sql);
                $stmt->bindValue(1, $logged_in_user_id, PDO::PARAM_INT);
            }
        
            $stmt->execute();
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch results as an associative array
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
        ?>

        <nav class="navbar">
            <div class="navbar-container">
                <div class="search-bar-container">
                    <form id="search-form" method="POST" action="dashboard-user.php" enctype="multipart/form-data">
                    <input type="text" id="room-number" name="room-number" placeholder="Search by Room Number" required>
                    <button class = search-button type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
        

        <div class="main-content">
        <div class="rooms-container">
    <?php if (!empty($rooms)): ?>
        <?php foreach ($rooms as $room): ?>
            <div class="room">
                <p><strong>Department:</strong> <?= htmlspecialchars($room['department']) ?></p>
                <p><strong>Room Number:</strong> <?= htmlspecialchars($room['room_num']) ?></p>
                <p><strong>Time:</strong> <?= htmlspecialchars($room['start_time']) ?> to <?= htmlspecialchars($room['end_time']) ?></p>
                <p><strong>Days:</strong> <?= htmlspecialchars($room['days']) ?></p>
                <button 
                    class="cancel-button" 
                    onclick="showCancelPopup(<?= htmlspecialchars(json_encode($room)) ?>)">
                    Cancel Reservation
                </button>
            </div>
            <?php endforeach; ?>
    <?php else: ?>
        <?php if (!empty($searchQuery)): ?>
            <p>No rooms found for Room Number <?= htmlspecialchars($searchQuery) ?>.</p>
        <?php else: ?>
            <p>No rooms booked.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

        <!-- Cancel Reservation Popup -->
        <div id="popup-overlay" class="popup-overlay" onclick="closeCancelPopup()"></div>

        <div id="cancel-popup" class="popup">
            <div class="popup-content">
                <h3>Cancel Reservation</h3>
                <form id="cancel-form" method="POST" action="cancel-reservation.php">
                    <input type="hidden" name="room_num" id="room-num">
                    <div id="reservation-options"></div>
                    <button id="cancel-button"type="submit">Cancel</button>
                    <button id="close-button" type="button" onclick="closeCancelPopup()">Close</button>
                </form>
            </div>
        </div>

        <!-- Right Section: Map -->
        <div class="right-section">
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

    <script>
    function showCancelPopup(room) {
    const popup = document.getElementById('cancel-popup');
    const overlay = document.getElementById('popup-overlay');
    const roomNumInput = document.getElementById('room-num');
    const optionsContainer = document.getElementById('reservation-options');

    // Show the overlay and popup
    overlay.style.display = 'block';
    popup.style.display = 'block';

    // Fetch reservations matching the logged-in user, room, and time
    fetch(`get-user-reservations.php?room_num=${room.room_num}&start_time=${room.start_time}&end_time=${room.end_time}&days=${room.days}`)
        .then(response => response.json())
        .then(data => {
            roomNumInput.value = room.room_num;
            optionsContainer.innerHTML = ''; // Clear any existing options

            if (data.length === 0) {
                optionsContainer.innerHTML = '<p>No reservations found for this selection.</p>';
                return;
            }

            // Create radio buttons for each matching reservation
            data.forEach(reservation => {
                const label = document.createElement('label');
                const radio = document.createElement('input');
                radio.type = 'radio';
                radio.name = 'reservation_id';
                radio.value = reservation.record_id;

                label.appendChild(radio);
                label.appendChild(
                    document.createTextNode(
                        `Date: ${reservation.start_date} - Time: ${reservation.start_time} to ${reservation.end_time}`
                    )
                );
                optionsContainer.appendChild(label);
            });
        })
        .catch(error => {
            console.error('Error fetching reservations:', error);
            optionsContainer.innerHTML = '<p>Error loading reservations. Please try again.</p>';
        });
}

    function closeCancelPopup() {
        const popup = document.getElementById('cancel-popup');
        const overlay = document.getElementById('popup-overlay');

        // Hide the overlay and popup
        overlay.style.display = 'none';
        popup.style.display = 'none';
    }
    </script>


<script>
        // Toggle sidebar visibility
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
        }

        // Attach toggle function to the hamburger button
        document.querySelector('.hamburger').addEventListener('click', toggleSidebar);
    </script>



</body>


</html>
