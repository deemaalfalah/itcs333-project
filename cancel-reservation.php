<?php
// Handle the cancellation of a reservation
require('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
    $reservation_id = $_POST['reservation_id'];

    try {
        // Delete the reservation from the database
        $sql = "DELETE FROM transaction WHERE record_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $reservation_id, PDO::PARAM_INT);
        $stmt->execute();

        echo "Reservation cancelled successfully.";
        // Redirect or display a success message
    } catch (PDOException $e) {
        echo "Error cancelling reservation: " . $e->getMessage();
    }
}
?>
