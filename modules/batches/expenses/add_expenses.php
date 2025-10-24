<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch batches
$batches = $pdo->query("SELECT batch_id, batch_no FROM batch_records ORDER BY batch_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch_id         = $_POST['batch_id'];
    $total_feed_cost  = $_POST['total_feed_cost'];
    $total_health_cost= $_POST['total_health_cost'];
    $avg_expense_per_pig = $_POST['avg_expense_per_pig'];

    $stmt = $pdo->prepare("
        INSERT INTO batch_expenses
        (batch_id, total_feed_cost, total_health_cost, avg_expense_per_pig)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$batch_id, $total_feed_cost, $total_health_cost, $avg_expense_per_pig]);

    header("Location: list_expense.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Add Batch Expense</title></head>
<body>
<h2>Add Batch Expense</h2>
<form method="POST">
    Batch:
    <select name="batch_id" required>
        <option value="">-- Select Batch --</option>
        <?php foreach ($batches as $b): ?>
        <option value="<?= $b['batch_id'] ?>"><?= htmlspecialchars($b['batch_no']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    Total Feed Cost (₱): <input type="number" step="0.01" name="total_feed_cost" required><br><br>
    Total Health Cost (₱): <input type="number" step="0.01" name="total_health_cost" required><br><br>
    Avg Expense per Pig (₱): <input type="number" step="0.01" name="avg_expense_per_pig"><br><br>

    <button type="submit">Save Expense</button>
</form>
<br>
<a href="list_expense.php">← Back to list</a>
</body>
</html>
