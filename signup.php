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
      <input type="password" name="password" placeholder="Password">
    </div>
  <br>


  <div class="input-group">
      <i class="fa-solid fa-envelope" id="userIcon"></i>
      <input type="text" name="email" placeholder="Email">
    </div>
  <br>

  <div class="input-group">
  <i class="fa-solid fa-user-tie" id="userIcon"></i>
      <input type="text" name="type" placeholder="User Type (Admin or Instructor)">
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

    
</body>
</html>



<?php

if(isset($_POST["sbtn"]) ){
    $username= $_POST["username"];
    $userid= $_POST["userid"];
    $email=$_POST["email"];
    $password=$_POST["password"];
    $userType=$_POST["type"];
    if (empty($username) || empty($userid) || empty($email) || empty($password) || empty($userType)) {
      echo "<script>alert('All fields are required.');</script>";
  } else {
      try {
        require("connection.php");

        $query="INSERT INTO users VALUES (:username,:userid,:password,:email,:type)";
        $stmt=$db->prepare($query);

        $hps=password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(":username",$username);
        $stmt->bindParam(":userid",$userid);
        $stmt->bindParam(":password",$hps);
        $stmt->bindParam(":email",$email);
        $stmt->bindParam(":type",$userType);

    
      if($stmt->execute()){
        echo "<script>document.getElementById('msg').innerHTML='Registered successfully, <a href=\'login.php\'>Login</a>'; </script>";
      }

      $db=null;

      } 
      
      catch (PDOEXCEPTION $e) {
        die("Error ".$e->getMessage());
      }
  }
}
  


?>
