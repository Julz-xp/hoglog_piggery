<?php
require_once __DIR__ . '/../../../config/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("❌ Invalid request: Missing Gilt ID");
}

// Fetch Gilt record with linked Sow info
$stmt = $pdo->prepare("
    SELECT g.*, s.ear_tag_no, s.breed_line, s.source 
    FROM sow_gilt_stage g
    LEFT JOIN sows s ON g.sow_id = s.sow_id
    WHERE g.gilt_id = ?
");
$stmt->execute([$id]);
$gilt = $stmt->fetch();

if (!$gilt) {
    die("❌ Gilt record not found!");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Gilt Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">👁 Gilt Stage Record Details</h2>
  
  <table class="table table-bordered">
    <tr><th>Gilt ID</th><td><?= htmlspecialchars($gilt['gilt_id']) ?></td></tr>
    <tr><th>Sow (Ear Tag)</th><td><?= htmlspecialchars($gilt['ear_tag_no']) ?> — <?= htmlspecialchars($gilt['breed_line']) ?></td></tr>
    <tr><th>Source</th><td><?= htmlspecialchars($gilt['source']) ?></td></tr>
    <tr><th>Heat Detection Date</th><td><?= htmlspecialchars($gilt['heat_detection_date']) ?></td></tr>
    <tr><th>Breeding / AI Attempt Date</th><td><?= htmlspecialchars($gilt['breeding_date']) ?></td></tr>
    <tr><th>Service Type</th><td><?= htmlspecialchars($gilt['service_type']) ?></td></tr>
    <tr><th>Boar / Semen Source (ID, Batch No.)</th><td><?= htmlspecialchars($gilt['semen_source']) ?></td></tr>
    <tr><th>Technician / Handler</th><td><?= htmlspecialchars($gilt['technician_handler']) ?></td></tr>
    <tr><th>Heat Observation Notes</th><td><?= nl2br(htmlspecialchars($gilt['heat_observation_notes'])) ?></td></tr>
    <tr><th>Pregnancy Check Date</th><td><?= htmlspecialchars($gilt['pregnancy_check_date']) ?></td></tr>
    <tr><th>Pregnancy Result</th><td><?= htmlspecialchars($gilt['pregnancy_result']) ?></td></tr>
    <tr><th>Rebreeding Date</th><td><?= htmlspecialchars($gilt['rebreeding_date']) ?></td></tr>
    <tr><th>Mother’s Parity No.</th><td><?= htmlspecialchars($gilt['mother_parify_no']) ?></td></tr>
    <tr><th>Remarks</th><td><?= nl2br(htmlspecialchars($gilt['remarks'])) ?></td></tr>
  </table>

  <a href="list_gilt.php" class="btn btn-secondary">⬅ Back</a>
  <a href="edit_gilt.php?id=<?= $gilt['gilt_id'] ?>" class="btn btn-warning">✏️ Edit</a>
  <a href="delete_gilt.php?id=<?= $gilt['gilt_id'] ?>" class="btn btn-danger"
     onclick="return confirm('Are you sure you want to delete this record?')">🗑 Delete</a>
</div>
</body>
</html>
