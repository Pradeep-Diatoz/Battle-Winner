<?php 
/*$conn = mysqli_connect("localhost","root","","skyfo9g2_esports_club");*/


$servername = "localhost";
$username = "db_username";
$password = "db_password";
$db = "db_name";
/*$servername = "localhost";
$username = "db_username";
$password = "db_password";
$db = "db_name";*/

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
//echo "Connected successfully";
mysqli_set_charset($conn,"utf8");
?>