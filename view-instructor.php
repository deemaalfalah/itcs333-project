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
                            <li><a href="daily-booking-ID.php">Daily Booking</a></li>
                        </ul>
                    </li>
                    <li><a href="view-instructor.php">Dashboard</a></li>
                    <li><a href="contact-us.php">Contact US</a></li>
                    <li><a href="#">My Account</a></li>
                    <li><a href="logout.php" class="logout-button">Logout</a>
                    </li>
                </ul>
            </nav>
        </div>

        
        <div class="header-container">
            <h1>Welcome</h1>
        </div>
        
        <!-- Main Content Section -->
        <div class="main-content">
            <div class="rooms-container">
                <!-- Repeat this block for each room -->
                <div class="room">
                <p><strong>itcs333</strong></p>
                <p><strong>section :</section>:</strong> 01</p>
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>time:</strong> 9:00 to 9:50</p>
                </div>

                <div class="room">
                <p><strong>itcs333</strong></p>
                <p><strong>section :</section>:</strong> 01</p>
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>time:</strong> 9:00 to 9:50</p>
                </div>
                <div class="room">
                <p><strong>itcs333</strong></p>
                <p><strong>section :</section>:</strong> 01</p>
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>time:</strong> 9:00 to 9:50</p>
                </div>

                <div class="room">
                <p><strong>itcs333</strong></p>
                <p><strong>section :</section>:</strong> 01</p>
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>time:</strong> 9:00 to 9:50</p>
                </div>

                <div class="room">
                <p><strong>itcs333</strong></p>
                <p><strong>section :</section>:</strong> 01</p>
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>time:</strong> 9:00 to 9:50</p>
                </div>

                <div class="room">
                <p><strong>itcs333</strong></p>
                <p><strong>section :</section>:</strong> 01</p>
                    <p><strong>Room Number:</strong> 101</p>
                    <p><strong>time:</strong> 9:00 to 9:50</p>
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
