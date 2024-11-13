<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/pico.min.css">
    <link rel="stylesheet" href="styles/regesteration.css">
    <title>Regestration Form</title>
</head>
<body>
    <div class="container">

        <!-- if(if($_SERVER["REQUEST_METHOD" == "POST"])){
        } -->
        <?php
            if(isset($_POST["submit"]))
            {  //if the user clicked in submit button it will save his data in these variables so we can later save them in the data base
                $fullname = $_POST["fullname"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $repeat_password = $_POST["repeat_password"];
                $passwordHash = password_hash($password , PASSWORD_DEFAULT); //to hide the password and make it secure
                $errors = array();
                if(empty($fullname) OR empty($email) OR empty($password) OR empty($repeat_password))
                { //if when the user clicked regester, one of the fields was empty then an error will be added to the error array
                    array_push($errors , "All fields are requiered");
                }

                if(!filter_var($email , FILTER_VALIDATE_EMAIL))
                {//if when the user clicked regester, the email was unvalid and the filter var returns false
                    array_push($errors , "Email is not valid");
                }
                if(strlen($password)<8)
                {
                    array_push($errors, "Password must be 8 or more characters");
                }
                if($password !== $repeat_password)
                {
                    array_push($errors, "Passwords does not match");
                }
                require_once "database.php";
                $sql= "SELECT * FROM users WHERE email = '$email'";//this will select all the emails in the database that equals the inputed email
                $result = mysqli_query($conn, $sql);//The function mysqli_query() is used to send a query to the MySQL database. 
                //It executes the SQL query that was created in the variable $sql
                $rowCount = mysqli_num_rows($result);
                if($rowCount >0 ){
                    array_push($errors , "Email already exist");
                }

                if (count($errors)>0){
                    foreach($errors as $error){
                        if($error == "Email is not valid"){
                            echo "<div class='error-green'> $error </div>" ;
                        }else{
                                
                        }
                    }
                }else{
                    // require_once "database.php";
                    $sql = "INSERT INTO users(fullname, email, password) VALUES (? , ? , ?)";
                    $stmt = mysqli_stmt_init($conn); //This initializes a prepared statement object using the database connection $conn.
                    // The prepared statement object ($stmt) will later be used to execute the SQL query safely with the provided parameters.
                    $prepareStmt = mysqli_stmt_prepare($stmt , $sql); //This prepares the SQL statement ($sql) for execution. It associates the 
                    //SQL query with the prepared statement object ($stmt).
                    if($prepareStmt){
                        mysqli_stmt_bind_param($stmt , "sss" , $fullname , $email , $passwordHash );
                        mysqli_stmt_execute($stmt);//This executes the prepared statement with the bound parameters,
                        // meaning it will run the SQL query with the provided values. In this case, it will insert a new row into the users table.
                        echo "<div class='error-green'> You loged in successfully! </div>" ;
                    }
                    else{
                        die("Some thing went wrong");
                    }
                }

            }

        ?>


        <form method="post" action="regesteration.php">
            <div class="form-group">
                 <input type="text" name="fullname" placeholder="Full Name ">
            </div>

            <div class="form-group">
                 <input type="email" name="email" placeholder="Email ">
            </div>

            <div class="form-group">
                 <input type="password" name="password" placeholder="Password ">
            </div>

            <div class="form-group">
                 <input type="password" name="repeat_password" placeholder="Repeat Password ">
            </div>

            <div class="form-group">
                 <input type="submit" name="submit" value="Regester">
            </div>
        </form>
    </div>
</body>
</html>