<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT s.*, f.ear_tag_no 
                       FROM fattener_sales_record s
                       JOIN fattener_records f ON s.fattener_id = f.fattener_id
                       WHERE s.sale_id = ?");
$stmt->execute([$id]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$r) die("Record not found.");
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>View Sales Record</title></head>
<body>
<h2>View Sales Record</h2>

<p><b>Fattener:</b> <?= htmlspecialchars($r['ear_tag_no']) ?></p>
<p><b>Sale Date:</b> <?= htmlspecialchars($r['sale_date']) ?></p>
<p><b>Market Weight:</b> <?= htmlspecialchars($r['market_weight']) ?> kg</p>
<p><b>Price per kg:</b> ₱<?= htmlspecialchars($r['price_per_kg']) ?></p>
<p><b>Total Revenue:</b> ₱<?= htmlspecialchars($r['total_revenue']) ?></p>
<p><b>Total Feed Cost:</b> ₱<?= htmlspecialchars($r['total_feed_cost']) ?></p>
<p><b>Total Health Cost:</b> ₱<?= htmlspecialchars($r['total_health_cost']) ?></p>
<p><b>Total Expenses:</b> ₱<?= htmlspecialchars($r['total_expenses']) ?></p>
<p><b>Net Profit:</b> ₱<?= htmlspecialchars($r['net_profit']) ?></p>
<p><b>Buyer:</b> <?= htmlspecialchars($r['buyer_name']) ?></p>
<p><b>Payment Method:</b> <?= htmlspecialchars($r['payment_method']) ?></p>
<p><b>Remarks:</b> <?= nl2br(htmlspecialchars($r['remarks'])) ?></p>

<br>
<a href="edit_sales.php?id=<?= $r['sale_id'] ?>">✏️ Edit</a> |
<a href="list_sales.php">⬅️ Back to List</a>
</body>
</html>
