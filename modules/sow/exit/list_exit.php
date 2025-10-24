<?php
require_once __DIR__ . '/../../../config/db.php';

$stmt = $pdo->query("
  SELECT e.*, s.ear_tag_no, s.breed_line
  FROM sow_exit_record e
  LEFT JOIN sows s ON e.sow_id = s.sow_id
  ORDER BY e.exit_id DESC
");
$exits = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Exit Records</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">🐖 Exit Records (Culled / Sold / Died)</h2>
  <a href="add_exit.php" class="btn btn-success mb-3">➕ Add Exit Record</a>

  <table class="table table-bordered text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Sow (Ear Tag)</th>
        <th>Culling Date</th>
        <th>Exit Type</th>
        <th>Reason</th>
        <th>Final Weight</th>
        <th>Sale Price</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($exits): foreach ($exits as $e): ?>
      <tr>
        <td><?= $e['exit_id'] ?></td>
        <td><?= htmlspecialchars($e['ear_tag_no']) ?></td>
        <td><?= htmlspecialchars($e['culling_date']) ?></td>
        <td><?= htmlspecialchars($e['exit_type']) ?></td>
        <td><?= htmlspecialchars($e['reason_for_culling']) ?></td>
        <td><?= htmlspecialchars($e['final_weight']) ?></td>
        <td><?= htmlspecialchars($e['sale_price']) ?></td>
        <td>
          <a href="view_exit.php?id=<?= $e['exit_id'] ?>" class="btn btn-info btn-sm">View</a>
          <a href="edit_exit.php?id=<?= $e['exit_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="delete_exit.php?id=<?= $e['exit_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="8">No exit records found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
