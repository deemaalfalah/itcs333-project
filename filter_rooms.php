<?php
require('connection.php');

// Get filter inputs
$startDate = $_POST['start_date'];
$endDate = $_POST['end_date'];
$startTime = $_POST['start_time'];
$endTime = $_POST['end_time'];

// Query to find rooms that do not clash with the given times
$sql = "
    SELECT *
    FROM rooms
    WHERE room_num NOT IN (
        SELECT room_num
        FROM transaction
        WHERE (
            (start_date <= :end_date AND end_date >= :start_date) AND
            (start_time < :end_time AND end_time > :start_time)
        )
    )
";
$stmt = $db->prepare($sql);
$stmt->execute([
    ':start_date' => $startDate,
    ':end_date' => $endDate,
    ':start_time' => $startTime,
    ':end_time' => $endTime,
]);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the filtered rooms as JSON
echo json_encode($rooms);
?>
