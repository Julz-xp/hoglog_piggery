<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("
  SELECT e.*, s.ear_tag_no, s.breed_line
  FROM sow_expenses e
  LEFT JOIN sows s ON e.sow_id = s.sow_id
  WHERE e.expense_id = ?
");
$stmt->execute([$id]);
$e = $stmt->fetch();
if (!$e) die("Record not found!");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Expense Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">👁 View Expense Record</h2>
  <table class="table table-bordered">
    <tr><th>Sow (Ear Tag)</th><td><?= htmlspecialchars($e['ear_tag_no']) ?></td></tr>
    <tr><th>Breed Line</th><td><?= htmlspecialchars($e['breed_line']) ?></td></tr>
    <tr><th>Stage</th><td><?= htmlspecialchars($e['stage']) ?></td></tr>
    <tr><th>Feed Cost (₱)</th><td><?= number_format($e['feed_cost'], 2) ?></td></tr>
    <tr><th>Health Cost (₱)</th><td><?= number_format($e['health_cost'], 2) ?></td></tr>
    <tr><th>AI Cost (₱)</th><td><?= number_format($e['ai_cost'], 2) ?></td></tr>
    <tr class="table-secondary"><th>Total Cost (₱)</th><td><strong><?= number_format($e['total_cost'], 2) ?></strong></td></tr>
  </table>
  <a href="list_expense.php" class="btn btn-secondary">⬅ Back</a>
</div>
</body>
</html>
