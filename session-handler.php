<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set session timeout (optional)
$timeout = 3600; // 1 hour
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
}
$_SESSION['last_activity'] = time();
?>

