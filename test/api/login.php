<?php
require_once(__DIR__ . "/db.php");

$inData = json_decode(file_get_contents("php://input"), true);

$login = $inData["login"];
$password = $inData["password"];

$stmt = $conn->prepare("SELECT ID, FirstName, LastName, Password FROM Users WHERE Login=?");
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row["Password"])) {
        echo json_encode([
            "id" => $row["ID"],
            "firstName" => $row["FirstName"],
            "lastName" => $row["LastName"],
            "error" => ""
        ]);
    } else {
        echo json_encode(["id" => 0, "firstName" => "", "lastName" => "", "error" => "Incorrect password."]);
    }
} else {
    echo json_encode(["id" => 0, "firstName" => "", "lastName" => "", "error" => "User not found."]);
}
$conn->close();
?>