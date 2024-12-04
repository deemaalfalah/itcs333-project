<?php
session_start();

if (isset($_SESSION['currentUser'])) {
    $userid = $_SESSION['currentUser'];
    try {
        require('connection.php');
        $sql = "SELECT * FROM users WHERE userid = ?";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(1, $userid, PDO::PARAM_INT);
        $stmt->execute();
        $user_profile = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user_profile) {
            die("User not found.");
        }

        // Safely assign variables
        $username = $user_profile['username'] ?? '';
        $email = $user_profile['email'] ?? '';
        $profile_picture = $user_profile['profile_image'] ?? 'default.png';
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        require('connection.php');
        $db->beginTransaction();

        $newUsername = $_POST["username"] ?? '';
        $newEmail = $_POST["email"] ?? '';
        $newProfilePicture = $_FILES["profile_image"] ?? null;

        $counter = 0;

        if ($newUsername && $newUsername !== $username) {
            $sql = "UPDATE users SET username = ? WHERE userid = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$newUsername, $userid]);
            $counter++;
        }

        if ($newEmail && $newEmail !== $email) {
            $sql = "UPDATE users SET email = ? WHERE userid = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$newEmail, $userid]);
            $counter++;
        }

        if ($newProfilePicture && $newProfilePicture['size'] > 0) {
            $target_dir = 'uploads/profile_image/';
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
            }

            $filename = uniqid() . "_" . basename($newProfilePicture["name"]);
            $target_file = $target_dir . $filename;

            if (move_uploaded_file($newProfilePicture["tmp_name"], $target_file)) {
                $sql = "UPDATE users SET profile_image = ? WHERE userid = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$filename, $userid]);
                $counter++;
            } else {
                throw new Exception("Failed to upload the file. Please check directory permissions.");
            }
        }

        if ($counter > 0) {
            $db->commit();
            $modalMessage = "Your information has been updated.";
        } else {
            $modalMessage = "No changes made.";
        }
    } catch (PDOException $e) {
        $db->rollBack();
        $modalMessage = "Error updating information: " . $e->getMessage();
    } catch (Exception $e) {
        $modalMessage = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/edit-profile.css">
    <style>
        .profile-picture {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
        }
    </style>
    <script>
        function previewProfileImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    const preview = document.getElementById('profile-image-preview');
                    preview.src = reader.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</head>
<body>
    <div class="profile-container">
        <form method="POST" action="" enctype="multipart/form-data">
            <?php if (!empty($modalMessage)): ?>
                <div class="modal-message"><?php echo htmlspecialchars($modalMessage); ?></div>
            <?php endif; ?>

            <div class="form-group">
                <img id="profile-image-preview" 
                     src="<?php echo 'uploads/profile_image/' . htmlspecialchars($profile_picture); ?>" 
                     class="profile-picture">
                <input type="file" name="profile_image" accept="image/*" onchange="previewProfileImage(event)">
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" 
                       value="<?php echo htmlspecialchars($username); ?>" placeholder="Username" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($email); ?>" placeholder="Email" required>
            </div>

            <div class="form-group">
                <input type="submit" name="btn-submit" value="Update Profile" class="btn-submit">
            </div>
        </form>
    </div>
</body>
</html>
