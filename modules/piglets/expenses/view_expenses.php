<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request.");

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT e.*, p.farrowing_date 
                       FROM piglet_expenses e 
                       JOIN piglet_records p ON e.piglet_id = p.piglet_id 
                       WHERE e.expense_id = ?");
$stmt->execute([$id]);
$e = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$e) die("Record not found.");
?>

<!DOCTYPE html>
<html>
<head><title>View Piglet Expense</title></head>
<body>
<h2>View Piglet Expense Record</h2>
<p><b>Piglet ID:</b> <?= $e['piglet_id'] ?></p>
<p><b>Total Feed Cost:</b> ₱<?= number_format($e['total_feed_cost'], 2) ?></p>
<p><b>Total Health Cost:</b> ₱<?= number_format($e['total_health_cost'], 2) ?></p>
<p><b>Total Expenses:</b> ₱<?= number_format($e['total_feed_cost'] + $e['total_health_cost'], 2) ?></p>

<br>
<a href="edit_expenses.php?id=<?= $e['expense_id'] ?>">Edit</a> |
<a href="list_expenses.php">Back to List</a>
</body>
</html>
