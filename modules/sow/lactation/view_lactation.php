<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT l.*, s.ear_tag_no, s.breed_line
    FROM sow_lactation l
    LEFT JOIN sows s ON l.sow_id = s.sow_id
    WHERE l.lactation_id = ?
");
$stmt->execute([$id]);
$l = $stmt->fetch();
if (!$l) die("Record not found!");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Lactation Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">👁 Lactation Record Details</h2>
  <table class="table table-bordered">
    <tr><th>Sow (Ear Tag)</th><td><?= htmlspecialchars($l['ear_tag_no']) ?></td></tr>
    <tr><th>Farrowing Date</th><td><?= htmlspecialchars($l['farrowing_date']) ?></td></tr>
    <tr><th>Total Born</th><td><?= htmlspecialchars($l['total_born']) ?></td></tr>
    <tr><th>Total Weaned</th><td><?= htmlspecialchars($l['total_weaned']) ?></td></tr>
    <tr><th>Average Birth Weight</th><td><?= htmlspecialchars($l['avg_birth_weight']) ?></td></tr>
    <tr><th>Survival Rate (%)</th><td><?= htmlspecialchars($l['survival_rate']) ?></td></tr>
    <tr><th>Remarks</th><td><?= nl2br(htmlspecialchars($l['remarks'])) ?></td></tr>
  </table>
  <a href="list_lactation.php" class="btn btn-secondary">⬅ Back</a>
</div>
</body>
</html>
