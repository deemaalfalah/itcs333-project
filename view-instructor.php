<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor View</title>
    <link rel="stylesheet" href="styles/view-instructor.css">
    <script src="scripts/sidebar-toggle.js" defer></script>
</head>
<body>
    <div class="sidebar">
        <div class="profile">
            <img src="https://placehold.co/80x80/gray/white" alt="Instructor Picture" class="profile-pic">
            <h2>Instructor Name</h2>
        </div>
        <nav class="nav-menu">
            <ul>
                <li class="menu-item">
                    <a href="#" class="menu-title" onclick="toggleSubmenu('book-room')">Book Room &#9662;</a>
                    <ul class="submenu" id="book-room">
                        <li><a href="single_booking.php">Single Booking</a></li>
                        <li><a href="daily_booking.php">Daily Booking</a></li>
                    </ul>
                </li>
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Help</a></li>
                <li><a href="#">My Account</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="content">
        <div class="room-details-container">
            <!-- Room Details Section -->
            <div class="room-details">
                <h1>Room Details</h1>
                <img src="https://placehold.co/300x200" alt="Room Picture" class="room-picture">
                <p><strong>Room Number:</strong> 101</p>
                <p><strong>Description:</strong> This room is equipped with a projector, air conditioning, and a seating capacity of 50 people.</p>

                <!-- Booking Table -->
                <h2>Booking Table</h2>
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Booked By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2024-11-25</td>
                            <td>10:00 AM - 12:00 PM</td>
                            <td>John Doe</td>
                        </tr>
                        <tr>
                            <td>2024-11-26</td>
                            <td>1:00 PM - 3:00 PM</td>
                            <td>Jane Smith</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Map Section -->
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
</html>

    </div>
</body>
</html>
