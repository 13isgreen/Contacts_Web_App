<?php
require 'db.php';

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM Contacts WHERE ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$contact = $result->fetch_assoc();

if (!$contact) {
    echo "Contact not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Contact - Contact Manager</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="studio-image">
  <nav class="navbar navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="dashboard.html">Dashboard</a>
  </nav>

  <div class="heroframe-text d-flex flex-column justify-content-center align-items-center">
    <div class="form-box text-start">
      <h2 class="text-center mb-4">Edit Contact</h2>
      <form method="POST" action="update_contactnb.php">
        <input type="hidden" name="ID" value="<?= htmlspecialchars($contact['ID']) ?>">
        <div class="mb-3">
          <label class="form-label">First Name</label>
          <input type="text" name="FirstName" class="form-control" value="<?= htmlspecialchars($contact['FirstName']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Last Name</label>
          <input type="text" name="LastName" class="form-control" value="<?= htmlspecialchars($contact['LastName']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="Email" class="form-control" value="<?= htmlspecialchars($contact['Email']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Phone</label>
          <input type="text" name="Phone" class="form-control" value="<?= htmlspecialchars($contact['Phone']) ?>" required>
        </div>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary btn-lg">Update Contact</button>
          <a href="view_contactsnb.php" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
