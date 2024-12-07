<?php
var_dump($_POST); 
session_start();

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php'); 
    exit();
}

$userId = $_SESSION['currentUser']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Information</title>
    <link rel="stylesheet" href="styles/room-booking.css">
    <script>
        async function fetchTransactionData(roomNum) {
            const response = await fetch(`fetch_transactions.php?room_num=${roomNum}`);
            const data = await response.json();
            return data;
        }

        function showTransactionTable(roomDiv, roomNum) {
            const tableContainer = document.getElementById('room-table-container');
            tableContainer.style.display = 'block';
            tableContainer.style.top = `${roomDiv.offsetTop + roomDiv.offsetHeight}px`;
            tableContainer.style.left = `${roomDiv.offsetLeft}px`;

            fetchTransactionData(roomNum).then(data => {
                let tableContent = `<table>
                    <thead>
                        <h3>The room is booked at:</h3>
                        <tr>
                            <th>User ID</th>
                            <th>Semester</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Days</th>
                        </tr>
                    </thead>
                    <tbody>`;

                if (data.length > 0) {
                    data.forEach(record => {
                        tableContent += `
                            <tr>
                                <td>${record.userid}</td>
                                <td>${record.semester}</td>
                                <td>${record.start_date}</td>
                                <td>${record.end_date}</td>
                                <td>${record.start_time}</td>
                                <td>${record.end_time}</td>
                                <td>${record.days}</td>
                            </tr>`;
                    });
                } else {
                    tableContent += '<tr><td colspan="8">No records found</td></tr>';
                }

                tableContent += '</tbody></table>';
                tableContainer.innerHTML = tableContent;
            });
        }

        function hideTransactionTable() {
            const tableContainer = document.getElementById('room-table-container');
            tableContainer.style.display = 'none';
        }

        function openBookingForm(roomNum) {
            const userId = <?php echo json_encode($userId); ?>; // Fetch user ID from PHP session
            document.getElementById('user-id').value = userId;
            document.getElementById('room-num').value = roomNum;
            document.getElementById('booking-form-modal').style.display = 'block';
        }

        function closeBookingForm() {
            document.getElementById('booking-form-modal').style.display = 'none';
        }

        async function handleBooking(event) {
            event.preventDefault();
            const formData = new FormData(document.getElementById('booking-form'));
            const response = await fetch('process_booking.php', {
                method: 'POST',
                body: formData,
            });
            const result = await response.json();

            if (result.success) {
                alert('Room booked successfully!');
                closeBookingForm();
            } else {
                alert(result.message || 'Room booking failed due to a clash.');
            }
        }
    </script>
</head>
<body>
    
<?php
 // Start the session at the top
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
        <!-- Hamburger button for mobile view -->
<button class="hamburger">&#9776;</button>
        <div class="profile">
        <img src="<?php echo 'uploads/profile_image/' . htmlspecialchars($profile_picture); ?>" 
             alt="Profile Picture" 
             class="profile-pic">
            <h2><?php echo htmlspecialchars($username); ?></h2>
        </div>
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

    <nav class="navbar">
        <div class="navbar-container">
            <div class="search-bar-container">
                <form id="search-form" method="POST" action="room-booking.php" enctype="multipart/form-data">
                    <input type="text" id="room-number" name="room-number" placeholder="Enter Room Number" required>
                    <button class = search-button type="submit">Search</button>
                    <!--<button class="filter-button" id="filterButton" method="POST" action="room-booking.php" enctype="multipart/form-data">Filters</button>-->
                </form>
                
            </div>
        </div>
    </nav>

    <!-- Rooms Container -->
    <div class="container">
        <div class="main-content">
            <div id="rooms-container" class="rooms-container">
            <?php
            require("connection.php");

             //Check if the search form was submitted
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['room-number'])) {
                // Get the room number from the form input
                $roomNumber = $_POST['room-number'];

                // Fetch room data matching the search term
                $sql = "SELECT * FROM rooms WHERE room_num = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$roomNumber]);
                $rooms = $stmt->fetchAll();

                if (count($rooms) > 0) {
                    foreach ($rooms as $row) {
                        $imageSrc = $row['image'] 
                            ? "data:image/jpeg;base64," . base64_encode($row['image']) 
                            : "https://placehold.co/150x150/gray/white";
            ?>
            <div class="room">
                <img src="<?= $imageSrc ?>" alt="Room <?= htmlspecialchars($row['room_num']) ?>" class="room-image">
                <p><strong>Room Number:</strong> <?= htmlspecialchars($row['room_num']) ?></p>
                <p><strong>Department:</strong> <?= htmlspecialchars($row['department']) ?></p>
                <p><strong>Capacity:</strong> <?= htmlspecialchars($row['capacity']) ?> people</p>
                <p><strong>Lab:</strong> <?= $row['lab'] ? 'Yes' : 'No' ?></p>
                <p><strong>Smartboard:</strong> <?= $row['smartboard'] ? 'Yes' : 'No' ?></p>
                <p><strong>Datashow:</strong> <?= $row['datashow'] ? 'Yes' : 'No' ?></p>
                <button class="book-room-button" onclick="openBookingForm(<?= htmlspecialchars($row['room_num']) ?>)">Book</button>
            </div>
            <?php
                    }
                } else {
                    echo "<p>No rooms match your search criteria.</p>";
                }
            } else {
                // Fetch all rooms if no search is performed
                $sql = "SELECT * FROM rooms";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $rooms = $stmt->fetchAll();

                if (count($rooms) > 0) {
                    foreach ($rooms as $row) {
                        $imageSrc = $row['image'] 
                            ? "data:image/jpeg;base64," . base64_encode($row['image']) 
                            : "https://placehold.co/150x150/gray/white";
            ?>
            <div class="room">
                <img src="<?= $imageSrc ?>" alt="Room <?= htmlspecialchars($row['room_num']) ?>" class="room-image">
                <p><strong>Room Number:</strong> <?= htmlspecialchars($row['room_num']) ?></p>
                <p><strong>Department:</strong> <?= htmlspecialchars($row['department']) ?></p>
                <p><strong>Capacity:</strong> <?= htmlspecialchars($row['capacity']) ?> people</p>
                <p><strong>Lab:</strong> <?= $row['lab'] ? 'Yes' : 'No' ?></p>
                <p><strong>Smartboard:</strong> <?= $row['smartboard'] ? 'Yes' : 'No' ?></p>
                <p><strong>Datashow:</strong> <?= $row['datashow'] ? 'Yes' : 'No' ?></p>
                <button class="book-room-button" onclick="openBookingForm(<?= htmlspecialchars($row['room_num']) ?>)">Book</button>
            </div>
            <?php
                    }
                } else {
                    echo "<p>No rooms available.</p>";
                }
            }
            ?>
            </div>
            <div id="room-table-container" class="room-table"></div>
        </div>  
    </div>
    

<!-- Modal Form -->
<div id="booking-form-modal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-button" onclick="closeBookingForm()">&times;</span>
            <h3>Book Room</h3>
            <form id="booking-form" onsubmit="handleBooking(event)">
                <label for="user-id">User ID:</label>
                <!-- <input type="text" id="user-id" name="user_id" readonly> -->
                <input type="text" id="user-id" name="user_id" value="<?php echo htmlspecialchars($userId); ?>" readonly>


                <label for="room-num">Room Number:</label>
                <input type="text" id="room-num" name="room_num" readonly>

                <label for="semester">Semester:</label>
                <input type="text" id="semester" name="semester" required>

                <label for="start-date">Start Date:</label>
                <input type="date" id="start-date" name="start_date" required>

                <label for="end-date">End Date:</label>
                <input type="date" id="end-date" name="end_date" required>

                <label for="start-time">Start Time:</label>
                <input type="time" id="start-time" name="start_time" required>

                <label for="end-time">End Time:</label>
                <input type="time" id="end-time" name="end_time" required>

                <label for="days">Days (MW or UTH):</label>
                <input type="text" id="days" name="days" pattern="MW|UTH" required>

                <button type="submit">Submit</button>
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
    
        <!-- Footer -->
    <footer class="footer">
    <div class="container2">
                    <div class="row">
                    <div class="footerCol"><!--location & time column-->
                        <h4>University of Bahrain</h4> 
                        <ul>
                            <li><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffff"><path d="M480-301q99-80 149.5-154T680-594q0-90-56-148t-144-58q-88 0-144 58t-56 148q0 65 50.5 139T480-301Zm0 101Q339-304 269.5-402T200-594q0-125 78-205.5T480-880q124 0 202 80.5T760-594q0 94-69.5 192T480-200Zm0-320q33 0 56.5-23.5T560-600q0-33-23.5-56.5T480-680q-33 0-56.5 23.5T400-600q0 33 23.5 56.5T480-520ZM200-80v-80h560v80H200Zm280-520Z"/></svg> 123 Zallaq HW, Zallaq, Bahrain</li>
                            <li><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffff"><path d="m612-292 56-56-148-148v-184h-80v216l172 172ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-400Zm0 320q133 0 226.5-93.5T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160Z"/></svg> Sunday - Thursday <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7:00 AM - 4:00 PM</li>
                        </ul>
                        
                    </div>
                    <div class="footerCol"><!--resources column-->
                        <h4>Resources</h4>
                        <ul>
                            <li><a href="dashboard-user.php">Dashboard</a></li>
                            <li><a href="room-booking.php">Room booking</a></li>
                            <li><a href="edit-profile.php">My Account</a></li>
                            <li><a href="#">FAQ</a></li>
                        </ul>
                        
                    </div>
                    <div class="footerCol"><!--contact us column-->
                        <h4>Contact Us</h4>
                        <ul>
                            <li><a><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M798-120q-125 0-247-54.5T329-329Q229-429 174.5-551T120-798q0-18 12-30t30-12h162q14 0 25 9.5t13 22.5l26 140q2 16-1 27t-11 19l-97 98q20 37 47.5 71.5T387-386q31 31 65 57.5t72 48.5l94-94q9-9 23.5-13.5T670-390l138 28q14 4 23 14.5t9 23.5v162q0 18-12 30t-30 12ZM241-600l66-66-17-94h-89q5 41 14 81t26 79Zm358 358q39 17 79.5 27t81.5 13v-88l-94-19-67 67ZM241-600Zm358 358Z"/></svg> 3378 4599</a></li>
                            <li><a><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M798-120q-125 0-247-54.5T329-329Q229-429 174.5-551T120-798q0-18 12-30t30-12h162q14 0 25 9.5t13 22.5l26 140q2 16-1 27t-11 19l-97 98q20 37 47.5 71.5T387-386q31 31 65 57.5t72 48.5l94-94q9-9 23.5-13.5T670-390l138 28q14 4 23 14.5t9 23.5v162q0 18-12 30t-30 12ZM241-600l66-66-17-94h-89q5 41 14 81t26 79Zm358 358q39 17 79.5 27t81.5 13v-88l-94-19-67 67ZM241-600Zm358 358Z"/></svg> 3912 3456</a></li>
                            <li><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z"/></svg><a href="mailto:info@sunshinekindergarten.com">support@UOBclassbooking.com</a></li>
                        </ul>
                        
                    </div>   
                    </div>
                </div>
                </div>
        <p>&copy; 2024 UOB Class Booking. All rights reserved.</p>
    </footer>

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
