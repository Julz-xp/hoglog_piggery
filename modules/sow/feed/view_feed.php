<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("
  SELECT f.*, s.ear_tag_no, s.breed_line
  FROM sow_feed_consumption f
  LEFT JOIN sows s ON f.sow_id = s.sow_id
  WHERE f.feed_id = ?
");
$stmt->execute([$id]);
$f = $stmt->fetch();
if (!$f) die("Record not found!");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Feed Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">👁 View Feed Record</h2>
  <table class="table table-bordered">
    <tr><th>Sow (Ear Tag)</th><td><?= htmlspecialchars($f['ear_tag_no']) ?></td></tr>
    <tr><th>Breed</th><td><?= htmlspecialchars($f['breed_line']) ?></td></tr>
    <tr><th>Stage</th><td><?= htmlspecialchars($f['stage']) ?></td></tr>
    <tr><th>Feed Type</th><td><?= htmlspecialchars($f['feed_type']) ?></td></tr>
    <tr><th>Start Date</th><td><?= htmlspecialchars($f['start_date']) ?></td></tr>
    <tr><th>End Date</th><td><?= htmlspecialchars($f['end_date']) ?></td></tr>
    <tr><th>Daily Intake (kg)</th><td><?= htmlspecialchars($f['daily_intake']) ?></td></tr>
    <tr><th>Total Days</th><td><?= htmlspecialchars($f['total_days']) ?></td></tr>
    <tr><th>Total Feed (kg)</th><td><?= htmlspecialchars($f['total_feed']) ?></td></tr>
  </table>
  <a href="list_feed.php" class="btn btn-secondary">⬅ Back</a>
</div>
</body>
</html>
