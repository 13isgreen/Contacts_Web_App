<?php
include 'dbConnection.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM Contacts WHERE Id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

echo json_encode(["message" => "Contact deleted"]);

$stmt->close();
$conn->close();
?>
