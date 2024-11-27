<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Information</title>
    <link rel="stylesheet" href="styles/single-booking.css">
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
                        <tr>
                            <th>Record ID</th>
                            <th>User ID</th>
                            <th>Semester</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
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
                            </tr>`;
                    });
                } else {
                    tableContent += '<tr><td colspan="7">No records found</td></tr>';
                }

                tableContent += `</tbody></table>`;
                tableContainer.innerHTML = tableContent;
            });
        }

        function hideTransactionTable() {
            const tableContainer = document.getElementById('room-table-container');
            tableContainer.style.display = 'none';
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <div class="rooms-container">
                <?php
                // Include database connection
                require("connection.php");

                // Fetch room data from the database
                $sql = "SELECT * FROM rooms";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $rooms = $stmt->fetchAll(); // Fetch all rows

                // Check if any rooms are available
                if (count($rooms) > 0) {
                    // Loop through each room and display its details
                    foreach ($rooms as $row) {
                        // Generate the image source dynamically
                        $imageSrc = $row['image'] 
                            ? "data:image/jpeg;base64," . base64_encode($row['image']) 
                            : "https://placehold.co/150x150/gray/white";
                        ?>
                        <div 
                            class="room" 
                            onmouseover="showTransactionTable(this, <?= htmlspecialchars($row['room_num']) ?>)" 
                            onmouseout="hideTransactionTable()">
                            <img src="<?= $imageSrc ?>" alt="Room <?= htmlspecialchars($row['room_num']) ?>" class="room-image">
                            <p><strong>Room Number:</strong> <?= htmlspecialchars($row['room_num']) ?></p>
                            <p><strong>Department:</strong> <?= htmlspecialchars($row['department']) ?></p>
                            <p><strong>Capacity:</strong> <?= htmlspecialchars($row['capacity']) ?> people</p>
                            <p><strong>Lab:</strong> <?= $row['lab'] ? 'Yes' : 'No' ?></p>
                            <p><strong>Smartboard:</strong> <?= $row['smartboard'] ? 'Yes' : 'No' ?></p>
                            <p><strong>Datashow:</strong> <?= $row['datashow'] ? 'Yes' : 'No' ?></p>
                        </div>
                        <?php
                    }
                } else {
                    // If no rooms are found, display a message
                    echo "<p>No rooms available.</p>";
                }
                ?>
            </div>
            <div id="room-table-container" class="room-table"></div>
        </div>
    </div>

    <?php
    // Close database connection (optional)
    $db = null;
    ?>
</body>
</html>
