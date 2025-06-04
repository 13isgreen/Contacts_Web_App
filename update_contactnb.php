<?php
require 'db.php';

$id = $_POST['ID'];
$first = $_POST['FirstName'];
$last = $_POST['LastName'];
$email = $_POST['Email'];
$phone = $_POST['Phone'];

$stmt = $conn->prepare("UPDATE Contacts SET FirstName=?, LastName=?, Email=?, Phone=? WHERE ID=?");
$stmt->bind_param("ssssi", $first, $last, $email, $phone, $id);
$stmt->execute();

header("Location: view_contactsnb.php");
exit;
