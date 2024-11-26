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
            <h1>Instructor Dashboard</h1>

            <div class="courses-container">
                <!-- PHP code to fetch and display courses for the instructor -->
                <?php
                // Example data, this should come from the database in a real-world scenario
                $courses = [
                    [
                        'course_name' => 'Computer Science 101',
                        'room_number' => '101',
                        'building_number' => 'Building A',
                        'class_time' => '9:00 AM - 11:00 AM',
                        'section_number' => 'CS101-01',
                    ],
                    [
                        'course_name' => 'Mathematics 201',
                        'room_number' => '205',
                        'building_number' => 'Building B',
                        'class_time' => '11:30 AM - 1:30 PM',
                        'section_number' => 'MATH201-02',
                    ],
                    // Add more courses as needed
                ];

                // Loop through each course and display the details
                foreach ($courses as $course) {
                    echo '<div class="course">';
                    echo '<h3>' . $course['course_name'] . '</h3>';
                    echo '<p><strong>Room Number:</strong> ' . $course['room_number'] . '</p>';
                    echo '<p><strong>Building Number:</strong> ' . $course['building_number'] . '</p>';
                    echo '<p><strong>Class Time:</strong> ' . $course['class_time'] . '</p>';
                    echo '<p><strong>Section Number:</strong> ' . $course['section_number'] . '</p>';
                    echo '</div>';
                }
                ?>
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
