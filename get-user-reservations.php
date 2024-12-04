<?php
session_start();
require('connection.php');

// Check if the user is logged in
if (!isset($_SESSION['currentUser'])) {
    echo json_encode([]);
    exit;
}

$userid = $_SESSION['currentUser'];

// Validate and sanitize input
$room_num = $_GET['room_num'] ?? null;
$start_time = $_GET['start_time'] ?? null;
$end_time = $_GET['end_time'] ?? null;
$days = $_GET['days'] ?? null;

if (!$room_num || !$start_time || !$end_time || !$days) {
    echo json_encode([]);
    exit;
}

try {
    // Fetch reservations for the logged-in user that match the specified room and timing
    $sql = "
        SELECT t.record_id, t.start_date, t.start_time, t.end_time
        FROM transaction t
        WHERE t.userid = ? 
          AND t.room_num = ? 
          AND t.start_time = ? 
          AND t.end_time = ? 
          AND t.days = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$userid, $room_num, $start_time, $end_time, $days]);

    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($reservations);
} catch (PDOException $e) {
    echo json_encode([]);
    error_log("Error fetching reservations: " . $e->getMessage());
}
?>
