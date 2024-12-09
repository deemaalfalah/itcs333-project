<?php
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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


    require('connection.php');

    $department = $_GET['departmen'] ?? '';
    $lab = $_GET['lab'] ?? '';
    $datashow = $_GET['datashow'] ?? '';
    $smartboard = $_GET['smartboard'] ?? '';
    $capacity = $_GET['capacity'] ?? '';
    
    // Start building the SQL query
    $sql = "SELECT * FROM rooms WHERE 1";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $department = $_POST['department'] ?? '';
        $lab = $_POST['lab'] ?? '';
        $datashow = $_POST['datashow'] ?? '';
        $smartboard = $_POST['smartboard'] ?? '';
        $capacity = $_POST['capacity'] ?? '';
    
        // Start building the SQL query
        $sql = "SELECT * FROM rooms WHERE 1";
    
        // Add conditions to the SQL query based on filter parameters
        if (!empty($department)) {
            $sql .= " AND department LIKE :department";
        }
        if ($lab !== '') {
            $sql .= " AND lab = :lab";
        }
        if ($datashow !== '') {
            $sql .= " AND datashow = :datashow";
        }
        if ($smartboard !== '') {
            $sql .= " AND smartboard = :smartboard";
        }
        if ($capacity !== '') {
            $sql .= " AND capacity >= :capacity";
        }
    
        $stmt = $db->prepare($sql);
    
        // Bind parameters for the SQL query
        if (!empty($department)) {
            $stmt->bindValue(':department', '%' . $department . '%', PDO::PARAM_STR);
        }
        if ($lab !== '') {
            $stmt->bindValue(':lab', $lab, PDO::PARAM_INT);
        }
        if ($datashow !== '') {
            $stmt->bindValue(':datashow', $datashow, PDO::PARAM_INT);
        }
        if ($smartboard !== '') {
            $stmt->bindValue(':smartboard', $smartboard, PDO::PARAM_INT);
        }
        if ($capacity !== '') {
            $stmt->bindValue(':capacity', $capacity, PDO::PARAM_INT);
        }
    
        $stmt->execute();
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Return filtered rooms as JSON
        echo json_encode($rooms);
        exit();
    }
}


?>


<nav class="navbar">
        <div class="navbar-container">
            <div class="search-bar-container">
                <form id="search-form" method="POST" action="room-booking.php" enctype="multipart/form-data">
                    <input type="text" id="room-number" name="room-number" placeholder="Search by Room Number" required>
                    <button class = search-button type="submit">Search</button>
                    <button onclick="openFilterModal()">Filter Rooms</button>
                </form>
            </div>
        </div>
    </nav>





    <!-- Sidebar Section -->
    <div class="sidebar">
        <!-- Hamburger button for mobile view -->
        <button class="hamburger">&#9776;</button>
        <div class="profile">
        <?php
            if(isset($_POST['delete_profile_image']) || $profile_picture == null) { ?>
                <img src="<?php echo 'upload/profile_image/aa.jpeg'?>" 
                 alt="Profile Picture" 
                 class="profile-pic">
            <?php 
            }
            else { ?>
                <img src="<?php echo 'uploads/profile_image/' . htmlspecialchars($profile_picture); ?>" 
                 alt="Profile Picture" 
                 class="profile-pic">
        <?php } ?>
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

    <div class="container">
        <div class="main-content">
            <div class="rooms-container">
                <?php
                require("connection.php");

                //Check if the search form was submitted
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['room-number'])) {
                // Get the room number from the form input
                $roomNumber = $_POST['room-number'];

                // Fetch room data from the database
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
                <div class="room" onmouseover="showTransactionTable(this, <?= htmlspecialchars($row['room_num']) ?>)" onmouseout="hideTransactionTable()">
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

                <div class="room" onmouseover="showTransactionTable(this, <?= htmlspecialchars($row['room_num']) ?>)" onmouseout="hideTransactionTable()">
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
       

    <!-- Filter Modal -->
    
    <div id="filter-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-button" onclick="closeFilterModal()">&times;</span>
            <h3>Filter Rooms</h3>
            <form id="filter-form" onsubmit="applyFilter(event)">
                
                

            <label for="department">Department:</label>
            <input type="text" id="department" name="department" placeholder="e.g., Computer Science">


                <label for="lab">Lab:</label>
                <select id="lab" name="lab">
                    <option value="">Any</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>

                <label for="datashow">Datashow:</label>
                <select id="datashow" name="datashow">
                    <option value="">Any</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>

                <label for="smartboard">Smartboard:</label>
                <select id="smartboard" name="smartboard">
                    <option value="">Any</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>

                <label for="capacity">Capacity:</label>
                <input type="range" id="capacity" name="capacity" min="0" max="100" oninput="document.getElementById('capacity-display').innerText = this.value">
                <span id="capacity-display">50</span>

                <button type="submit">Apply Filter</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
    
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

        <script>
        // Toggle sidebar visibility
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
        }

        // Attach toggle function to the hamburger button
        document.querySelector('.hamburger').addEventListener('click', toggleSidebar);

    </script>
<footer class="university-footer">
  <div class="footer-content">
    
    <!-- Right Section (Social Media Icons) -->
    <div class="footer-right">
      <div class="footer-item">
        <a href="https://www.instagram.com/uobedubh/?hl=en" target="_blank" class="social-icon">
          <i class="fab fa-instagram"></i> Instagram
        </a>
      </div>
      <div class="footer-item">
        <a href="https://x.com/i/flow/login?redirect_after_login=%2Fuobitcollege" target="_blank" class="social-icon">
          <i class="fab fa-twitter"></i> Twitter
        </a>
      </div>

    <!-- Copyright -->
    <div class="footer-copyright">
      ©️ 2024 University of Bahrain.all right reserved.
    </div>
    </footer>
</body>
</html>