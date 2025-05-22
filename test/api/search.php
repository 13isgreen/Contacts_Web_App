<?php
require_once(__DIR__ . "/db.php");

$inData = json_decode(file_get_contents("php://input"), true);
$search = $inData["search"] ?? "";

$search = "%" . $search . "%";

$stmt = $conn->prepare("SELECT ID, FirstName, LastName, Login FROM Users WHERE FirstName LIKE ? OR LastName LIKE ? OR Login LIKE ?");
$stmt->bind_param("sss", $search, $search, $search);
$stmt->execute();
$result = $stmt->get_result();

$results = [];
while ($row = $result->fetch_assoc()) {
    $results[] = $row;
}

echo json_encode(["results" => $results]);

$conn->close();
?>