<?php
session_start();

// Email validation regex
$useridReg = "/\d{8,}/";

// Form submission logic


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/login.css">
    <title>Login</title>
</head>
<body>  
    <div class="container">
    <div class="side">
    <img src="picture/University_of_Bahrain_logo.png" alt="logo" style="width: 100px; height: auto; margin-bottom: 20px;">


    <p>Welcome to the University of Bahrain<br>
    Dedicated to enhancing your experience with the Information Technology Room System.<br>
    Reserve, manage, and access IT resources with ease.<br>
    Your journey to innovation starts here!</p>
</div>
        <main>
            <div class="login">
                <h1 class='log'>Login</h1>
                <form method="post">
                    <div id="messageBox" style='visibility: hidden;'> 
                        <span id='message'></span>
                    </div>
                    <div class="inputF" id="useridInput">
                        <i class="fa-solid fa-user" id="userIcon"></i>
                        <input onfocus="useridChange(this)" onblur="useridlReset(this)" type="text" class="input" placeholder="User ID" name="userid" value="<?php if(isset($_POST['userid'])) echo $_POST['userid'];?>">
                    </div>
                    <span id='userid' style='color:red;'></span>
                    <div class="inputF" id="passwordInput">
                        <i class="fa-solid fa-lock" id="passIcon"></i>
                        <input onfocus="passChange(this)" onblur="passReset(this)" type="password" class="input" placeholder="Password" name="password">
                    </div>
                    <span id='pass' style='color:red;'></span>
                    <div class="submit">
                        <button type="submit" name="sbtn" class="button">login</button>
                    </div>
                </form>

                <div class="signup-container">
                    <p>Doesn't have an account?</p>
                    <a onclick="location.href='signup-user.php'">
                        <button class="signup-button">Sign Up</button>
                    </a>
                </div>

                <div class="info">
                    <p>
                        <a onclick="location.href='contact-us.php'" >Contact us</a> 
                        <span>|</span>
                        <a href="">About us</a>
                        
                    </p>
                </div>
            </div>
        </main>
    </div>
    <?php
    if (isset($_POST['sbtn'])) {
        print_r($_POST['sbtn']);
      // Check for empty fields
      $print = false;
      $userid = trim($_POST['userid']);
      $userPassword = trim($_POST['password']);
  
      if ($userid == "") {
          echo "<script>document.getElementById('userid').innerHTML='* User ID is required'; document.getElementById('useridInput').style.borderBottomColor = 'red';</script>";
          $print = true;
      }
      if ($userPassword == "") {
          echo "<script>document.getElementById('pass').innerHTML='* Password is required'; document.getElementById('passwordInput').style.borderBottomColor = 'red';</script>";
          $print = true;
      }
      if (!$print) {
          // If the userid is valid
          if (preg_match($useridReg, $userid)) {
              try {
                  require('connection.php');
                  $sql = "SELECT * FROM users WHERE userid = ?";
                  $result = $db->prepare($sql);
                  $result->execute(array($userid));
                  $db = null;
              } catch (PDOException $e) {
                  die($e->getMessage());
              }
  
              $count = $result->rowCount();
              $row = $result->fetch(PDO::FETCH_ASSOC);
              if ($count == 1) {
                  // Check if the password is valid
        
                  if (password_verify($userPassword, $row['password'])) {
                      $_SESSION['currentUser'] = $row["userid"];
                      $_SESSION['userType'] = $row["usertype"];
  
                      if ($row["usertype"] == 'user') {
                          header('Location: dashboard-user.php');
                          exit();
                      } else if ($row["usertype"] == 'admin') {
                          header('Location: dashboard-admin.php');
                          exit();
                      } 
                  } else {
                      echo "<script>document.getElementById('message').innerHTML='User ID or password is not valid';
                                     document.getElementById('messageBox').style.visibility = 'visible'</script>";
                  }
              } else {
                  echo "<script>document.getElementById('message').innerHTML='User ID or password is not valid';
                                    document.getElementById('messageBox').style.visibility = 'visible';</script>";
              }
          } else {
              echo "<script>document.getElementById('message').innerHTML='User ID or password is not valid';
                            document.getElementById('messageBox').style.visibility = 'visible';</script>";
          }
      }
  }
  
    
    ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Change the color of the icon and the underline when clicking on the input field
        function emailChange(input) {
            const element = document.getElementById('useridInput');
            const icon = document.getElementById('userIcon');
            element.style.borderBottomColor = '#a9856c';
            icon.style.color = '#a9856c';
        }

        function emailReset(input) {
            const element = document.getElementById('useridInput');
            const icon = document.getElementById('userIcon');
            element.style.borderBottomColor = '#757575';
            icon.style.color = '#757575';
        }
        
        function passChange(input) {
            const element = document.getElementById('passwordInput');
            const icon = document.getElementById('passIcon');
            element.style.borderBottomColor = '#a9856c';
            icon.style.color = '#a9856c';
        }

        function passReset(input) {
            const element = document.getElementById('passwordInput');
            const icon = document.getElementById('passIcon');
            element.style.borderBottomColor = '#757575';
            icon.style.color = '#757575';
        }
    </script>
</body>
</html> 