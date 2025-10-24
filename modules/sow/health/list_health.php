<?php
require_once __DIR__ . '/../../../config/db.php';
$stmt = $pdo->query("
  SELECT h.*, s.ear_tag_no, s.breed_line
  FROM sow_health_record h
  LEFT JOIN sows s ON h.sow_id = s.sow_id
  ORDER BY h.record_date DESC
");
$healths = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Health & Treatment Records</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">💉 Health, Vaccination & Treatment Records</h2>
  <a href="add_health.php" class="btn btn-success mb-3">➕ Add Health Record</a>

  <table class="table table-bordered text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Sow (Ear Tag)</th>
        <th>Date</th>
        <th>Type</th>
        <th>Stage</th>
        <th>Description</th>
        <th>Vet</th>
        <th>Cost (₱)</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($healths): foreach ($healths as $h): ?>
      <tr>
        <td><?= $h['health_id'] ?></td>
        <td><?= htmlspecialchars($h['ear_tag_no']) ?></td>
        <td><?= htmlspecialchars($h['record_date']) ?></td>
        <td><?= htmlspecialchars($h['record_type']) ?></td>
        <td><?= htmlspecialchars($h['stage']) ?></td>
        <td><?= htmlspecialchars($h['description']) ?></td>
        <td><?= htmlspecialchars($h['farm_vet']) ?></td>
        <td><?= htmlspecialchars($h['cost']) ?></td>
        <td>
          <a href="view_health.php?id=<?= $h['health_id'] ?>" class="btn btn-info btn-sm">View</a>
          <a href="edit_health.php?id=<?= $h['health_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="delete_health.php?id=<?= $h['health_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="9">No health records found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
