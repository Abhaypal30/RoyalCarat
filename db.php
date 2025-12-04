<?php
// Database configuration
$host = "localhost";        
$user = "root";            
$password = "";            
$dbname = "royal_carat";  

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If connected successfully
// echo "Connected successfully";
?>
