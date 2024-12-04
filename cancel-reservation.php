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

        // Set a success message in the session (optional)
        session_start();
        $_SESSION['success_message'] = "Reservation cancelled successfully.";

        // Redirect to dashboard-user.php
        header("Location: dashboard-user.php");
        exit; // Ensure no further code is executed after the redirect
    } catch (PDOException $e) {
        // Handle the error and redirect with an error message
        session_start();
        $_SESSION['error_message'] = "Error cancelling reservation: " . $e->getMessage();
        header("Location: dashboard-user.php");
        exit;
    }
}
?>
