<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT e.*, b.batch_no
    FROM batch_expenses e
    JOIN batch_records b ON e.batch_id = b.batch_id
    WHERE e.expense_id=?
");
$stmt->execute([$id]);
$e = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$e) die("Expense record not found");
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>View Batch Expense</title></head>
<body>
<h2>Batch Expense Summary (Batch <?= htmlspecialchars($e['batch_no']) ?>)</h2>

<p><b>Total Feed Cost:</b> ₱<?= number_format($e['total_feed_cost'],2) ?></p>
<p><b>Total Health Cost:</b> ₱<?= number_format($e['total_health_cost'],2) ?></p>
<p><b>Total Expenses:</b> ₱<?= number_format($e['total_expenses'],2) ?></p>
<p><b>Average Expense per Pig:</b> ₱<?= number_format($e['avg_expense_per_pig'],2) ?></p>

<br>
<a href="edit_expense.php?id=<?= $e['expense_id'] ?>">✏️ Edit</a> |
<a href="list_expense.php">← Back</a>
</body>
</html>
