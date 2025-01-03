<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

$userId = $_SESSION['currentUser'];  // Get user ID from the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Information</title>
    <link rel="stylesheet" href="styles/dashboard-admin.css">
    <link rel="stylesheet" href="styles/dashboard-admin.css">
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



        function openFilterModal() {
    document.getElementById('filter-modal').style.display = 'block';
}

function closeFilterModal() {
    document.getElementById('filter-modal').style.display = 'none';
}

async function handleFilter(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('filter-form'));

    // Send filter data to the backend to fetch filtered rooms
    const response = await fetch('filter_rooms.php', {
        method: 'POST',
        body: formData,
    });

    const filteredRooms = await response.json();

    // Update the rooms container with the filtered rooms
    const roomsContainer = document.querySelector('.rooms-container');
    roomsContainer.innerHTML = '';

    if (filteredRooms.length > 0) {
        filteredRooms.forEach(room => {
            const roomDiv = document.createElement('div');
            roomDiv.classList.add('room');
            roomDiv.innerHTML = `
                <img src="${room.image || 'https://placehold.co/150x150/gray/white'}" alt="Room ${room.room_num}" class="room-image">
                <p><strong>Room Number:</strong> ${room.room_num}</p>
                <p><strong>Department:</strong> ${room.department}</p>
                <p><strong>Capacity:</strong> ${room.capacity} people</p>
                <p><strong>Lab:</strong> ${room.lab ? 'Yes' : 'No'}</p>
                <p><strong>Smartboard:</strong> ${room.smartboard ? 'Yes' : 'No'}</p>
                <p><strong>Datashow:</strong> ${room.datashow ? 'Yes' : 'No'}</p>
                <button class="book-room-button" onclick="openBookingForm('${room.room_num}')">Book</button>

            `;
            roomsContainer.appendChild(roomDiv);
        });
    } else {
        roomsContainer.innerHTML = '<p>No rooms available matching the criteria.</p>';
    }

    closeFilterModal();
}


    </script>
</head>
<body>
<?php

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

<nav class="navbar">
        <div class="navbar-container">
            <div class="search-bar-container">
                <form id="search-form" method="POST" action="dashboard-admin.php" enctype="multipart/form-data">
                <input type="text" id="room-number" name="room-number" placeholder="Search by Room Number" required>
                <button class = search-button type="submit">Search</button>
                </form>
                <button id="filter-button" onclick="openFilterModal()">Filter</button>
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
                <li><a href="dashboard-admin.php">Dashboard</a></li>
                <li><a href="ManageRooms.php">Manage Rooms</a></li>
                <li><a href="add_room.php">Add Room</a></li>
                <li><a href="edit-profile-admin.php">My Account</a></li>
                <li><a href="change-password-admin.php">Change Password</a></li>
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

                    <!-- Book Room Button -->
                    <button class="book-room-button" onclick="openBookingForm(<?= htmlspecialchars($row['room_num']) ?>)">Book</button>
                    
                    <!-- Edit Room Button -->
                    <a href="edit_room.php?room_num=<?= htmlspecialchars($row['room_num']) ?>" class="edit-room-button">Edit</a>

                    <!-- Remove Room Button -->
                    <form action="delete_room.php" class="remove-form" method="POST" style="display:inline-block;width: 100%;">
                        <input type="hidden" name="room_num" value="<?= htmlspecialchars($row['room_num']) ?>">
                        <button type="submit" class="book-room-button" onclick="return confirm('Are you sure you want to delete this room?')">Remove</button>
                    </form>
                    
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

                <!-- Book Room Button -->
                    <button class="book-room-button" onclick="openBookingForm(<?= htmlspecialchars($row['room_num']) ?>)">Book</button>
                    
                    <!-- Edit Room Button -->
                    <a href="edit_room.php?room_num=<?= htmlspecialchars($row['room_num']) ?>" class="edit-room-button">Edit</a>
                    
                    <!-- Remove Room Button -->
                    <form action="delete_room.php" class="remove-form" method="POST" style="display:inline-block;width: 100%;">
                        <input type="hidden" name="room_num" value="<?= htmlspecialchars($row['room_num']) ?>">
                        <button type="submit" class="book-room-button" onclick="return confirm('Are you sure you want to delete this room?')">Remove</button>
                    </form>
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
    <div id="filter-modal" class="modal-filter" style="display:none;">
    <div class="modal-content-filter">
        <span class="close-button" onclick="closeFilterModal()">&times;</span>
        <h3>Filter Rooms</h3>
        <form id="filter-form" onsubmit="handleFilter(event)">
            <label for="filter-start-date">Start Date:</label>
            <input type="date" id="filter-start-date" name="start_date" required>

            <label for="filter-end-date">End Date:</label>
            <input type="date" id="filter-end-date" name="end_date" required>

            <label for="filter-start-time">Start Time:</label>
            <input type="time" id="filter-start-time" name="start_time" required>

            <label for="filter-end-time">End Time:</label>
            <input type="time" id="filter-end-time" name="end_time" required>

            <button type="submit">Filter</button>
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

