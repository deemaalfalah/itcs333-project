<?php
// Start the session to access the logged-in user ID
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Information</title>
    <link rel="stylesheet" href="styles/daily-booking.css">
    <script>
        // Fetch transaction data for displaying booked rooms
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
                            <th>Record ID</th>
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
                                <td>${record.record_id}</td>
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

                tableContent += `</tbody></table>`;
                tableContainer.innerHTML = tableContent;
            });
        }

        function hideTransactionTable() {
            const tableContainer = document.getElementById('room-table-container');
            tableContainer.style.display = 'none';
        }

        function openBookingForm(roomNum) {
            const userId = <?= json_encode($_SESSION['user_id']); ?>; // Fetch user ID from PHP session
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
                <li><a href="logout.php" class="logout-button">Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="container">
        <div class="main-content">
            <div class="rooms-container">
                <?php
                require("connection.php");

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
                ?>
            </div>
            <div id="room-table-container" class="room-table"></div>
        </div>
    </div>

    <?php $db = null; ?>

    <!-- Modal Form -->
    <div id="booking-form-modal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-button" onclick="closeBookingForm()">&times;</span>
            <h3>Book Room</h3>
            <form id="booking-form" onsubmit="handleBooking(event)">
                <label for="user-id">User ID:</label>
                <input type="text" id="user-id" name="user_id" readonly>

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
</body>
</html>
