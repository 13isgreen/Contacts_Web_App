<?php
include 'db.php';

$login = $_POST['login'];
$password = $_POST['password'];

$sql = "SELECT ID, Password FROM Users WHERE Login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['Password'])) {
        // Login success – Redirect to dashboard
        header("Location: index.html");
    } else {
        echo "Invalid password.";
    }
} else {
    echo "User not found.";
}

$conn->close();
?>
