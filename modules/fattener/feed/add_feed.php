<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch fattener list
$fatteners = $pdo->query("SELECT fattener_id, ear_tag_no FROM fattener_records ORDER BY fattener_id DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fattener_id = $_POST['fattener_id'];
    $feed_type = $_POST['feed_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $daily_intake = $_POST['daily_intake'];
    $price_per_kg = $_POST['price_per_kg'];
    $remarks = $_POST['remarks'];

    // 🧮 Auto Compute
    $total_days = (strtotime($end_date) - strtotime($start_date)) / 86400;
    $total_feed_consumed = $daily_intake * $total_days;
    $total_feed_cost = $total_feed_consumed * $price_per_kg;

    // 🗄️ Insert Record
    $stmt = $pdo->prepare("INSERT INTO fattener_feed_consumption 
        (fattener_id, feed_type, start_date, end_date, daily_intake, total_days, total_feed_consumed, price_per_kg, total_feed_cost, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$fattener_id, $feed_type, $start_date, $end_date, $daily_intake, $total_days, $total_feed_consumed, $price_per_kg, $total_feed_cost, $remarks]);

    header("Location: list_feed.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Add Feed Consumption</title>
</head>
<body>
<h2>➕ Add Feed Consumption</h2>

<form method="POST">
    Fattener: 
    <select name="fattener_id" required>
        <option value="">-- Select Fattener --</option>
        <?php foreach ($fatteners as $f): ?>
            <option value="<?= $f['fattener_id'] ?>"><?= htmlspecialchars($f['ear_tag_no']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    Feed Type:
    <select name="feed_type" required>
        <option value="Pre-Starter">Pre-Starter</option>
        <option value="Starter">Starter</option>
        <option value="Grower">Grower</option>
        <option value="Finisher">Finisher</option>
    </select><br><br>

    Start Date: <input type="date" name="start_date" required><br><br>
    End Date: <input type="date" name="end_date" required><br><br>
    Daily Intake (kg): <input type="number" step="0.01" name="daily_intake" required><br><br>
    Price per kg (₱): <input type="number" step="0.01" name="price_per_kg" required><br><br>
    Remarks:<br>
    <textarea name="remarks" rows="3" cols="40"></textarea><br><br>

    <input type="submit" value="Add Record">
</form>

<br>
<a href="list_feed.php">⬅️ Back to List</a>
</body>
</html>
