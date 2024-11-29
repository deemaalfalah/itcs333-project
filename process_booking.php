<?php
require("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $roomNum = $_POST['room_num'];
    $semester = $_POST['semester'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];
    $days = $_POST['days'];

    // Check for clashes
    $query = "SELECT * FROM transaction
              WHERE room_num = :room_num
              AND semester = :semester
              AND days = :days
              AND (
                  (start_time <= :end_time AND end_time >= :start_time)
                  AND (start_date <= :end_date AND end_date >= :start_date)
              )";

    $stmt = $db->prepare($query);
    $stmt->execute([
        ':room_num' => $roomNum,
        ':semester' => $semester,
        ':days' => $days,
        ':start_time' => $startTime,
        ':end_time' => $endTime,
        ':start_date' => $startDate,
        ':end_date' => $endDate,
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Room is already booked at the specified time and days.']);
    } else {
        // No clash, insert the booking
        $insertQuery = "INSERT INTO transaction (userid, room_num, semester, start_date, end_date, start_time, end_time, days)
                        VALUES (:userid, :room_num, :semester, :start_date, :end_date, :start_time, :end_time, :days)";
        $insertStmt = $db->prepare($insertQuery);
        $insertStmt->execute([
            ':userid' => $userId,
            ':room_num' => $roomNum,
            ':semester' => $semester,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
            ':start_time' => $startTime,
            ':end_time' => $endTime,
            ':days' => $days,
        ]);

        echo json_encode(['success' => true]);
    }
}
?>
