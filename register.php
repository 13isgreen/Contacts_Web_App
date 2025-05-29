<?php
include 'db.php';

$firstName = $_POST['FirstName'];
$lastName = $_POST['LastName'];
$login = $_POST['login'];
$password = $_POST['password'];

// Hash password before storing
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO Users (FirstName, LastName, Login, Password)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $firstName, $lastName, $login, $hashedPassword);

if ($stmt->execute()) {
    header("Location: login.html");
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
