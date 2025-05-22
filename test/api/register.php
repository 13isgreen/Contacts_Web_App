<?php
require_once(__DIR__ . "/db.php");

$inData = json_decode(file_get_contents("php://input"), true);

$firstName = $inData["firstName"];
$lastName = $inData["lastName"];
$login = $inData["login"];
$password = $inData["password"];

$stmt = $conn->prepare("SELECT ID FROM Users WHERE Login=?");
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->fetch_assoc()) {
    echo json_encode(["id" => 0, "error" => "User already exists"]);
} else {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO Users (FirstName, LastName, Login, Password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstName, $lastName, $login, $hashedPassword);
    $stmt->execute();
    echo json_encode(["id" => $stmt->insert_id, "error" => ""]);
}
$conn->close();
?>