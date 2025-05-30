<?php
$servername = "localhost";
$username = "nathanB";        
$password = "Group13Pass";   
$dbname = "COP4331";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
