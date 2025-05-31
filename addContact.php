<?php
include 'dbConnection.php';

$data = json_decode(file_get_contents("php://input"), true);
$firstName = $data["FirstName"];
$lastName = $data["LastName"];
$phone = $data["Phone"];
$email = $data["Email"];
$userId = $data["UserId"];

$stmt = $conn->prepare("INSERT INTO Contacts (FirstName, LastName, Phone, Email, UserId) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $firstName, $lastName, $phone, $email, $userId);
$stmt->execute();

$data["Id"] = $stmt->insert_id;
echo json_encode($data);

$stmt->close();
$conn->close();
?>
