<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT s.*, b.batch_no
    FROM batch_sales_record s
    JOIN batch_records b ON s.batch_id = b.batch_id
    WHERE s.sale_id=?
");
$stmt->execute([$id]);
$s = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$s) die("Record not found");

$total_revenue = $s['total_market_weight'] * $s['price_per_kg'];
$net_profit = $total_revenue - $s['total_expenses'];
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>View Batch Sale</title></head>
<body>
<h2>Batch Sale Summary (Batch <?= htmlspecialchars($s['batch_no']) ?>)</h2>

<p><b>Heads Sold:</b> <?= $s['num_heads_sold'] ?></p>
<p><b>Total Market Weight:</b> <?= $s['total_market_weight'] ?> kg</p>
<p><b>Price per kg:</b> ₱<?= number_format($s['price_per_kg'],2) ?></p>
<p><b>Total Revenue:</b> ₱<?= number_format($total_revenue,2) ?></p>
<p><b>Total Expenses:</b> ₱<?= number_format($s['total_expenses'],2) ?></p>
<p><b>Net Profit:</b> ₱<?= number_format($net_profit,2) ?></p>
<p><b>Profit per Head:</b> ₱<?= number_format($s['profit_per_head'],2) ?></p>
<p><b>Market Date:</b> <?= $s['market_date'] ?></p>
<p><b>Remarks:</b><br><?= nl2br(htmlspecialchars($s['remarks'])) ?></p>

<br>
<a href="edit_sale.php?id=<?= $s['sale_id'] ?>">✏️ Edit</a> |
<a href="list_sale.php">← Back</a>
</body>
</html>
