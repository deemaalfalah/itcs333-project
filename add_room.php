
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=styles\add_room.css>
    <title>Add Room</title>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="admin-profile">
            <img src="https://placehold.co/600x400/gray/white" alt="Admin Picture">
            <h3>Admin Name</h3>
        </div>
        <a href="my_account.php">My Account</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main content area -->
    <div class="main-content">

            <!-- Navbar -->
        <div class="navbar">
        
            <div class="search-bar">
                <input type="text" placeholder="Search...">
            </div>
            <a href="add_class.php">Add Class</a>
            <a href="view_classes.php">View Classes</a>
        </div>

        <!-- Search bar -->
        
        <div class="form-container">
    <h2>Add Room</h2>
    <form action="" method="">
        
        <!-- Department Field -->
        <div class="form-group">
    <label>Department:</label>
    <div>
        <input type="radio" id="department_is" name="department" value="IS" required>
        <label for="department_is">IS</label>
    </div>
    <div>
        <input type="radio" id="department_cs" name="department" value="CS">
        <label for="department_cs">CS</label>
    </div>
    <div>
        <input type="radio" id="department_ce" name="department" value="CE">
        <label for="department_ce">CE</label>
    </div>
</div>


        
        <!-- Room Number Field -->
        <div class="form-group">
            <label for="room_number">Room Number:</label>
            <input type="text" id="room_number" name="room_number" required>
        </div>
        
        <!-- Capacity Field -->
        <div class="form-group">
            <label for="capacity">Capacity:</label>
            <input type="number" id="capacity" name="capacity" min="1" required>
        </div>
        
        <!-- Checkbox Fields -->
        <div class="form-group">
            
            <label for="lab">Lab</label>
            <input type="checkbox" id="lab" name="lab" value="Lab">
            
            
            <label for="smartboard">Smartboard</label>
            <input type="checkbox" id="smartboard" name="smartboard" value="Smartboard">

            
            <label for="datashow">Datashow</label>
            <input type="checkbox" id="datashow" name="datashow" value="Datashow">
        </div>

        <!-- Submit Button -->
        <button name="sbtn">Submit</button>
    </form>
</div>  
    </div>
</body>
</html>

 <?php
    if(isset($_POST["sbtn"]) ){
        $department= $_POST["department"];
        $room_number= $_POST["room_number"];
        $capacity=$_POST["capacity"];
        $lab=$_POST["lab"];
        $smartboard=$_POST["smartboard"];
        $datashow=$_POST["datashow"];
        
     

     try {
        require("connection.php");

        $query="INSERT INTO rooms VALUES (:department,:room_number,:capacity,:lab,:smartboard,:datashow)";
        $stmt=$db->prepare($query);


        $stmt->bindParam(":department",$department);
        $stmt->bindParam(":room_number",$room_number);
        $stmt->bindParam(":capacity",$capacity);
        $stmt->bindParam(":lab",$lab);
        $stmt->bindParam(":smartboard",$smartboard);
        $stmt->bindParam(":datashow",$datashow);

    
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

