<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("
    SELECT a.*, s.ear_tag_no, s.breed_line
    FROM sow_ai_attempts a
    LEFT JOIN sows s ON a.sow_id = s.sow_id
    WHERE a.ai_id = ?
");
$stmt->execute([$id]);
$ai = $stmt->fetch();
if (!$ai) die("Record not found!");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View A.I. Attempt</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">👁 A.I. Attempt Details</h2>
  <table class="table table-bordered">
    <tr><th>Sow (Ear Tag)</th><td><?= htmlspecialchars($ai['ear_tag_no']) ?></td></tr>
    <tr><th>Breed / Line</th><td><?= htmlspecialchars($ai['breed_line']) ?></td></tr>
    <tr><th>Heat Detection Date</th><td><?= htmlspecialchars($ai['heat_detection_date']) ?></td></tr>
    <tr><th>A.I. Date</th><td><?= htmlspecialchars($ai['ai_date']) ?></td></tr>
    <tr><th>Breeding Type</th><td><?= htmlspecialchars($ai['breeding_type']) ?></td></tr>
    <tr><th>Boar Source</th><td><?= htmlspecialchars($ai['boar_source']) ?></td></tr>
    <tr><th>Farm Vet</th><td><?= htmlspecialchars($ai['farm_vet']) ?></td></tr>
    <tr><th>Pregnancy Check Date</th><td><?= htmlspecialchars($ai['pregnancy_check_date']) ?></td></tr>
    <tr><th>Confirmation</th><td><?= htmlspecialchars($ai['confirmation']) ?></td></tr>
    <tr><th>Cost (₱)</th><td><?= number_format($ai['cost'], 2) ?></td></tr>
    <tr><th>Estrus Notes</th><td><?= nl2br(htmlspecialchars($ai['estrus_notes'])) ?></td></tr>
  </table>
  <a href="list_ai.php" class="btn btn-secondary">⬅ Back</a>
</div>
</body>
</html>
