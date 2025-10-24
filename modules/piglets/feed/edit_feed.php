<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die("Invalid request.");
$id = $_GET['id'];

// Fetch existing record
$stmt = $pdo->prepare("SELECT * FROM piglet_feed_consumption WHERE feed_id=?");
$stmt->execute([$id]);
$feed = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$feed) die("Record not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $piglet_id = $_POST['piglet_id'];
    $feed_type = $_POST['feed_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $daily_intake = $_POST['daily_intake'];
    $price_per_kg = $_POST['price_per_kg'];
    $remarks = $_POST['remarks'];

    $total_days = (strtotime($end_date) - strtotime($start_date)) / 86400;
    $total_feed_consumed = $daily_intake * $total_days;
    $total_feed_cost = $total_feed_consumed * $price_per_kg;

    $stmt = $pdo->prepare("UPDATE piglet_feed_consumption SET 
        piglet_id=?, feed_type=?, start_date=?, end_date=?, daily_intake=?, price_per_kg=?, 
        total_days=?, total_feed_consumed=?, total_feed_cost=?, remarks=?
        WHERE feed_id=?");

    $stmt->execute([$piglet_id, $feed_type, $start_date, $end_date, $daily_intake,
                    $price_per_kg, $total_days, $total_feed_consumed, $total_feed_cost, $remarks, $id]);

    header("Location: list_feed.php?updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Piglet Feed Record</title></head>
<body>
<h2>Edit Piglet Feed Record</h2>
<form method="POST">
    Piglet ID: <input type="number" name="piglet_id" value="<?= $feed['piglet_id'] ?>" required><br><br>
    Feed Type:
    <select name="feed_type">
        <option <?= $feed['feed_type']=='Creep Feed'?'selected':'' ?>>Creep Feed</option>
        <option <?= $feed['feed_type']=='Booster'?'selected':'' ?>>Booster</option>
        <option <?= $feed['feed_type']=='Starter'?'selected':'' ?>>Starter</option>
    </select><br><br>
    Start Date: <input type="date" name="start_date" value="<?= $feed['start_date'] ?>"><br><br>
    End Date: <input type="date" name="end_date" value="<?= $feed['end_date'] ?>"><br><br>
    Daily Intake (kg): <input type="number" step="0.01" name="daily_intake" value="<?= $feed['daily_intake'] ?>"><br><br>
    Price per kg (₱): <input type="number" step="0.01" name="price_per_kg" value="<?= $feed['price_per_kg'] ?>"><br><br>
    Remarks:<br>
    <textarea name="remarks" rows="3" cols="40"><?= $feed['remarks'] ?></textarea><br><br>
    <button type="submit">Update</button>
</form>
</body>
</html>
