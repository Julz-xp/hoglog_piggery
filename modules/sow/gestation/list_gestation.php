<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch all gestation records with sow info
$stmt = $pdo->query("
    SELECT g.*, s.ear_tag_no, s.breed_line, a.ai_date
    FROM sow_gestation g
    LEFT JOIN sows s ON g.sow_id = s.sow_id
    LEFT JOIN sow_ai_attempts a ON g.ai_id = a.ai_id
    ORDER BY g.gestation_id DESC
");
$gestations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Gestation Records</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">🤰 Gestation Stage Records</h2>
  <a href="add_gestation.php" class="btn btn-success mb-3">➕ Add Gestation Record</a>
  <table class="table table-bordered table-hover text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Sow (Ear Tag)</th>
        <th>Breeding Date</th>
        <th>Expected Farrowing</th>
        <th>Boar Source</th>
        <th>BCS</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($gestations): foreach ($gestations as $g): ?>
      <tr>
        <td><?= $g['gestation_id'] ?></td>
        <td><?= htmlspecialchars($g['ear_tag_no']) ?></td>
        <td><?= htmlspecialchars($g['breeding_date']) ?></td>
        <td><?= htmlspecialchars($g['expected_farrowing_date']) ?></td>
        <td><?= htmlspecialchars($g['boar_source']) ?></td>
        <td><?= htmlspecialchars($g['body_condition_score']) ?></td>
        <td>
          <a href="view_gestation.php?id=<?= $g['gestation_id'] ?>" class="btn btn-info btn-sm">View</a>
          <a href="edit_gestation.php?id=<?= $g['gestation_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="delete_gestation.php?id=<?= $g['gestation_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="7">No gestation records found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
