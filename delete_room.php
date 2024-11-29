<?php
require("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_num'])) {
    $room_num = $_POST['room_num'];

    $sql = "DELETE FROM rooms WHERE room_num = :room_num";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':room_num', $room_num);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Failed to delete room.";
    }
} else {
    echo "Invalid request.";
}
?>
