<?php
require("connection.php");

if (isset($_GET['room_num'])) {
    $room_num = intval($_GET['room_num']);
    $userid = $_SESSION['currentUser'];

    $sql = "
        SELECT record_id, start_date, end_date, start_time, end_time
        FROM transaction
        WHERE room_num = ? AND userid = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$room_num, $userid]);

    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($reservations);
} else {
    echo json_encode([]);
}
?>
