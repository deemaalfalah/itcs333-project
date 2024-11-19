<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
    <link rel="stylesheet" href="styles/add_room.css" />
    
            </head>

            <body>
        <!-- Sidebar -->
    <!-- <div class="sidebar">
        <div class="admin-profile">
            <img src="https://placehold.co/600x400/gray/white" alt="Admin Picture">
            <h3>Admin Name</h3>
        </div>
        <a href="my_account.php">My Account</a>
        <a href="logout.php">Logout</a>
    </div>
<div class="navbar">
        
        <div class="search-bar">
            <input type="text" placeholder="Search...">
        </div>
        <a href="add_class.php">Add Room</a>
        <a href="view_classes.php">View Classes</a>
    </div> -->

    <!-- Search bar -->
    
    <div class="form-container">

    <fieldset>
        <legend>Add Room</legend>
        <form method="post">
        
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


</fieldset>


<!-- Pop up modal -->
<div id="myModal" class="modal" style="<?php echo isset($showModal) && $showModal ? 'display:flex;' : 'display:none;'; ?>">
    <div class="modal-content">
      <span class="close">&times;</span>
      <div class="modal-body">
        <i class="fa-solid fa-circle-check icons"></i>
        <p class="modal-message"><?php echo isset($modalMessage) ? $modalMessage : ''; ?></p>
      </div>
      <div class="modal-footer">
        <button id="closeModal">Close</button>
      </div>
    </div>
  </div>

                <footer>
      <div class="left-footer">
        <i class="fa-solid fa-circle-question"></i>
        <a href="contact-us.php">contact us</a>
      </div>
      <div class="center">
        <a href="">Terms & Conditions</a>
        <p>|</p>
        <p>@2024 mark</p>
        <p>|</p>
        <!-- <br /> -->
        <a href="">Privacy & Policy</a>
      </div>
      <div class="right-footer">
        <i class="fa-solid fa-circle-info"></i>
      </div>
    </footer>
   <script src="script/pop-up.js"></script>
     <script src="script/header.js"></script>
  
</body>
</html>





<?php

//  session_start();
// if(!isset($_SESSION["currentUser"])||$_SESSION["userType"]=="user"){
//   header("location:login.php");
// }

    if( isset($_POST['sbtn'])){
        $modalMessage="";
        $showModal = false;
        try 

        {

            require("connection.php");
            $db->beginTransaction();

            if (isset($_POST["sbtn"])) {
                $department = $_POST["department"];
                $room_number = $_POST["room_number"];
                $capacity = $_POST["capacity"];
                $lab = isset($_POST["lab"]) ? $_POST["lab"] : "null";
                $smartboard = isset($_POST["smartboard"]) ? $_POST["smartboard"] : "null";
                $datashow = isset($_POST["datashow"]) ? $_POST["datashow"] : "null";

            $sql = "INSERT INTO rooms 
            (departmen,room_numb,capacity,lab,smartboar,datashow) VALUES (:departmen,:room_numb,:capacity,:lab,:smartboar,:datashow)";
            $stmt = $db->prepare($sql);

            $stmt->bindParam(":department",$department);
            $stmt->bindParam(":room_number",$room_number);
            $stmt->bindParam(":capacity",$capacity);
            $stmt->bindParam(":lab",$lab);
            $stmt->bindParam(":smartboard",$smartboard);
            $stmt->bindParam(":datashow",$datashow);
            $stmt->execute();

            $Book_ID = $db->lastInsertId();

        

          if($db->commit()){
            $modalMessage = "room added successfully!";
            $showModal = true;

          } else {
            $modalMessage = "There was an error adding the rooms.";
            $showModal = true;
           }
        
        

        }
    }
        catch(PDOException $e) 
        {
            $db->rollBack();
            die("Error " . $e->getMessage());
        }
    }
    
?>
