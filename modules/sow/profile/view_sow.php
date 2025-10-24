<?php
require_once __DIR__ . '/../../../config/db.php';

$id = $_GET['id'] ?? 0;

// 🧾 Fetch sow record
$stmt = $pdo->prepare("SELECT * FROM sows WHERE sow_id = ?");
$stmt->execute([$id]);
$sow = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Sow Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
  background-color: #f8f9fa;
}
.card {
  border: none;
  border-radius: 1rem;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.table th {
  width: 30%;
}
</style>
</head>
<body class="p-4">
<div class="container" style="max-width: 700px;">
  <div class="card p-4">
    <div class="text-center mb-4">
      <i class="bi bi-piggy-bank-fill fs-1 text-success"></i>
      <h2 class="mt-2">Sow Profile Details</h2>
      <p class="text-muted mb-0">Comprehensive record overview</p>
    </div>

    <?php if ($sow): ?>
      <table class="table table-bordered">
        <?php foreach ($sow as $key => $value): ?>
        <tr>
          <th><?= htmlspecialchars(ucwords(str_replace('_', ' ', $key))) ?></th>
          <td><?= htmlspecialchars($value) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>

      <div class="d-flex justify-content-between mt-4">
        <a href="list_sow.php" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Back
        </a>

        <?php if ($sow['status'] === 'Gilt'): ?>
          <a href="../gilt/view_roadmap.php?sow_id=<?= $sow['sow_id'] ?>" 
             class="btn btn-success">
            <i class="bi bi-diagram-3"></i> View Roadmap
          </a>
        <?php endif; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-danger mt-3">
        <i class="bi bi-exclamation-triangle"></i> No sow record found.
      </div>
    <?php endif; ?>

  </div>
</div>
</body>
</html>
