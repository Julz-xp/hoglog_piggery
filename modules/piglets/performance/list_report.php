<?php
require_once __DIR__ . '/../../../config/db.php';
$stmt = $pdo->query("SELECT r.*, p.farrowing_date 
                     FROM piglet_performance_report r
                     JOIN piglet_records p ON r.piglet_id = p.piglet_id
                     ORDER BY r.report_id DESC");
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><title>Piglet Performance Reports</title></head>
<body>
<h2>🐷 Piglet Performance Reports</h2>
<a href="generate_report.php">➕ Generate New Report</a>
<br><br>

<?php if (isset($_GET['success'])) echo "<p style='color:green;'>✅ Report generated successfully!</p>"; ?>

<table border="1" cellpadding="8">
<tr>
  <th>ID</th><th>Piglet ID</th><th>Litter Size</th><th>Total Weaned</th>
  <th>Survival (%)</th><th>Avg Birth (kg)</th><th>Avg Weaning (kg)</th>
  <th>Feed/Piglet (₱)</th><th>Health/Piglet (₱)</th><th>Net Expense/Piglet (₱)</th><th>Actions</th>
</tr>

<?php if ($reports): foreach ($reports as $r): ?>
<tr>
  <td><?= $r['report_id'] ?></td>
  <td><?= $r['piglet_id'] ?></td>
  <td><?= $r['litter_size'] ?></td>
  <td><?= $r['total_weaned'] ?></td>
  <td><?= $r['survival_rate'] ?>%</td>
  <td><?= $r['avg_birth_weight'] ?></td>
  <td><?= $r['avg_weaning_weight'] ?></td>
  <td>₱<?= number_format($r['feed_cost_per_piglet'], 2) ?></td>
  <td>₱<?= number_format($r['health_cost_per_piglet'], 2) ?></td>
  <td><b>₱<?= number_format($r['net_expense_per_piglet'], 2) ?></b></td>
  <td>
    <a href="view_report.php?id=<?= $r['report_id'] ?>">View</a> |
    <a href="delete_report.php?id=<?= $r['report_id'] ?>" onclick="return confirm('Delete this report?')">Delete</a>
  </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="11">No reports generated yet.</td></tr>
<?php endif; ?>
</table>
</body>
</html>
