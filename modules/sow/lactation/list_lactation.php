<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch all lactation records with sow info
$stmt = $pdo->query("
    SELECT l.*, s.ear_tag_no, s.breed_line
    FROM sow_lactation l
    LEFT JOIN sows s ON l.sow_id = s.sow_id
    ORDER BY l.lactation_id DESC
");
$lactations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Lactation Records</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">🐷 Lactation Stage Records</h2>
  <a href="add_lactation.php" class="btn btn-success mb-3">➕ Add Lactation Record</a>
  <table class="table table-bordered text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Sow (Ear Tag)</th>
        <th>Farrowing Date</th>
        <th>Total Born</th>
        <th>Total Weaned</th>
        <th>Survival Rate (%)</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($lactations): foreach ($lactations as $l): ?>
      <tr>
        <td><?= $l['lactation_id'] ?></td>
        <td><?= htmlspecialchars($l['ear_tag_no']) ?></td>
        <td><?= htmlspecialchars($l['farrowing_date']) ?></td>
        <td><?= htmlspecialchars($l['total_born']) ?></td>
        <td><?= htmlspecialchars($l['total_weaned']) ?></td>
        <td><?= htmlspecialchars($l['survival_rate']) ?></td>
        <td>
          <a href="view_lactation.php?id=<?= $l['lactation_id'] ?>" class="btn btn-info btn-sm">View</a>
          <a href="edit_lactation.php?id=<?= $l['lactation_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="delete_lactation.php?id=<?= $l['lactation_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="7">No lactation records found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
