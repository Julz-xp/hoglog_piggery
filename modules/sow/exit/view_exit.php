<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("
  SELECT e.*, s.ear_tag_no, s.breed_line
  FROM sow_exit_record e
  LEFT JOIN sows s ON e.sow_id = s.sow_id
  WHERE e.exit_id = ?
");
$stmt->execute([$id]);
$exit = $stmt->fetch();
if (!$exit) die("Record not found!");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Exit Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">👁 Exit Record Details</h2>
  <table class="table table-bordered">
    <tr><th>Sow (Ear Tag)</th><td><?= htmlspecialchars($exit['ear_tag_no']) ?></td></tr>
    <tr><th>Breed Line</th><td><?= htmlspecialchars($exit['breed_line']) ?></td></tr>
    <tr><th>Culling Date</th><td><?= htmlspecialchars($exit['culling_date']) ?></td></tr>
    <tr><th>Reason for Culling</th><td><?= htmlspecialchars($exit['reason_for_culling']) ?></td></tr>
    <tr><th>Last Parity Summary</th><td><?= nl2br(htmlspecialchars($exit['last_parity_summary'])) ?></td></tr>
    <tr><th>Final Weight (kg)</th><td><?= htmlspecialchars($exit['final_weight']) ?></td></tr>
    <tr><th>Sale Price (₱)</th><td><?= htmlspecialchars($exit['sale_price']) ?></td></tr>
    <tr><th>Final Health Condition</th><td><?= htmlspecialchars($exit['health_condition']) ?></td></tr>
    <tr><th>Exit Type</th><td><?= htmlspecialchars($exit['exit_type']) ?></td></tr>
    <tr><th>Disposal Notes</th><td><?= nl2br(htmlspecialchars($exit['disposal_notes'])) ?></td></tr>
  </table>
  <a href="list_exit.php" class="btn btn-secondary">⬅ Back</a>
</div>
</body>
</html>
