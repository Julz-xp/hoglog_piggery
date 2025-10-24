<?php
require_once __DIR__ . '/../../../config/db.php';

$stmt = $pdo->query("
  SELECT d.*, s.ear_tag_no, s.breed_line
  FROM sow_dry_stage d
  LEFT JOIN sows s ON d.sow_id = s.sow_id
  ORDER BY d.dry_id DESC
");
$records = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dry Sow Records</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">🐷 Dry Sow Stage Records</h2>
  <a href="add_dry.php" class="btn btn-success mb-3">➕ Add Record</a>
  <table class="table table-bordered text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Sow (Ear Tag)</th>
        <th>Weaning Date</th>
        <th>Heat Detection Date</th>
        <th>Interval (days)</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($records): foreach ($records as $r): ?>
      <tr>
        <td><?= $r['dry_id'] ?></td>
        <td><?= htmlspecialchars($r['ear_tag_no']) ?></td>
        <td><?= htmlspecialchars($r['weaning_date']) ?></td>
        <td><?= htmlspecialchars($r['heat_detection_date']) ?></td>
        <td><?= htmlspecialchars($r['weaning_estrus_interval']) ?></td>
        <td>
          <a href="view_dry.php?id=<?= $r['dry_id'] ?>" class="btn btn-info btn-sm">View</a>
          <a href="edit_dry.php?id=<?= $r['dry_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="delete_dry.php?id=<?= $r['dry_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="6">No records found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
