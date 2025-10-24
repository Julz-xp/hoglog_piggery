<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch fattener list
$fatteners = $pdo->query("SELECT fattener_id, ear_tag_no FROM fattener_records ORDER BY fattener_id DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fattener_id = $_POST['fattener_id'];
    $sale_date = $_POST['sale_date'];
    $market_weight = $_POST['market_weight'];
    $price_per_kg = $_POST['price_per_kg'];
    $total_feed_cost = $_POST['total_feed_cost'];
    $total_health_cost = $_POST['total_health_cost'];
    $buyer_name = $_POST['buyer_name'];
    $payment_method = $_POST['payment_method'];
    $remarks = $_POST['remarks'];

    // 🧮 Auto Computations
    $total_revenue = $market_weight * $price_per_kg;
    $total_expenses = $total_feed_cost + $total_health_cost;
    $net_profit = $total_revenue - $total_expenses;

    // 🗄️ Insert record
    $stmt = $pdo->prepare("INSERT INTO fattener_sales_record 
        (fattener_id, sale_date, market_weight, price_per_kg, total_revenue, total_feed_cost, total_health_cost, total_expenses, net_profit, buyer_name, payment_method, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$fattener_id, $sale_date, $market_weight, $price_per_kg, $total_revenue, $total_feed_cost, $total_health_cost, $total_expenses, $net_profit, $buyer_name, $payment_method, $remarks]);

    header("Location: list_sales.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Add Sales Record</title>
</head>
<body>
<h2>➕ Add Sales Record</h2>

<form method="POST">
    Fattener:
    <select name="fattener_id" required>
        <option value="">-- Select Fattener --</option>
        <?php foreach ($fatteners as $f): ?>
            <option value="<?= $f['fattener_id'] ?>"><?= htmlspecialchars($f['ear_tag_no']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    Sale Date: <input type="date" name="sale_date" required><br><br>
    Market Weight (kg): <input type="number" step="0.01" name="market_weight" required><br><br>
    Price per kg (₱): <input type="number" step="0.01" name="price_per_kg" required><br><br>
    Total Feed Cost (₱): <input type="number" step="0.01" name="total_feed_cost" required><br><br>
    Total Health Cost (₱): <input type="number" step="0.01" name="total_health_cost" required><br><br>
    Buyer Name: <input type="text" name="buyer_name"><br><br>
    Payment Method: <input type="text" name="payment_method"><br><br>
    Remarks:<br><textarea name="remarks" rows="3" cols="40"></textarea><br><br>

    <input type="submit" value="Add Record">
</form>

<br>
<a href="list_sales.php">⬅️ Back to List</a>
</body>
</html>
