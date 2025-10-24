<?php
require_once __DIR__ . '/../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch_no            = $_POST['batch_no'];
    $building_position   = $_POST['building_position'] ?? null;
    $num_pigs_total      = $_POST['num_pigs_total'] ?: null;
    $num_male            = $_POST['num_male'] ?: null;
    $num_female          = $_POST['num_female'] ?: null;
    $breed               = $_POST['breed'] ?? null;
    $birth_date          = $_POST['birth_date'] ?? null;
    $avg_birth_weight    = $_POST['avg_birth_weight'] ?: null;
    $source_sow          = $_POST['source_sow'] ?? null;
    $source_boar         = $_POST['source_boar'] ?? null;
    $weaning_date        = $_POST['weaning_date'] ?? null;
    $avg_weaning_weight  = $_POST['avg_weaning_weight'] ?: null;
    $expected_market_date= $_POST['expected_market_date'] ?? null;
    $status              = $_POST['status'] ?? 'Active';
    $remarks             = $_POST['remarks'] ?? null;

    $stmt = $pdo->prepare("
        INSERT INTO batch_records
        (batch_no, building_position, num_pigs_total, num_male, num_female, breed, birth_date,
         avg_birth_weight, source_sow, source_boar, weaning_date, avg_weaning_weight,
         expected_market_date, status, remarks)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([
        $batch_no, $building_position, $num_pigs_total, $num_male, $num_female, $breed, $birth_date,
        $avg_birth_weight, $source_sow, $source_boar, $weaning_date, $avg_weaning_weight,
        $expected_market_date, $status, $remarks
    ]);

    header("Location: list_batch.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Add Batch</title></head>
<body>
<h2>Add Batch Record</h2>
<form method="POST">
    Batch No: <input type="text" name="batch_no" required><br><br>
    Building / Pen: <input type="text" name="building_position"><br><br>
    Total Pigs: <input type="number" name="num_pigs_total" min="0">
    &nbsp; Male: <input type="number" name="num_male" min="0">
    &nbsp; Female: <input type="number" name="num_female" min="0"><br><br>
    Breed: <input type="text" name="breed"><br><br>
    Birth Date: <input type="date" name="birth_date">
    &nbsp; Avg Birth Wt (kg): <input type="number" step="0.01" name="avg_birth_weight"><br><br>
    Source Sow: <input type="text" name="source_sow">
    &nbsp; Source Boar: <input type="text" name="source_boar"><br><br>
    Weaning Date: <input type="date" name="weaning_date">
    &nbsp; Avg Weaning Wt (kg): <input type="number" step="0.01" name="avg_weaning_weight"><br><br>
    Expected Market Date: <input type="date" name="expected_market_date"><br><br>
    Status:
    <select name="status">
        <option value="Active">Active</option>
        <option value="Sold">Sold</option>
        <option value="Closed">Closed</option>
    </select><br><br>
    Remarks:<br>
    <textarea name="remarks" rows="4" cols="50"></textarea><br><br>

    <button type="submit">Save Batch</button>
</form>
<br>
<a href="list_batch.php">← Back to list</a>
</body>
</html>
