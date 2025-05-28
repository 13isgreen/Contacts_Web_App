<?php
$servername = "localhost";
$username = "nathanB";        // change if needed
$password = "Group13Pass";            // set your MySQL root password
$dbname = "COP4331";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
