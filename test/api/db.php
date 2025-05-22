<?php
$DB_HOST = "localhost";
$DB_USER = "your_username";
$DB_PASS = "your_password";
$DB_NAME = "COP4331";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}
?>