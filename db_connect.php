<?php
$host = "localhost"; 
$username = "root";      
$password = "Uday@2006";  
$dbname = "foodfusion";  

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>