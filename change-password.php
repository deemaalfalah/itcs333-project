<?php
// Start the session
session_start();

// Include the database connection file
require 'connection.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get input values from the form
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validate the input
    $errors = [];
    
    // Check if user is logged in
    if (!isset($_SESSION['currentUser'])) {
        $errors[] = "You must be logged in to change your password.";
    }
    
    // Validate new password match
    if ($new_password !== $confirm_password) {
        $errors[] = "New password and confirmation do not match.";
    }
    
    // Validate password complexity
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&-_])[A-Za-z\d@$!%*#?&-_]{8,}$/', $new_password)) {
        $errors[] = "Password must be at least 8 characters, include a letter, number, and special character.";
    }
    
    // If no errors, proceed with password change
    if (empty($errors)) {
        try {
            // Prepare query to fetch current user's password
            $stmt = $db->prepare("SELECT password FROM users WHERE userid = ?");
            $stmt->execute([$_SESSION['currentUser']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify current password
            if (!password_verify($current_password, $user['password'])) {
                $errors[] = "Current password is incorrect.";
            } else {
                // Hash the new password
                $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
                
                // Prepare update query
                $update_stmt = $db->prepare("UPDATE users SET password = ? WHERE userid = ?");
                $update_result = $update_stmt->execute([$hashed_new_password, $_SESSION['currentUser']]);
                
                if ($update_result) {
                    // Redirect to a success page or show success message
                    $_SESSION['password_change_success'] = "Password successfully updated.";
                    header('Location: dashboard-user.php');
                    exit();
                } else {
                    $errors[] = "Error updating password. Please try again.";
                }
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
    
    // If there are any errors, display them
    if (!empty($errors)) {
        $_SESSION['password_change_errors'] = $errors;
        header('Location: change-password.php');
        exit();
    }
}
?>

<!-- HTML remains the same as in the original change-password.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles/change-password.css">
</head>
<body>
<div class="top-line"></div>

<?php

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; // Use 'Guest' if not logged in


if (isset($_SESSION['currentUser'])) {
    $userid = $_SESSION['currentUser'];
    try {
        require('connection.php');
        $sql = "SELECT username, profile_image FROM users WHERE userid = ?";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(1, $userid, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $username = $user['username'] ?? '';
        $profile_picture = $user['profile_image'] ?? 'default.png';
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // Default values if no user is logged in
    $username = "Guest";
    $profile_picture = "default.png";
}
?>

<!-- Sidebar Section -->
<div class="sidebar">

    <!-- Hamburger button for mobile view -->
<button class="hamburger">&#9776;</button>

            <div class="profile">
            <?php
            if(isset($_POST['delete_profile_image']) || $profile_picture == null) { ?>
                <img src="<?php echo 'upload/profile_image/aa.jpeg'?>" 
                 alt="Profile Picture" 
                 class="profile-pic">
            <?php 
            }
            else { ?>
                <img src="<?php echo 'uploads/profile_image/' . htmlspecialchars($profile_picture); ?>" 
                 alt="Profile Picture" 
                 class="profile-pic">
        <?php } ?>
                <h2><?php echo htmlspecialchars($username); ?></h2>
            </div>
            <nav class="nav-menu">
                <ul>
                <li>
                    <li><a href="dashboard-user.php">Dashboard</a></li>
                    <li><a href="room-booking.php">Room Booking</a></li>
                    <li><a href="edit-profile.php">My Account</a></li>
                    <li><a href="change-password.php">Change password</a></li>
                    <li><a href="contact-us.php">Contact US</a></li>
                    <li><a href="logout.php" class="logout-button">Logout</a></li>
                </ul>
            </nav>
        </div>


        



    <div class="container">
        <form action="change-password.php" method="post" class="password-form">
            <h2>Change Password</h2>
            
            <?php
            // Display errors if any
            if (isset($_SESSION['password_change_errors'])) {
                echo '<div class="error-messages">';
                foreach ($_SESSION['password_change_errors'] as $error) {
                    echo "<p style='color: red;'>$error</p>";
                }
                echo '</div>';
                unset($_SESSION['password_change_errors']);
            }
            
            // Display success message if any
            if (isset($_SESSION['password_change_success'])) {
                echo '<div class="success-message" style="color: green;">' . 
                     $_SESSION['password_change_success'] . '</div>';
                unset($_SESSION['password_change_success']);
            }
            ?>
            
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" placeholder="Current Password" required>
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" placeholder="New Password" required 
                       minlength="8" pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&-_])[A-Za-z\d@$!%*#?&-_]{8,}$">
                <small>Must be at least 8 characters, include a letter, number, and special character</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required>
            </div>
            
            <button type="submit" name="Change Password" class="btn-submit">Change Password</button>
        </form>
    </div>

    <script>
        // Toggle sidebar visibility
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
        }

        // Attach toggle function to the hamburger button
        document.querySelector('.hamburger').addEventListener('click', toggleSidebar);
    </script>


</body>
</html>