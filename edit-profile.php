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
        // Function to preview the uploaded profile picture
        function previewProfilePicture(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    const preview = document.getElementById('profile-picture-preview');
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
            <?php 
                session_start();
                if (isset($_SESSION['currentUser'])) {
                    $userid = $_SESSION['currentUser'];
                    try {
                        require('connection.php');
                        $sql = "SELECT * FROM users WHERE userid = ?";
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(1, $userid);
                        $stmt->execute();
                        $user_profile = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch user data
                        $db = null;

                        // Initialize variables with user data
                        $username = $user_profile['username'];
                        $email = $user_profile['email'];
                        $profile_picture = $user_profile['profile_picture'];
                    } catch (PDOException $e) {
                        die($e->getMessage());
                    }
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        require('connection.php');
                        $db->beginTransaction();

                        // Form inputs
                        $newUsername = $_POST["username"];
                        $newEmail = $_POST["email"];
                        $newProfilePicture = $_FILES["profile_picture"]; // File upload handling

                        $counter = 0;

                        // Update username if changed
                        if ($newUsername != $username) {
                            $sql = "UPDATE users SET username = ? WHERE userid = ?";
                            $stmt = $db->prepare($sql);
                            $stmt->execute([$newUsername, $userid]);
                            $counter++;
                        }

                        // Update email if changed
                        if ($newEmail != $email) {
                            $sql = "UPDATE users SET email = ? WHERE userid = ?";
                            $stmt = $db->prepare($sql);
                            $stmt->execute([$newEmail, $userid]);
                            $counter++;
                        }

                        // Update profile picture if a new file is uploaded
                        if ($newProfilePicture['size'] > 0) {
                            $target_dir = "uploads/profile_pictures/";
                            $target_file = $target_dir . basename($newProfilePicture["name"]);
                            move_uploaded_file($newProfilePicture["tmp_name"], $target_file);

                            $sql = "UPDATE users SET profile_picture = ? WHERE userid = ?";
                            $stmt = $db->prepare($sql);
                            $stmt->execute([basename($newProfilePicture["name"]), $userid]);
                            $counter++;
                        }

                        if ($counter != 0) {
                            $db->commit();
                            $modalMessage = "Your information is updated";
                            $showModal = true;
                        }
                        $db = null;
                    } catch (PDOException $e) {
                        $db->rollBack();
                        $modalMessage = "Error!";
                        $showModal = true;
                    }
                }
            ?>

            <?php if (!empty($modalMessage)): ?>
                <div class="modal-message"><?php echo htmlspecialchars($modalMessage); ?></div>
            <?php endif; ?>

            <!-- Profile Picture Display and Upload -->
            <div class="form-group">
                <img id="profile-picture-preview" 
                     src="<?php 
                         $default_pic = 'uploads/profile_pictures/default.png';
                         $current_pic = !empty($profile_picture) 
                             ? 'uploads/profile_pictures/' . htmlspecialchars($profile_picture) 
                             : $default_pic;
                         echo $current_pic;
                     ?>" 
                     alt="Profile Picture" class="profile-picture">
                <input type="file" name="profile_picture" accept="image/jpeg,image/png,image/gif" onchange="previewProfilePicture(event)">
            </div>

            <!-- Username -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" 
                       value="<?php echo htmlspecialchars($username ?? ''); ?>" placeholder="Username" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" 
                       value="<?php echo htmlspecialchars($email ?? ''); ?>" placeholder="Email" required>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <input type="submit" name="btn-submit" value="Update Profile" class="btn-submit">
            </div>
        </form>
    </div>
</body>
</html>
