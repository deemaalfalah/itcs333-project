<?php
// Fetch reservations for a given room
require('connection.php');

if (isset($_GET['room_num'])) {
    $room_num = $_GET['room_num'];
    $logged_in_user_id = $_SESSION['currentUser'];
    
    try {
        $sql = "SELECT record_id, start_date, start_time, end_time FROM transaction WHERE room_num = ? ";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $room_num,PDO::PARAM_INT);
        $stmt->execute();
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($reservations);  // Return reservations as JSON
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error fetching reservations: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Room number not specified']);
}
?>
