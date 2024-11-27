<?php
require("connection.php");

if (isset($_GET['room_num'])) {
    $room_num = intval($_GET['room_num']);
    $sql = "SELECT * FROM `transaction` WHERE room_num = :room_num";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':room_num', $room_num, PDO::PARAM_INT);
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($transactions);
}
?>
