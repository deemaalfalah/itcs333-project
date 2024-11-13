<?php
$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "login_regesteration";
$conn = mysqli_connect($hostName , $dbUser , $dbPassword , $dbName);
if(!$conn){
        die("Something went wrong"); //we did this to check if our database is connected correctly or not
}

?>