<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/signup.css">

    <title>Document</title>
</head>
<body>
    <form method="post">
    <label for="">User Name</label>
    <input type="text" name='username'>
    <br><br>
    <label for="">User ID</label>
    <input type="text" name='userid'>
    <br><br>
    <label for="">Password</label>
    <input type="password" name='password'>
    <br><br>
    <label for="">email</label>
    <input type="text" name='email'>
    <br><br>

    
    <label for="">UserType</label>
    <input type="text"  name="type" >
    

    <br><br>
    <button name="sbtn">Submit</button>
    </form>
</body>
</html>



<?php

if(isset($_POST["sbtn"]) ){
    $username= $_POST["username"];
    $userid= $_POST["userid"];
    $email=$_POST["email"];
    $password=$_POST["password"];
    $userType=$_POST["type"];


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
  
?>
