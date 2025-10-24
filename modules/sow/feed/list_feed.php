<?php
require_once __DIR__ . '/../../../config/db.php';
$stmt = $pdo->query("
  SELECT f.*, s.ear_tag_no, s.breed_line
  FROM sow_feed_consumption f
  LEFT JOIN sows s ON f.sow_id = s.sow_id
  ORDER BY f.start_date DESC
");
$feeds = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Feed Records</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">🥣 Sow Feed Consumption</h2>
  <a href="add_feed.php" class="btn btn-success mb-3">➕ Add Feed Record</a>

  <table class="table table-bordered text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Sow</th>
        <th>Stage</th>
        <th>Feed Type</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Daily Intake (kg)</th>
        <th>Total Days</th>
        <th>Total Feed (kg)</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($feeds): foreach ($feeds as $f): ?>
      <tr>
        <td><?= $f['feed_id'] ?></td>
        <td><?= htmlspecialchars($f['ear_tag_no']) ?></td>
        <td><?= htmlspecialchars($f['stage']) ?></td>
        <td><?= htmlspecialchars($f['feed_type']) ?></td>
        <td><?= htmlspecialchars($f['start_date']) ?></td>
        <td><?= htmlspecialchars($f['end_date']) ?></td>
        <td><?= htmlspecialchars($f['daily_intake']) ?></td>
        <td><?= htmlspecialchars($f['total_days']) ?></td>
        <td><?= htmlspecialchars($f['total_feed']) ?></td>
        <td>
          <a href="view_feed.php?id=<?= $f['feed_id'] ?>" class="btn btn-info btn-sm">View</a>
          <a href="edit_feed.php?id=<?= $f['feed_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="delete_feed.php?id=<?= $f['feed_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="10">No feed records found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
