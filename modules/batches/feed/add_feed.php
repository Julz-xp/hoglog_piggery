<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch batch list for dropdown
$batches = $pdo->query("SELECT batch_id, batch_no FROM batch_records ORDER BY batch_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch_id           = $_POST['batch_id'];
    $feed_stage         = $_POST['feed_stage'];
    $start_date         = $_POST['start_date'];
    $end_date           = $_POST['end_date'];
    $expected_days      = $_POST['expected_days'];
    $expected_intake_per_day = $_POST['expected_intake_per_day'];
    $expected_feed_total = $_POST['expected_feed_total'];
    $actual_feed_total  = $_POST['actual_feed_total'];
    $price_per_kg       = $_POST['price_per_kg'];
    $remarks            = $_POST['remarks'];

    $stmt = $pdo->prepare("
        INSERT INTO batch_feed_consumption 
        (batch_id, feed_stage, start_date, end_date, expected_days, expected_intake_per_day,
         expected_feed_total, actual_feed_total, price_per_kg, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $batch_id, $feed_stage, $start_date, $end_date, $expected_days, $expected_intake_per_day,
        $expected_feed_total, $actual_feed_total, $price_per_kg, $remarks
    ]);

    header("Location: list_feed.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Add Feed Record</title></head>
<body>
<h2>Add Batch Feed Record</h2>
<form method="POST">
    Batch:
    <select name="batch_id" required>
        <option value="">-- Select Batch --</option>
        <?php foreach ($batches as $b): ?>
        <option value="<?= $b['batch_id'] ?>"><?= htmlspecialchars($b['batch_no']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    Feed Stage:
    <select name="feed_stage" required>
        <option value="Pre-Starter">Pre-Starter</option>
        <option value="Starter">Starter</option>
        <option value="Grower">Grower</option>
        <option value="Finisher">Finisher</option>
        <option value="High-Energy Finisher">High-Energy Finisher</option>
    </select><br><br>

    Start Date: <input type="date" name="start_date">  
    End Date: <input type="date" name="end_date"><br><br>
    Expected Days: <input type="number" name="expected_days"><br><br>
    Expected Intake (kg/day): <input type="number" step="0.01" name="expected_intake_per_day"><br><br>
    Expected Feed Total (kg): <input type="number" step="0.01" name="expected_feed_total"><br><br>
    Actual Feed Total (kg): <input type="number" step="0.01" name="actual_feed_total"><br><br>
    Price per kg (₱): <input type="number" step="0.01" name="price_per_kg"><br><br>
    Remarks:<br><textarea name="remarks" rows="4" cols="50"></textarea><br><br>

    <button type="submit">Save Feed Record</button>
</form>
<br>
<a href="list_feed.php">← Back to list</a>
</body>
</html>
