<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/signup.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Document</title>
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
      <input type="text" name="username" placeholder="User Name">
    </div>
  <br>

    <div class="input-group">
      <i class="fa-solid fa-id-card" id="userIcon"></i>
      <input type="text" name="userid" placeholder="User ID">
    </div>
  <br>
  
  <div class="input-group">
      <i class="fa-solid fa-lock" id="userIcon"></i>
      <input type="password" name="password" placeholder="Password" 
             pattern="^(?=.*[a-zA-Z])(?=.*\d)(?=.*[?_!~])[a-zA-Z\d?_!~]{8,}$"
             title="Password must include at least 1 letter, 1 number, and 1 special character (?_!~), and be at least 8 characters long" 
             required>
    </div>
  <br>


  <div class="input-group">
      <i class="fa-solid fa-envelope" id="userIcon"></i>
      <input type="text" name="email" placeholder="Email">
    </div>
  <br>

  <div class="input-group">
  <i class="fa-solid fa-user-tie" id="userIcon"></i>
      <input type="text" name="type" placeholder="User Type (Admin or User)">
    </div>
  <br>

  <!-- <div class="input-group">
            <i class="fa-solid fa-image" id="userIcon"></i>
            <input type="file" name="profile_image" accept="image/*" required>
        </div>
        <br> -->
    
    <div class="button-container">
        <button class="signup-button" name="sbtn">Sign Up</button>
        <p class="login-text">
            Already have an account? <a href="login.php">Login</a>
        </p>
    </div>
    
    </form>
    </div>

    
</body>
</html>



<?php

if(isset($_POST["sbtn"]) ){
    $username= $_POST["username"];
    $userid= $_POST["userid"];
    $email=$_POST["email"];
    $password=$_POST["password"];
    $userType=$_POST["type"];
    
    // Handle file upload for image
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
      $imageData = file_get_contents($_FILES['profile_image']['tmp_name']);
  } else {
      $imageData = null;
  }


    if (empty($username) || empty($userid) || empty($email) || empty($password) || empty($userType)) {
      echo "<script>alert('All fields are required.');</script>";
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

        $query="INSERT INTO users VALUES (:username,:userid,:password,:email,:type,:profile_image)";
        $stmt=$db->prepare($query);

        $hps=password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(":username",$username);
        $stmt->bindParam(":userid",$userid);
        $stmt->bindParam(":password",$hps);
        $stmt->bindParam(":email",$email);
        $stmt->bindParam(":type",$userType);
        $stmt->bindParam(":profile_image", $imageData, PDO::PARAM_LOB);

    
      // Execute the query
      if ($stmt->execute()) {
        echo "<script>alert('Registered successfully!'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('An error occurred. Please try again.');</script>";
    }
    
    }
      $db=null;

      } 
      
      catch (PDOEXCEPTION $e) {
        die("Error ".$e->getMessage());
      }
  }
}
  


?>