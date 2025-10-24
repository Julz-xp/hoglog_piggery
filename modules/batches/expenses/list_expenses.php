<?php
require_once __DIR__ . '/../../../config/db.php';
$stmt = $pdo->query("
    SELECT e.*, b.batch_no
    FROM batch_expenses e
    JOIN batch_records b ON e.batch_id = b.batch_id
    ORDER BY e.created_at DESC
");
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Batch Expenses</title></head>
<body>
<h2>Batch Expenses</h2>
<a href="add_expense.php">➕ Add Expense Record</a><br><br>

<?php if (isset($_GET['success'])) echo "<p style='color:green;'>✅ Record added successfully.</p>"; ?>
<?php if (isset($_GET['updated'])) echo "<p style='color:blue;'>✏️ Record updated successfully.</p>"; ?>
<?php if (isset($_GET['deleted'])) echo "<p style='color:red;'>🗑️ Record deleted.</p>"; ?>

<table border="1" cellpadding="8">
<tr>
  <th>ID</th><th>Batch</th><th>Feed Cost (₱)</th><th>Health Cost (₱)</th><th>Total (₱)</th><th>Avg/Pig (₱)</th><th>Actions</th>
</tr>
<?php if ($expenses): foreach ($expenses as $e): ?>
<tr>
  <td><?= $e['expense_id'] ?></td>
  <td><?= htmlspecialchars($e['batch_no']) ?></td>
  <td><?= number_format($e['total_feed_cost'],2) ?></td>
  <td><?= number_format($e['total_health_cost'],2) ?></td>
  <td><?= number_format($e['total_expenses'],2) ?></td>
  <td><?= number_format($e['avg_expense_per_pig'],2) ?></td>
  <td>
    <a href="view_expense.php?id=<?= $e['expense_id'] ?>">View</a> |
    <a href="edit_expense.php?id=<?= $e['expense_id'] ?>">Edit</a> |
    <a href="delete_expense.php?id=<?= $e['expense_id'] ?>" onclick="return confirm('Delete this record?')">Delete</a>
  </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="7">No expense records found.</td></tr>
<?php endif; ?>
</table>
</body>
</html>
