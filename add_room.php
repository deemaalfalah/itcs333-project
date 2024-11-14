
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/add_room.css">
    <title>Add Room</title>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="admin-profile">
            <img src="path_to_admin_image.jpg" alt="Admin Picture">
            <h3>Admin Name</h3>
        </div>
        <a href="my_account.php">My Account</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main content area -->
    <div class="main-content">
        
            <!-- Search bar -->
            <div class="search-bar">
                <input type="text" placeholder="Search...">
            </div>

            <!-- Navbar -->
        <div class="navbar">
            <a href="add_class.php">Add Class</a>
            <a href="view_classes.php">View Classes</a>
        </div>

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
            <input type="checkbox" id="lab" name="features[]" value="Lab">
            
            
            <label for="smartboard">Smartboard</label>
            <input type="checkbox" id="smartboard" name="features[]" value="Smartboard">

            
            <label for="datashow">Datashow</label>
            <input type="checkbox" id="datashow" name="features[]" value="Datashow">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="submit-btn">Add Room</button>
    </form>
</div>

        
    </div>



</body>
</html>

