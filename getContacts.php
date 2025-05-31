<?php
include 'dbConnection.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$filter = $conn->real_escape_string($filter);

$sql = $filter === '' ?
    "SELECT * FROM Contacts" :
    "SELECT * FROM Contacts WHERE FirstName LIKE '$filter%' OR LastName LIKE '$filter%'";

$result = $conn->query($sql);

$contacts = [];
while ($row = $result->fetch_assoc()) {
    $contacts[] = $row;
}

echo json_encode($contacts);
$conn->close();
?>
