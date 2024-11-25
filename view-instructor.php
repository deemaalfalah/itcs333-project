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

<!-- Main Content Section -->
<div class="parent">
    <!-- Room 1 -->
    <div class="room">
        <img src="picture/class No 021.jpeg" alt="Room 1" class="room-image">
        <p><strong>Room Number:</strong> 101</p>
        <p><strong>Description:</strong> This room is equipped with a projector, air conditioning, and seating for 50 people.</p>
        <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=101'">View More Details</button>
    </div>
    
    <!-- Room 2 -->
    <div class="room">
        <img src="picture/class No 049.jpeg" alt="Room 2" class="room-image">
        <p><strong>Room Number:</strong> 102</p>
        <p><strong>Description:</strong> This room has flexible seating and a large whiteboard for group discussions.</p>
        <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=102'">View More Details</button>
    </div>
    
    <!-- Room 3 -->
    <div class="room">
        <img src="picture/class No 057.jpeg" alt="Room 3" class="room-image">
        <p><strong>Room Number:</strong> 103</p>
        <p><strong>Description:</strong> A modern classroom with multimedia capabilities and seating for 30 students.</p>
        <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=103'">View More Details</button>
    </div>
    
    <!-- Room 4 -->
    <div class="room">
        <img src="picture/class No 058.jpeg" alt="Room 4" class="room-image">
        <p><strong>Room Number:</strong> 104</p>
        <p><strong>Description:</strong> A cozy room ideal for workshops and small seminars.</p>
        <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=104'">View More Details</button>
    </div>
    
    <!-- Room 5 -->
    <div class="room">
        <img src="picture/class No 060.jpeg" alt="Room 5" class="room-image">
        <p><strong>Room Number:</strong> 105</p>
        <p><strong>Description:</strong> A well-lit room with a projector and plenty of space for 40 students.</p>
        <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=105'">View More Details</button>
    </div>
    
    <!-- Room 6 -->
    <div class="room">
        <img src="picture/class No 077.jpeg" alt="Room 6" class="room-image">
        <p><strong>Room Number:</strong> 106</p>
        <p><strong>Description:</strong> This room is designed for collaborative learning with movable seating arrangements.</p>
        <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=106'">View More Details</button>
    </div>

    <!-- Room 7 -->
    <div class="room">
        <img src="picture/class No 086.jpeg" alt="Room 7" class="room-image">
        <p><strong>Room Number:</strong> 107</p>
        <p><strong>Description:</strong> Ideal for meetings with a large conference table and comfortable chairs.</p>
        <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=107'">View More Details</button>
    </div>

    <!-- Room 8 -->
    <div class="room">
        <img src="picture/class No 088.jpeg" alt="Room 8" class="room-image">
        <p><strong>Room Number:</strong> 108</p>
        <p><strong>Description:</strong> A spacious room with a theater-style seating arrangement for 100 people.</p>
        <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=108'">View More Details</button>
    </div>

    <!-- Room 9 -->
    <div class="room">
        <img src="picture/class No 1006.jpeg" alt="Room 9" class="room-image">
        <p><strong>Room Number:</strong> 109</p>
        <p><strong>Description:</strong> A versatile space for various types of events, equipped with audio-visual facilities.</p>
        <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=109'">View More Details</button>
    </div>

    <!-- Room 10 -->
    <div class="room">
        <img src="picture/class No 1047.jpeg" alt="Room 10" class="room-image">
        <p><strong>Room Number:</strong> 110</p>
        <p><strong>Description:</strong> A small meeting room perfect for group discussions with 10 seats.</p>
        <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=110'">View More Details</button>
    </div>

    <!-- Room 11 -->
    <div class="room">
        <img src="picture/class No 1089.jpeg" alt="Room 11" class="room-image">
        <p><strong>Room Number:</strong> 111</p>
        <p><strong>Description:</strong> A room designed for remote learning, equipped with video conferencing tools.</p>
        <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=111'">View More Details</button>
    </div>

    <!-- Room 12 -->
    <div class="room">
        <img src="picture/class No 2046.jpeg" alt="Room 12" class="room-image">
        <p><strong>Room Number:</strong> 112</p>
        <p><strong>Description:</strong> A tech-savvy room with high-speed internet and a large screen for presentations.</p>
        <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=112'">View More Details</button>
    </div>

    <!-- Room 13 -->
    <div class="room">
        <img src="picture/class No 2050.jpeg" alt="Room 13" class="room-image">
        <p><strong>Room Number:</strong> 113</p>
        <p><strong>Description:</strong> A stylish and contemporary room with minimalistic decor and a cozy atmosphere.</p>
        <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=113'">View More Details</button>
    </div>

    <!-- Room 14 -->
    <div class="room">
        <img src="picture\class No 2087.jpeg" alt="Room 14" class="room-image">
        <p><strong>Room Number:</strong> 114</p>
        <p><strong>Description:</strong> A classroom with a flexible seating arrangement suitable for group activities.</p>
        <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=114'">View More Details</button>
    </div>

    <!-- Room 15 -->
    <div class="room">
        <img src="picture/class No1083.jpeg" alt="Room 15" class="room-image">
        <p><strong>Room Number:</strong> 115</p>
        <p><strong>Description:</strong> A bright and spacious room perfect for lectures and presentations.</p>
        <button class="view-details-btn" onclick="window.location.href='room_details.php?room_id=115'">View More Details</button>
    </div>
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
