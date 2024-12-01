<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/signup.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Sign Up</title>
</head>
<body>

<header>
    <h1>Sign up</h1>
    <p>Create your account</p>
</header>

<div class="container">
    <form method="post">
        <div class="input-group">
            <i class="fa-solid fa-user" id="userIcon"></i>
            <input type="text" name="username" placeholder="User Name" required>
        </div>
        <br>
        <div class="input-group">
            <i class="fa-solid fa-id-card" id="userIcon"></i>
            <input type="text" name="userid" placeholder="User ID" required>
        </div>
        <br>
        <div class="input-group">
            <i class="fa-solid fa-lock" id="userIcon"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <br>
        <div class="input-group">
            <i class="fa-solid fa-envelope" id="userIcon"></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <br>
        <div class="input-group">
            <i class="fa-solid fa-image" id="userIcon"></i>
            <input type="file" name="profile_image" accept="image/*" required>
        </div>
        <br>
        <div class="button-container">
            <button class="signup-button" name="sbtn">Sign Up</button>
            <p class="login-text">
                Already have an account? <a href="login.php">Login</a>
            </p>
        </div>
    </form>
</div>

<?php
if (isset($_POST["sbtn"])) {
    $username = $_POST["username"];
    $userid = $_POST["userid"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $userType = "user"; // Default user type

    // Handle file upload
    $profileImage = $_FILES["profile_image"]; // Initialize properly
    $imageData = null; // Default image data

    // Check if file was uploaded without errors
if (isset($profileImage) && $profileImage['error'] === UPLOAD_ERR_OK) {
    $imageData = file_get_contents($profileImage['tmp_name']); // Read file content
} else {
    echo "<script>alert('File upload error. Please try again.');</script>";
}



    // Validate email format
    if (!preg_match('/^\d{9}@(stu\.uob\.edu\.bh|uob\.edu\.bh)$/', $email)) {
        echo "<script>alert('Invalid email format. Use: id_number@stu.uob.edu.bh or id_number@uob.edu.bh');</script>";
    } else {
        try {
            require("connection.php");

            // Check for duplicate userid or email
            $checkQuery = "SELECT * FROM users WHERE userid = :userid OR email = :email";
            $checkStmt = $db->prepare($checkQuery);
            $checkStmt->bindParam(":userid", $userid);
            $checkStmt->bindParam(":email", $email);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                echo "<script>alert('User ID or Email already exists. Please use a different one.');</script>";
            } else {
                // Insert new user
                $query = "INSERT INTO users (username, userid, password, email, usertype, profile_image) 
                          VALUES (:username, :userid, :password, :email, :usertype, :profile_image)";
                $stmt = $db->prepare($query);

                // Hash the password
                $hps = password_hash($password, PASSWORD_DEFAULT);

                // Bind parameters
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":userid", $userid);
                $stmt->bindParam(":password", $hps);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":usertype", $userType);
                $stmt->bindParam(":profile_image", $imageData, PDO::PARAM_LOB);

                // Execute the query
                if ($stmt->execute()) {
                    echo "<script>alert('Registered successfully!'); window.location.href = 'login.php';</script>";
                } else {
                    echo "<script>alert('An error occurred. Please try again.');</script>";
                }
            }

            $db = null; // Close the database connection

        } catch (PDOException $e) {
            echo "<script>alert('Database error: " . $e->getMessage() . "');</script>";
        }
    }
}





echo '<pre>';
print_r($_FILES);
echo '</pre>';
?>


</body>
</html>


