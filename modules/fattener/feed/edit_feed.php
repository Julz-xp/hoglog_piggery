<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM fattener_feed_consumption WHERE feed_id = ?");
$stmt->execute([$id]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$r) die("Record not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feed_type = $_POST['feed_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $daily_intake = $_POST['daily_intake'];
    $price_per_kg = $_POST['price_per_kg'];
    $remarks = $_POST['remarks'];

    // 🧮 Auto compute
    $total_days = (strtotime($end_date) - strtotime($start_date)) / 86400;
    $total_feed_consumed = $daily_intake * $total_days;
    $total_feed_cost = $total_feed_consumed * $price_per_kg;

    $stmt = $pdo->prepare("UPDATE fattener_feed_consumption 
        SET feed_type=?, start_date=?, end_date=?, daily_intake=?, total_days=?, total_feed_consumed=?, price_per_kg=?, total_feed_cost=?, remarks=? 
        WHERE feed_id=?");
    $stmt->execute([$feed_type, $start_date, $end_date, $daily_intake, $total_days, $total_feed_consumed, $price_per_kg, $total_feed_cost, $remarks, $id]);

    header("Location: list_feed.php?updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Edit Feed Record</title></head>
<body>
<h2>Edit Feed Record</h2>

<form method="POST">
    Feed Type:
    <select name="feed_type">
        <option value="Pre-Starter" <?= $r['feed_type']=='Pre-Starter'?'selected':'' ?>>Pre-Starter</option>
        <option value="Starter" <?= $r['feed_type']=='Starter'?'selected':'' ?>>Starter</option>
        <option value="Grower" <?= $r['feed_type']=='Grower'?'selected':'' ?>>Grower</option>
        <option value="Finisher" <?= $r['feed_type']=='Finisher'?'selected':'' ?>>Finisher</option>
    </select><br><br>

    Start Date: <input type="date" name="start_date" value="<?= $r['start_date'] ?>"><br><br>
    End Date: <input type="date" name="end_date" value="<?= $r['end_date'] ?>"><br><br>
    Daily Intake (kg): <input type="number" step="0.01" name="daily_intake" value="<?= $r['daily_intake'] ?>"><br><br>
    Price per kg (₱): <input type="number" step="0.01" name="price_per_kg" value="<?= $r['price_per_kg'] ?>"><br><br>
    Remarks:<br>
    <textarea name="remarks" rows="3" cols="40"><?= htmlspecialchars($r['remarks']) ?></textarea><br><br>

    <input type="submit" value="Update Record">
</form>

<br>
<a href="list_feed.php">⬅️ Back to List</a>
</body>
</html>
