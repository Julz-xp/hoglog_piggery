<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM batch_sales_record WHERE sale_id=?");
$stmt->execute([$id]);
$s = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$s) die("Record not found");

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
        UPDATE batch_sales_record SET
        batch_id=?, num_heads_sold=?, total_market_weight=?, price_per_kg=?, total_expenses=?, profit_per_head=?, market_date=?, remarks=?
        WHERE sale_id=?
    ");
    $stmt->execute([$batch_id, $num_heads_sold, $total_market_weight, $price_per_kg, $total_expenses, $profit_per_head, $market_date, $remarks, $id]);

    header("Location: list_sale.php?updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Edit Batch Sale</title></head>
<body>
<h2>Edit Batch Sale Record</h2>
<form method="POST">
    Batch:
    <select name="batch_id" required>
        <?php foreach ($batches as $b): ?>
        <option value="<?= $b['batch_id'] ?>" <?= $b['batch_id']==$s['batch_id']?'selected':'' ?>>
            <?= htmlspecialchars($b['batch_no']) ?>
        </option>
        <?php endforeach; ?>
    </select><br><br>

    Number of Heads Sold: <input type="number" name="num_heads_sold" value="<?= $s['num_heads_sold'] ?>"><br><br>
    Total Market Weight (kg): <input type="number" step="0.01" name="total_market_weight" value="<?= $s['total_market_weight'] ?>"><br><br>
    Price per kg (₱): <input type="number" step="0.01" name="price_per_kg" value="<?= $s['price_per_kg'] ?>"><br><br>
    Total Expenses (₱): <input type="number" step="0.01" name="total_expenses" value="<?= $s['total_expenses'] ?>"><br><br>
    Profit per Head (₱): <input type="number" step="0.01" name="profit_per_head" value="<?= $s['profit_per_head'] ?>"><br><br>
    Market Date: <input type="date" name="market_date" value="<?= $s['market_date'] ?>"><br><br>
    Remarks:<br><textarea name="remarks" rows="4" cols="50"><?= htmlspecialchars($s['remarks']) ?></textarea><br><br>

    <button type="submit">Update Sale</button>
</form>
<br>
<a href="list_sale.php">← Back</a>
</body>
</html>
