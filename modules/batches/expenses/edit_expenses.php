<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

// Fetch record
$stmt = $pdo->prepare("SELECT * FROM batch_expenses WHERE expense_id=?");
$stmt->execute([$id]);
$e = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$e) die("Record not found");

// Fetch batches
$batches = $pdo->query("SELECT batch_id, batch_no FROM batch_records ORDER BY batch_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch_id         = $_POST['batch_id'];
    $total_feed_cost  = $_POST['total_feed_cost'];
    $total_health_cost= $_POST['total_health_cost'];
    $avg_expense_per_pig = $_POST['avg_expense_per_pig'];

    $stmt = $pdo->prepare("
        UPDATE batch_expenses SET
        batch_id=?, total_feed_cost=?, total_health_cost=?, avg_expense_per_pig=?
        WHERE expense_id=?
    ");
    $stmt->execute([$batch_id, $total_feed_cost, $total_health_cost, $avg_expense_per_pig, $id]);

    header("Location: list_expense.php?updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Edit Expense</title></head>
<body>
<h2>Edit Batch Expense</h2>
<form method="POST">
    Batch:
    <select name="batch_id" required>
        <?php foreach ($batches as $b): ?>
        <option value="<?= $b['batch_id'] ?>" <?= $b['batch_id']==$e['batch_id']?'selected':'' ?>>
            <?= htmlspecialchars($b['batch_no']) ?>
        </option>
        <?php endforeach; ?>
    </select><br><br>

    Total Feed Cost (₱): <input type="number" step="0.01" name="total_feed_cost" value="<?= $e['total_feed_cost'] ?>"><br><br>
    Total Health Cost (₱): <input type="number" step="0.01" name="total_health_cost" value="<?= $e['total_health_cost'] ?>"><br><br>
    Avg Expense per Pig (₱): <input type="number" step="0.01" name="avg_expense_per_pig" value="<?= $e['avg_expense_per_pig'] ?>"><br><br>

    <button type="submit">Update Expense</button>
</form>
<br>
<a href="list_expense.php">← Back</a>
</body>
</html>
