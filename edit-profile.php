<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/editprofile.css">
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
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <!-- Profile Picture Display and Upload -->
            <div class="form-group">
                <img id="profile-picture-preview" 
                     src="<?php 
                         $default_pic = 'uploads/profile_pictures/default.png';
                         $current_pic = !empty($user_profile['profile_picture']) 
                             ? 'uploads/profile_pictures/' . $user_profile['profile_picture'] 
                             : $default_pic;
                         echo htmlspecialchars($current_pic);
                     ?>" 
                     alt="Profile Picture" class="profile-picture">
                <input type="file" name="profile_picture" accept="image/jpeg,image/png,image/gif" onchange="previewProfilePicture(event)">
            </div>

            <!-- User ID (Read-Only) -->
            <div class="form-group">
                <label for="user_id">User ID</label>
                <input type="text" id="user_id" name="user_id" 
                       value="<?php echo htmlspecialchars($user_profile['id'] ?? ''); ?>" placeholder="8-digit number" required>
            </div>

            <!-- First Name -->
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" 
                       value="<?php echo htmlspecialchars($user_profile['first_name'] ?? ''); ?>" placeholder="First Name" required>
            </div>

            <!-- Last Name -->
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" 
                       value="<?php echo htmlspecialchars($user_profile['last_name'] ?? ''); ?>" placeholder="Last Name" required>
            </div>

            <!-- Phone Number with Country Code Dropdown -->
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <div style="display: flex;">
                    <select id="country_code" name="country_code" required style="width: 80px;">
                        <option value="+973" <?php if (strpos($user_profile['phone_number'] ?? '', '+973') === 0) echo 'selected'; ?>>+973</option>
                        <!-- Add more country codes as needed -->
                    </select>
                    <input type="tel" id="phone_number" name="phone_number" pattern="[0-9]{8}" maxlength="8" required
                           value="<?php echo htmlspecialchars(substr($user_profile['phone_number'] ?? '', -8)); ?>" 
                           placeholder="8-digit number" style="flex: 1;">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <input type="submit" value="Update Profile" class="btn-submit">
            </div>
        </form>
    </div>
</body>
</html>
