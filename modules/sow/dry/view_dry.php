<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("
  SELECT d.*, s.ear_tag_no, s.breed_line
  FROM sow_dry_stage d
  LEFT JOIN sows s ON d.sow_id = s.sow_id
  WHERE d.dry_id = ?
");
$stmt->execute([$id]);
$dry = $stmt->fetch();
if (!$dry) die("Record not found!");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Dry Sow Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">👁 View Dry Sow Record</h2>
  <table class="table table-bordered">
    <tr><th>Sow (Ear Tag)</th><td><?= htmlspecialchars($dry['ear_tag_no']) ?></td></tr>
    <tr><th>Breed Line</th><td><?= htmlspecialchars($dry['breed_line']) ?></td></tr>
    <tr><th>Weaning Date</th><td><?= htmlspecialchars($dry['weaning_date']) ?></td></tr>
    <tr><th>Heat Detection Date</th><td><?= htmlspecialchars($dry['heat_detection_date']) ?></td></tr>
    <tr><th>Weaning → Estrus Interval (days)</th><td><?= htmlspecialchars($dry['weaning_estrus_interval']) ?></td></tr>
  </table>
  <a href="list_dry.php" class="btn btn-secondary">⬅ Back</a>
</div>
</body>
</html>
