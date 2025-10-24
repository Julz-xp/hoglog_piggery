<?php
require_once __DIR__ . '/../../../config/db.php';
$stmt = $pdo->query("
    SELECT s.*, b.batch_no
    FROM batch_sales_record s
    JOIN batch_records b ON s.batch_id = b.batch_id
    ORDER BY s.market_date DESC
");
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Batch Sales Records</title></head>
<body>
<h2>Batch Sales Records</h2>
<a href="add_sale.php">➕ Add Sale Record</a><br><br>

<?php if (isset($_GET['success'])) echo "<p style='color:green;'>✅ Sale record added successfully.</p>"; ?>
<?php if (isset($_GET['updated'])) echo "<p style='color:blue;'>✏️ Record updated successfully.</p>"; ?>
<?php if (isset($_GET['deleted'])) echo "<p style='color:red;'>🗑️ Record deleted.</p>"; ?>

<table border="1" cellpadding="8">
<tr>
  <th>ID</th><th>Batch</th><th>Heads Sold</th><th>Weight (kg)</th><th>Price/kg</th><th>Total Revenue</th><th>Profit</th><th>Actions</th>
</tr>
<?php if ($sales): foreach ($sales as $s): 
$total_revenue = $s['total_market_weight'] * $s['price_per_kg'];
$net_profit = $total_revenue - $s['total_expenses'];
?>
<tr>
  <td><?= $s['sale_id'] ?></td>
  <td><?= htmlspecialchars($s['batch_no']) ?></td>
  <td><?= $s['num_heads_sold'] ?></td>
  <td><?= $s['total_market_weight'] ?></td>
  <td>₱<?= number_format($s['price_per_kg'],2) ?></td>
  <td>₱<?= number_format($total_revenue,2) ?></td>
  <td>₱<?= number_format($net_profit,2) ?></td>
  <td>
    <a href="view_sale.php?id=<?= $s['sale_id'] ?>">View</a> |
    <a href="edit_sale.php?id=<?= $s['sale_id'] ?>">Edit</a> |
    <a href="delete_sale.php?id=<?= $s['sale_id'] ?>" onclick="return confirm('Delete this sale record?')">Delete</a>
  </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="8">No sales records found.</td></tr>
<?php endif; ?>
</table>
</body>
</html>
