<?php
$servername = "localhost";
$username = "sophiaK";
$password = "1234";
$dbname = "COP4331";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => $conn->connect_error]));
}
?>
