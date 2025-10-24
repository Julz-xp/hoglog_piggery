<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("
  SELECT h.*, s.ear_tag_no, s.breed_line
  FROM sow_health_record h
  LEFT JOIN sows s ON h.sow_id = s.sow_id
  WHERE h.health_id = ?
");
$stmt->execute([$id]);
$h = $stmt->fetch();
if (!$h) die("Record not found!");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Health Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">👁 Health & Treatment Record Details</h2>
  <table class="table table-bordered">
    <tr><th>Sow (Ear Tag)</th><td><?= htmlspecialchars($h['ear_tag_no']) ?></td></tr>
    <tr><th>Breed Line</th><td><?= htmlspecialchars($h['breed_line']) ?></td></tr>
    <tr><th>Record Type</th><td><?= htmlspecialchars($h['record_type']) ?></td></tr>
    <tr><th>Date</th><td><?= htmlspecialchars($h['record_date']) ?></td></tr>
    <tr><th>Stage</th><td><?= htmlspecialchars($h['stage']) ?></td></tr>
    <tr><th>Description</th><td><?= nl2br(htmlspecialchars($h['description'])) ?></td></tr>
    <tr><th>Findings</th><td><?= nl2br(htmlspecialchars($h['findings'])) ?></td></tr>
    <tr><th>Treatment</th><td><?= nl2br(htmlspecialchars($h['treatment'])) ?></td></tr>
    <tr><th>Product Description</th><td><?= htmlspecialchars($h['product_description']) ?></td></tr>
    <tr><th>Farm Vet / Handler</th><td><?= htmlspecialchars($h['farm_vet']) ?></td></tr>
    <tr><th>Cost (₱)</th><td><?= htmlspecialchars($h['cost']) ?></td></tr>
    <tr><th>Remarks</th><td><?= nl2br(htmlspecialchars($h['remarks'])) ?></td></tr>
  </table>
  <a href="list_health.php" class="btn btn-secondary">⬅ Back</a>
</div>
</body>
</html>
