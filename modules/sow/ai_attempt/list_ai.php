<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch all A.I. attempts with sow info
$stmt = $pdo->query("
    SELECT a.*, s.ear_tag_no, s.breed_line 
    FROM sow_ai_attempts a
    LEFT JOIN sows s ON a.sow_id = s.sow_id
    ORDER BY a.ai_id DESC
");
$ai_attempts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>A.I. Attempt Records</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">💉 A.I. Attempt Records</h2>
  <a href="add_ai.php" class="btn btn-success mb-3">➕ Add A.I. Attempt</a>
  <table class="table table-bordered table-hover text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Sow (Ear Tag)</th>
        <th>A.I. Date</th>
        <th>Breeding Type</th>
        <th>Pregnancy Result</th>
        <th>Cost (₱)</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($ai_attempts): foreach ($ai_attempts as $a): ?>
      <tr>
        <td><?= $a['ai_id'] ?></td>
        <td><?= htmlspecialchars($a['ear_tag_no']) ?></td>
        <td><?= htmlspecialchars($a['ai_date']) ?></td>
        <td><?= htmlspecialchars($a['breeding_type']) ?></td>
        <td><?= htmlspecialchars($a['confirmation']) ?></td>
        <td><?= number_format($a['cost'], 2) ?></td>
        <td>
          <a href="view_ai.php?id=<?= $a['ai_id'] ?>" class="btn btn-info btn-sm">View</a>
          <a href="edit_ai.php?id=<?= $a['ai_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="delete_ai.php?id=<?= $a['ai_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="7">No A.I. attempt records found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
