<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db.php';

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

if (!$login || !$password) {
    die("Login and password are required.");
}

// Fetch the user
$sql = "SELECT ID, Password FROM Users WHERE Login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $storedPassword = $row['Password'];

    // OPTION 1: Use password_verify() for hashed passwords
    // OPTION 2: Use plain text comparison if not hashed
    if (
        password_verify($password, $storedPassword) ||  // hashed mode
        $password === $storedPassword                   // plaintext mode
    ) {
        // Success! Redirect to dashboard
        header("Location: index.html");
        exit();
    } else {
        echo "Invalid login or password.";
    }
} else {
    echo "User not found.";
}

$conn->close();
?>
