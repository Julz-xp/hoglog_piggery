<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request.");
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT r.*, p.farrowing_date, p.total_born, p.total_weaned 
                       FROM piglet_performance_report r
                       JOIN piglet_records p ON r.piglet_id = p.piglet_id
                       WHERE r.report_id = ?");
$stmt->execute([$id]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$r) die("Report not found.");
?>

<!DOCTYPE html>
<html>
<head><title>View Piglet Performance Report</title></head>
<body>
<h2>🐷 Piglet Performance Report</h2>
<p><b>Piglet ID:</b> <?= $r['piglet_id'] ?></p>
<p><b>Litter Size:</b> <?= $r['litter_size'] ?></p>
<p><b>Total Weaned:</b> <?= $r['total_weaned'] ?></p>
<p><b>Survival Rate:</b> <?= $r['survival_rate'] ?>%</p>
<p><b>Average Birth Weight:</b> <?= $r['avg_birth_weight'] ?> kg</p>
<p><b>Average Weaning Weight:</b> <?= $r['avg_weaning_weight'] ?> kg</p>
<p><b>Feed Cost per Piglet:</b> ₱<?= number_format($r['feed_cost_per_piglet'], 2) ?></p>
<p><b>Health Cost per Piglet:</b> ₱<?= number_format($r['health_cost_per_piglet'], 2) ?></p>
<p><b>Net Expense per Piglet:</b> ₱<?= number_format($r['net_expense_per_piglet'], 2) ?></p>
<br>
<a href="list_report.php">⬅ Back to Reports</a>
</body>
</html>
