<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post">
    <label for="">User Name</label>
    <input type="text" name='fname'>
    <br><br>
    <label for="">email</label>
    <input type="text" name='email'>
    <br><br>
    <label for="">Password</label>
    <input type="password" name='password'>
    <br><br>
    <label for="">Address</label>
   <textarea name="address"></textarea>
    <br><br>
    <label for="">Phone</label>
    <input type="text" name='phone'>
    <br><br>
    <label for="">userType</label>
    <input type="text" name='type'>

    <br><br>
    <button name="sbtn">Submit</button>
    </form>
</body>
</html>



<?php

if(isset($_POST["sbtn"]) ){
    $fname= $_POST["fname"];
    $lname= $_POST["lname"];
    $email=$_POST["email"];
    $password=$_POST["password"];
    $address=$_POST["address"];
    $phone=$_POST["phone"];
    $userType=$_POST["type"];


      try {
        require("connection.php");

        $query="INSERT INTO users VALUES (null,:fname,:lname,:email,:password,:address,:phone,null,null,:type)";
        $stmt=$db->prepare($query);

        $hps=password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(":fname",$fname);
        $stmt->bindParam(":lname",$lname);
        $stmt->bindParam(":email",$email);
        $stmt->bindParam(":password",$hps);
        $stmt->bindParam(":address",$address);
        $stmt->bindParam(":phone",$phone);
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
