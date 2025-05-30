<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access denied. Please log in.");
}

include 'db.php';

$firstName = $_POST['FirstName'] ?? '';
$lastName = $_POST['LastName'] ?? '';
$email = $_POST['Email'] ?? '';
$phone = $_POST['Phone'] ?? '';
$userId = $_SESSION['user_id'];

// Validate inputs (basic check)
if (!$firstName || !$lastName || !$email || !$phone) {
    die("All fields are required.");
}

// Prepare and execute insert statement
$sql = "INSERT INTO Contacts (FirstName, LastName, Email, Phone, UserID) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $firstName, $lastName, $email, $phone, $userId);

if ($stmt->execute()) {
    header("Location: view_contacts.php");
    exit();
} else {
    echo "Error adding contact: " . $stmt->error;
}

$conn->close();
?>
