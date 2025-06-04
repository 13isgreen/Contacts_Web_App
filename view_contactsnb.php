<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    die("Access denied. Please log in.");
}

include 'db.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT FirstName, LastName, Email, Phone FROM Contacts WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Contacts - Contact Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
<style>
  .contacts-content {
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    color: #fff;
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
    max-width: 900px;
    margin: auto;
  }

  .contacts-content table {
    color: #fff;
  }

  .contacts-content .table thead {
    background-color: rgba(255, 255, 255, 0.1);
  }

  .contacts-content .table tbody tr {
    background-color: rgba(255, 255, 255, 0.05);
  }

  .contacts-content .table-hover tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.15);
  }

  .contacts-content .btn {
    font-weight: bold;
  }
</style>
</head>
<body>
<div class="studio-image">
  <nav class="navbar navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="dashboard.html">Dashboard</a>
    <a class="btn btn-outline-light ms-2" href="login.html">Logout</a>
  </nav>

  <div class="heroframe-text d-flex flex-column justify-content-center align-items-center">
    <div class="contacts-content text-center">
      <h2 class="mb-4">Your Contacts</h2>

      <table class="table table-striped table-hover">
        <thead class="table-dark">
          <tr>
            <th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['FirstName']) ?></td>
              <td><?= htmlspecialchars($row['LastName']) ?></td>
              <td><?= htmlspecialchars($row['Email']) ?></td>
              <td><?= htmlspecialchars($row['Phone']) ?></td>
              <td>
                  <a href="edit_contactnb.php?id=<?= $row['ID'] ?>" class="btn btn-sm btn-primary me-1">Edit</a>
                  <a href="delete_contactnb.php?id=<?= $row['ID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this contact?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <a href="add_contacts.html" class="btn btn-outline-secondary mt-3">Add Contact</a>
    </div>
  </div>
</div>
</body>
</html>
