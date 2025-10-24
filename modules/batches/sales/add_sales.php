<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch batches for dropdown
$batches = $pdo->query("SELECT batch_id, batch_no FROM batch_records ORDER BY batch_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch_id          = $_POST['batch_id'];
    $num_heads_sold    = $_POST['num_heads_sold'];
    $total_market_weight = $_POST['total_market_weight'];
    $price_per_kg      = $_POST['price_per_kg'];
    $total_expenses    = $_POST['total_expenses'];
    $profit_per_head   = $_POST['profit_per_head'];
    $market_date       = $_POST['market_date'];
    $remarks           = $_POST['remarks'];

    $stmt = $pdo->prepare("
        INSERT INTO batch_sales_record
        (batch_id, num_heads_sold, total_market_weight, price_per_kg, total_expenses, profit_per_head, market_date, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$batch_id, $num_heads_sold, $total_market_weight, $price_per_kg, $total_expenses, $profit_per_head, $market_date, $remarks]);

    header("Location: list_sale.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Add Batch Sale</title></head>
<body>
<h2>Add Batch Sale Record</h2>
<form method="POST">
    Batch:
    <select name="batch_id" required>
        <option value="">-- Select Batch --</option>
        <?php foreach ($batches as $b): ?>
        <option value="<?= $b['batch_id'] ?>"><?= htmlspecialchars($b['batch_no']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    Number of Heads Sold: <input type="number" name="num_heads_sold" required><br><br>
    Total Market Weight (kg): <input type="number" step="0.01" name="total_market_weight" required><br><br>
    Price per kg (₱): <input type="number" step="0.01" name="price_per_kg" required><br><br>
    Total Expenses (₱): <input type="number" step="0.01" name="total_expenses" required><br><br>
    Profit per Head (₱): <input type="number" step="0.01" name="profit_per_head"><br><br>
    Market Date: <input type="date" name="market_date"><br><br>
    Remarks:<br><textarea name="remarks" rows="4" cols="50"></textarea><br><br>

    <button type="submit">Save Sale Record</button>
</form>
<br>
<a href="list_sale.php">← Back to list</a>
</body>
</html>
