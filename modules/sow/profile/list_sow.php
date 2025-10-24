<?php
require_once __DIR__ . '/../../../config/db.php';
$stmt = $pdo->query("SELECT * FROM sows ORDER BY created_at DESC");
$sows = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sow Profiles</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">🐖 Sow Profile Records</h2>
  <a href="add_sow.php" class="btn btn-success mb-3">➕ Add Sow</a>
  <table class="table table-bordered table-striped">
    <thead class="table-dark text-center">
      <tr>
        <th>ID</th>
        <th>Ear Tag</th>
        <th>Breed</th>
        <th>DOB</th>
        <th>Source</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody class="text-center">
      <?php foreach ($sows as $sow): ?>
      <tr>
        <td><?= $sow['sow_id'] ?></td>
        <td><?= htmlspecialchars($sow['ear_tag_no']) ?></td>
        <td><?= htmlspecialchars($sow['breed_line']) ?></td>
        <td><?= htmlspecialchars($sow['date_of_birth']) ?></td>
        <td><?= htmlspecialchars($sow['source']) ?></td>
        <td><?= htmlspecialchars($sow['status']) ?></td>
        <td>
          <a href="view_sow.php?id=<?= $sow['sow_id'] ?>" class="btn btn-info btn-sm">View</a>
          <a href="edit_sow.php?id=<?= $sow['sow_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="delete_sow.php?id=<?= $sow['sow_id'] ?>" class="btn btn-danger btn-sm"
             onclick="return confirm('Are you sure you want to delete this sow?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
