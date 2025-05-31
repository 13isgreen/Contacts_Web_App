<?php
include 'db.php';

$id = $_GET['id'];
$data = json_decode(file_get_contents("php://input"), true);

$firstName = $data["FirstName"];
$lastName = $data["LastName"];
$phone = $data["Phone"];
$email = $data["Email"];
$userId = $data["UserId"];

$stmt = $conn->prepare("UPDATE Contacts SET FirstName=?, LastName=?, Phone=?, Email=?, UserId=? WHERE Id=?");
$stmt->bind_param("ssssii", $firstName, $lastName, $phone, $email, $userId, $id);
$stmt->execute();

echo json_encode(["message" => "Contact updated"]);

$stmt->close();
$conn->close();
?>
