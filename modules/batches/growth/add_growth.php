<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch batch list for dropdown
$batches = $pdo->query("SELECT batch_id, batch_no FROM batch_records ORDER BY batch_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch_id         = $_POST['batch_id'];
    $stage            = $_POST['stage'];
    $avg_initial_weight = $_POST['avg_initial_weight'];
    $avg_final_weight   = $_POST['avg_final_weight'];
    $avg_adg          = $_POST['avg_adg'];
    $avg_fcr          = $_POST['avg_fcr'];
    $avg_feed_consumed = $_POST['avg_feed_consumed'];
    $mortality_count  = $_POST['mortality_count'];
    $remarks          = $_POST['remarks'];

    $stmt = $pdo->prepare("
        INSERT INTO batch_growth_summary 
        (batch_id, stage, avg_initial_weight, avg_final_weight, avg_adg, avg_fcr, avg_feed_consumed, mortality_count, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$batch_id, $stage, $avg_initial_weight, $avg_final_weight, $avg_adg, $avg_fcr, $avg_feed_consumed, $mortality_count, $remarks]);

    header("Location: list_growth.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Add Growth Record</title></head>
<body>
<h2>Add Batch Growth Record</h2>
<form method="POST">
    Batch:
    <select name="batch_id" required>
        <option value="">-- Select Batch --</option>
        <?php foreach ($batches as $b): ?>
        <option value="<?= $b['batch_id'] ?>"><?= htmlspecialchars($b['batch_no']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    Stage:
    <select name="stage" required>
        <option value="Weaning-Starter">Weaning → Starter</option>
        <option value="Starter-Grower">Starter → Grower</option>
        <option value="Grower-Finisher">Grower → Finisher</option>
        <option value="Finisher-Market">Finisher → Market</option>
    </select><br><br>

    Avg Initial Wt (kg): <input type="number" step="0.01" name="avg_initial_weight"><br><br>
    Avg Final Wt (kg): <input type="number" step="0.01" name="avg_final_weight"><br><br>
    Avg ADG (kg/day): <input type="number" step="0.001" name="avg_adg"><br><br>
    Avg FCR: <input type="number" step="0.001" name="avg_fcr"><br><br>
    Avg Feed Consumed (kg): <input type="number" step="0.01" name="avg_feed_consumed"><br><br>
    Mortality Count: <input type="number" name="mortality_count"><br><br>
    Remarks:<br>
    <textarea name="remarks" rows="4" cols="50"></textarea><br><br>

    <button type="submit">Save Growth Record</button>
</form>
<br>
<a href="list_growth.php">← Back to list</a>
</body>
</html>
