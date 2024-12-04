<?php
require("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
    $reservation_id = intval($_POST['reservation_id']);

    $sql = "DELETE FROM transaction WHERE record_id = ?";
    $stmt = $db->prepare($sql);

    if ($stmt->execute([$reservation_id])) {
        header("Location: dashboard-user.php?success=Reservation cancelled successfully");
    } else {
        header("Location: dashboard-user.php?error=Failed to cancel reservation");
    }
}
?>
