<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch gilt records with sow details
$stmt = $pdo->query("
    SELECT g.*, s.ear_tag_no 
    FROM sow_gilt_stage g
    LEFT JOIN sows s ON g.sow_id = s.sow_id
    ORDER BY g.gilt_id DESC
");
$gilts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Gilt Stage Records</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">🐖 Gilt Stage Records</h2>
  <a href="add_gilt.php" class="btn btn-success mb-3">➕ Add New Gilt Record</a>
  <table class="table table-bordered table-hover text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Sow (Ear Tag)</th>
        <th>Heat Detection</th>
        <th>Breeding Date</th>
        <th>Service Type</th>
        <th>Pregnancy Result</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($gilts): foreach ($gilts as $g): ?>
      <tr>
        <td><?= $g['gilt_id'] ?></td>
        <td><?= htmlspecialchars($g['ear_tag_no']) ?></td>
        <td><?= htmlspecialchars($g['heat_detection_date']) ?></td>
        <td><?= htmlspecialchars($g['breeding_date']) ?></td>
        <td><?= htmlspecialchars($g['service_type']) ?></td>
        <td><?= htmlspecialchars($g['pregnancy_result']) ?></td>
        <td>
          <a href="view_gilt.php?id=<?= $g['gilt_id'] ?>" class="btn btn-info btn-sm">View</a>
          <a href="edit_gilt.php?id=<?= $g['gilt_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="delete_gilt.php?id=<?= $g['gilt_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="7">No Gilt Stage records found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
