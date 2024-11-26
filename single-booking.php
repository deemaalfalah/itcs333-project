<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor View</title>
    <link rel="stylesheet" href="styles/single-booking.css">
    <script src="scripts/sidebar-toggle.js" defer></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar Section -->
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
                            <li><a href="single-booking.php">Single Booking</a></li>
                            <li><a href="daily-booking.php">Daily Booking</a></li>
                        </ul>
                    </li>
                    <li><a href="view-instructor.php">Dashboard</a></li>
                    <li><a href="#">Help</a></li>
                    <li><a href="#">My Account</a></li>
                    <li><a href="logout.php" class="logout-button">Logout</a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content Section -->
        <div class="main-content">
            <div class="rooms-container">
                <!-- Repeat this block for each room -->
                <div class="room">
                    <img src="picture/class No 077.jpeg" alt="Room 1" class="room-image">
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>Description:</strong> This room is equipped with a projector, air conditioning, and seating for 50 people.</p>
                    <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=101'">View More Details</button>
                </div>
                
                <!-- Add more rooms as needed -->
                <div class="room">
                    <img src="picture/class No 021.jpeg" alt="Room 1" class="room-image">
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>Description:</strong> This room is equipped with a projector, air conditioning, and seating for 50 people.</p>
                    <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=101'">View More Details</button>
                </div>

                <div class="room">
                    <img src="picture/class No 060.jpeg" alt="Room 1" class="room-image">
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>Description:</strong> This room is equipped with a projector, air conditioning, and seating for 50 people.</p>
                    <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=101'">View More Details</button>
                </div>

                <div class="room">
                    <img src="picture/class No 088.jpeg" alt="Room 1" class="room-image">
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>Description:</strong> This room is equipped with a projector, air conditioning, and seating for 50 people.</p>
                    <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=101'">View More Details</button>
                </div>

                <div class="room">
                    <img src="picture/class No 1006.jpeg" alt="Room 1" class="room-image">
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>Description:</strong> This room is equipped with a projector, air conditioning, and seating for 50 people.</p>
                    <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=101'">View More Details</button>
                </div>
                <div class="room">
                    <img src="picture/class No 1047.jpeg" alt="Room 1" class="room-image">
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>Description:</strong> This room is equipped with a projector, air conditioning, and seating for 50 people.</p>
                    <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=101'">View More Details</button>
                </div>
                <div class="room">
                    <img src="picture/class No 1081.jpeg" alt="Room 1" class="room-image">
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>Description:</strong> This room is equipped with a projector, air conditioning, and seating for 50 people.</p>
                    <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=101'">View More Details</button>
                </div>
                <div class="room">
                    <img src="picture/class No 1089.jpeg" alt="Room 1" class="room-image">
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>Description:</strong> This room is equipped with a projector, air conditioning, and seating for 50 people.</p>
                    <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=101'">View More Details</button>
                </div>
                <div class="room">
                    <img src="picture/class No 2050.jpeg" alt="Room 1" class="room-image">
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>Description:</strong> This room is equipped with a projector, air conditioning, and seating for 50 people.</p>
                    <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=101'">View More Details</button>
                </div>
                <div class="room">
                    <img src="picture/class No 2087.jpeg" alt="Room 1" class="room-image">
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>Description:</strong> This room is equipped with a projector, air conditioning, and seating for 50 people.</p>
                    <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=101'">View More Details</button>
                </div>
                <div class="room">
                    <img src="picture/class No2033.jpeg" alt="Room 1" class="room-image">
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>Description:</strong> This room is equipped with a projector, air conditioning, and seating for 50 people.</p>
                    <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=101'">View More Details</button>
                </div>
                <div class="room">
                    <img src="picture/class No 2045.jpeg.jpg" alt="Room 1" class="room-image">
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>Description:</strong> This room is equipped with a projector, air conditioning, and seating for 50 people.</p>
                    <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=101'">View More Details</button>
                </div>
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
</html>
