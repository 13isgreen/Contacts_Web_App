<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$userId = $_SESSION['user_id'];
$contactsPerPage = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $contactsPerPage;
$search = $_GET['search'] ?? '';

// Count total contacts for pagination
if (!empty($search)) {
    $searchTerm = "%{$search}%";
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM Contacts WHERE UserID = ? AND (
        FirstName LIKE ? OR LastName LIKE ? OR Email LIKE ? OR Phone LIKE ?
    )");
    $countStmt->bind_param("sssss", $userId, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
} else {
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM Contacts WHERE UserID = ?");
    $countStmt->bind_param("i", $userId);
}
$countStmt->execute();
$totalContacts = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalContacts / $contactsPerPage);

// Fetch paginated contacts
if (!empty($search)) {
    $searchTerm = "%{$search}%";
    $stmt = $conn->prepare("SELECT * FROM Contacts WHERE UserID = ? AND (
        FirstName LIKE ? OR LastName LIKE ? OR Email LIKE ? OR Phone LIKE ?
    ) LIMIT ? OFFSET ?");
    $stmt->bind_param("issssii", $userId, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $contactsPerPage, $offset);
} else {
    $stmt = $conn->prepare("SELECT * FROM Contacts WHERE UserID = ? LIMIT ? OFFSET ?");
    $stmt->bind_param("iii", $userId, $contactsPerPage, $offset);
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
    .table-box {
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
      color: white;
      width: 90%;
      max-width: 1000px;
    }
    .table-box input {
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
    }
    .table-box input::placeholder {
      color: #ccc;
    }
  </style>
</head>
<body>
  <div class="studio-image">
    <nav class="navbar navbar-dark bg-dark px-4">
      <a class="navbar-brand" href="dashboard.html">Dashboard</a>
      <div class="ms-auto">
        <a href="index.html" class="btn btn-outline-light">Logout</a>
      </div>
    </nav>

    <div class="heroframe-text d-flex flex-column justify-content-center align-items-center">
      <div class="table-box text-start">
        <form method="GET" class="mb-3">
          <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search contacts..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button class="btn btn-outline-light" type="submit">Search</button>
            <a href="view_contactsnb.php" class="btn btn-outline-secondary">Reset</a>
          </div>
        </form>

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

        <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
          <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>">
                  <?= $i ?>
                </a>
              </li>
            <?php endfor; ?>
          </ul>
        </nav>
        <?php endif; ?>

        <div class="text-center mt-3">
          <a href="add_contacts.html" class="btn btn-success">Add Contact</a>
        </div>

      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
