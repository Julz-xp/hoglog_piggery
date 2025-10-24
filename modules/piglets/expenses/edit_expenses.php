<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request.");
$id = $_GET['id'];

// Fetch record
$stmt = $pdo->prepare("SELECT * FROM piglet_expenses WHERE expense_id=?");
$stmt->execute([$id]);
$exp = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$exp) die("Record not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $piglet_id = $_POST['piglet_id'];
    $total_feed_cost = $_POST['total_feed_cost'];
    $total_health_cost = $_POST['total_health_cost'];

    $stmt = $pdo->prepare("UPDATE piglet_expenses 
        SET piglet_id=?, total_feed_cost=?, total_health_cost=? 
        WHERE expense_id=?");
    $stmt->execute([$piglet_id, $total_feed_cost, $total_health_cost, $id]);

    header("Location: list_expenses.php?updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Piglet Expenses</title></head>
<body>
<h2>Edit Piglet Expenses</h2>
<form method="POST">
    Piglet ID: <input type="number" name="piglet_id" value="<?= $exp['piglet_id'] ?>"><br><br>
    Total Feed Cost (₱): <input type="number" step="0.01" name="total_feed_cost" value="<?= $exp['total_feed_cost'] ?>"><br><br>
    Total Health Cost (₱): <input type="number" step="0.01" name="total_health_cost" value="<?= $exp['total_health_cost'] ?>"><br><br>
    <button type="submit">Update</button>
</form>
</body>
</html>
