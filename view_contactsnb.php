<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$userId = $_SESSION['user_id'];
$search = $_GET['search'] ?? '';

// If there's a search term, use LIKE
if (!empty($search)) {
    $searchTerm = "%{$search}%";
    $stmt = $conn->prepare("SELECT * FROM Contacts WHERE UserID = ? AND (
        FirstName LIKE ? OR LastName LIKE ? OR Email LIKE ? OR Phone LIKE ?
    )");
    $stmt->bind_param("issss", $userId, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
} else {
    $stmt = $conn->prepare("SELECT * FROM Contacts WHERE UserID = ?");
    $stmt->bind_param("i", $userId);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Contacts - Contact Manager</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    .table td, .table th {
      vertical-align: middle;
    }
  </style>
</head>
<body>
  <div class="studio-image">

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark bg-dark px-4">
      <a class="navbar-brand" href="dashboard.html">Dashboard</a>
      <div class="ms-auto">
        <a href="index.html" class="btn btn-outline-light">Logout</a>
      </div>
    </nav>

    <!-- SEARCH -->
    <form method="GET" class="mb-4 w-75 mx-auto">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Search contacts..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
      <button class="btn btn-outline-light btn-sm btn-dark" type="submit">Search</button>
      <a href="view_contactsnb.php" class="btn btn-outline-secondary btn-sm">Reset</a>
    </div>
    </form>

    <!-- CONTACTS PANEL -->
    <div class="heroframe-text d-flex flex-column justify-content-center align-items-center">
      <div class="contacts-content text-center w-100">
        <h2 class="mb-4">Your Contacts</h2>
        <div class="table-responsive w-75 mx-auto">
          <table class="table table-striped table-hover table-bordered bg-white text-dark">
            <thead class="table-dark">
              <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['FirstName']); ?></td>
                <td><?php echo htmlspecialchars($row['LastName']); ?></td>
                <td><?php echo htmlspecialchars($row['Email']); ?></td>
                <td><?php echo htmlspecialchars($row['Phone']); ?></td>
                <td>
                  <a href="edit_contactnb.php?id=<?php echo $row['ID']; ?>" class="btn btn-sm btn-primary me-1">Edit</a>
                  <a href="delete_contactnb.php?id=<?php echo $row['ID']; ?>" class="btn btn-sm btn-danger"
                     onclick="return confirm('Are you sure you want to delete this contact?')">Delete</a>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <a href="add_contacts.html" class="btn btn-success mt-3">Add Contact</a>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
