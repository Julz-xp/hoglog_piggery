<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM fattener_sales_record WHERE sale_id = ?");
$stmt->execute([$id]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$r) die("Record not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sale_date = $_POST['sale_date'];
    $market_weight = $_POST['market_weight'];
    $price_per_kg = $_POST['price_per_kg'];
    $total_feed_cost = $_POST['total_feed_cost'];
    $total_health_cost = $_POST['total_health_cost'];
    $buyer_name = $_POST['buyer_name'];
    $payment_method = $_POST['payment_method'];
    $remarks = $_POST['remarks'];

    // 🧮 Auto Compute again
    $total_revenue = $market_weight * $price_per_kg;
    $total_expenses = $total_feed_cost + $total_health_cost;
    $net_profit = $total_revenue - $total_expenses;

    $update = $pdo->prepare("UPDATE fattener_sales_record 
        SET sale_date=?, market_weight=?, price_per_kg=?, total_revenue=?, total_feed_cost=?, total_health_cost=?, total_expenses=?, net_profit=?, buyer_name=?, payment_method=?, remarks=? 
        WHERE sale_id=?");
    $update->execute([$sale_date, $market_weight, $price_per_kg, $total_revenue, $total_feed_cost, $total_health_cost, $total_expenses, $net_profit, $buyer_name, $payment_method, $remarks, $id]);

    header("Location: list_sales.php?updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Edit Sales Record</title></head>
<body>
<h2>Edit Sales Record</h2>

<form method="POST">
    Sale Date: <input type="date" name="sale_date" value="<?= $r['sale_date'] ?>"><br><br>
    Market Weight (kg): <input type="number" step="0.01" name="market_weight" value="<?= $r['market_weight'] ?>"><br><br>
    Price per kg (₱): <input type="number" step="0.01" name="price_per_kg" value="<?= $r['price_per_kg'] ?>"><br><br>
    Total Feed Cost (₱): <input type="number" step="0.01" name="total_feed_cost" value="<?= $r['total_feed_cost'] ?>"><br><br>
    Total Health Cost (₱): <input type="number" step="0.01" name="total_health_cost" value="<?= $r['total_health_cost'] ?>"><br><br>
    Buyer Name: <input type="text" name="buyer_name" value="<?= htmlspecialchars($r['buyer_name']) ?>"><br><br>
    Payment Method: <input type="text" name="payment_method" value="<?= htmlspecialchars($r['payment_method']) ?>"><br><br>
    Remarks:<br><textarea name="remarks" rows="3" cols="40"><?= htmlspecialchars($r['remarks']) ?></textarea><br><br>

    <input type="submit" value="Update Record">
</form>

<br>
<a href="list_sales.php">⬅️ Back to List</a>
</body>
</html>
