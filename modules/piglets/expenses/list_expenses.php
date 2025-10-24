<?php
require_once __DIR__ . '/../../../config/db.php';
$stmt = $pdo->query("SELECT e.*, p.farrowing_date 
                     FROM piglet_expenses e
                     JOIN piglet_records p ON e.piglet_id = p.piglet_id
                     ORDER BY e.expense_id DESC");
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><title>Piglet Expenses</title></head>
<body>
<h2>Piglet Expenses Summary</h2>
<a href="add_expenses.php">➕ Generate Expenses</a>
<br><br>

<?php if (isset($_GET['success'])) echo "<p style='color:green;'>✅ Record added successfully!</p>"; ?>
<?php if (isset($_GET['updated'])) echo "<p style='color:blue;'>✏️ Record updated successfully!</p>"; ?>
<?php if (isset($_GET['deleted'])) echo "<p style='color:red;'>🗑️ Record deleted successfully!</p>"; ?>

<table border="1" cellpadding="8">
<tr>
    <th>ID</th><th>Piglet ID</th><th>Total Feed Cost</th>
    <th>Total Health Cost</th><th>Total Expenses</th><th>Actions</th>
</tr>

<?php if ($expenses): foreach ($expenses as $e): ?>
<tr>
    <td><?= $e['expense_id'] ?></td>
    <td><?= $e['piglet_id'] ?></td>
    <td>₱<?= number_format($e['total_feed_cost'], 2) ?></td>
    <td>₱<?= number_format($e['total_health_cost'], 2) ?></td>
    <td><b>₱<?= number_format($e['total_feed_cost'] + $e['total_health_cost'], 2) ?></b></td>
    <td>
        <a href="view_expenses.php?id=<?= $e['expense_id'] ?>">View</a> |
        <a href="edit_expenses.php?id=<?= $e['expense_id'] ?>">Edit</a> |
        <a href="delete_expenses.php?id=<?= $e['expense_id'] ?>" onclick="return confirm('Delete this record?')">Delete</a>
    </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="6">No expense records found.</td></tr>
<?php endif; ?>
</table>
</body>
</html>

