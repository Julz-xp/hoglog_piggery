<?php
require_once __DIR__ . '/../../../config/db.php';

$query = "SELECT s.*, f.ear_tag_no 
          FROM fattener_sales_record s
          JOIN fattener_records f ON s.fattener_id = f.fattener_id
          ORDER BY s.sale_id DESC";
$stmt = $pdo->query($query);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Sales Records</title></head>
<body>
<h2>🐖 Fattener Sales Records</h2>
<a href="add_sales.php">➕ Add Sales Record</a>
<br><br>

<?php if (isset($_GET['success'])) echo "<p style='color:green;'>✅ Record added successfully!</p>"; ?>
<?php if (isset($_GET['updated'])) echo "<p style='color:blue;'>✏️ Record updated successfully!</p>"; ?>
<?php if (isset($_GET['deleted'])) echo "<p style='color:red;'>🗑️ Record deleted successfully!</p>"; ?>

<table border="1" cellpadding="8" cellspacing="0">
<tr>
    <th>ID</th>
    <th>Ear Tag</th>
    <th>Sale Date</th>
    <th>Weight (kg)</th>
    <th>Price/kg (₱)</th>
    <th>Total Revenue (₱)</th>
    <th>Net Profit (₱)</th>
    <th>Actions</th>
</tr>

<?php if ($records): foreach ($records as $r): ?>
<tr>
    <td><?= $r['sale_id'] ?></td>
    <td><?= htmlspecialchars($r['ear_tag_no']) ?></td>
    <td><?= htmlspecialchars($r['sale_date']) ?></td>
    <td><?= htmlspecialchars($r['market_weight']) ?></td>
    <td><?= htmlspecialchars($r['price_per_kg']) ?></td>
    <td><?= htmlspecialchars($r['total_revenue']) ?></td>
    <td><?= htmlspecialchars($r['net_profit']) ?></td>
    <td>
        <a href="view_sales.php?id=<?= $r['sale_id'] ?>">View</a> |
        <a href="edit_sales.php?id=<?= $r['sale_id'] ?>">Edit</a> |
        <a href="delete_sales.php?id=<?= $r['sale_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
    </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="8" align="center">No sales records found.</td></tr>
<?php endif; ?>
</table>

</body>
</html>
