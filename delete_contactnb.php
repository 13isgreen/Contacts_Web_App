<?php
require 'db.php';

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM Contacts WHERE ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: view_contactsnb.php");
exit;

