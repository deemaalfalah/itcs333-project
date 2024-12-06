<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/contact-us.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Contact Us - Class Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
</head>
<body>

<header>
    Contact Us
</header>
<?php
session_start(); // Start the session at the top
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; // Use 'Guest' if not logged in


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
        $profile_picture = $user['profile_image'] ?? 'default.png';
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // Default values if no user is logged in
    $username = "Guest";
    $profile_picture = "default.png";
}
?>

<!-- Sidebar Section -->
<div class="sidebar">
            <div class="profile">
            <img src="<?php echo 'uploads/profile_image/' . htmlspecialchars($profile_picture); ?>" 
             alt="Profile Picture" 
             class="profile-pic">
                <h2><?php echo htmlspecialchars($username); ?></h2>
            </div>
            <nav class="nav-menu">
                <ul>
                <li>
                    <li><a href="dashboard-user.php">Dashboard</a></li>
                    <li><a href="room-booking.php">Room Booking</a></li>
                    <li><a href="edit-profile.php">My Account</a></li>
                    <li><a href="change-password.php">Change password</a></li>
                    <li><a href="contact-us.php">Contact US</a></li>
                    <li><a href="logout.php" class="logout-button">Logout</a></li>
                </ul>
            </nav>
        </div>
<div class="container">
    <h1>We'd Love to Hear From You!</h1>
    <div class="contact-info">
        <p>For any queries or support, feel free to reach out to us:</p>
        <ul>
            <li><i class="fa-solid fa-phone"></i> Phone: 3378 4599 </li>
            <li><i class="fa-solid fa-envelope"></i> Email: support@UOBclassbooking.com</li>
            <li><i class="fas fa-map-marker-alt"></i> Address: 123 Zallaq HW, Zallaq, Bahrain</li>
            <li><i class="fas fa-clock"></i> Working Hours: Sun-Thurs, 7 AM - 4 PM</li>
        </ul>
    </div>

    <div class="contact-form">
        <h2>Send Us a Message</h2>
        <form class="form-input">
            <input type="text" placeholder="Your Name" required>
            <input type="email" placeholder="Your Email" required>
            <textarea placeholder="Your Message" rows="5" required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
</div>

<footer>
    &copy; 2024 Class Booking. All rights reserved.
</footer>

</body>
</html>
