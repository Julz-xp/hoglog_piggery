<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die('Invalid request');
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM batch_records WHERE batch_id=?");
$stmt->execute([$id]);
$b = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$b) die('Batch not found');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [
        'batch_no','building_position','num_pigs_total','num_male','num_female','breed',
        'birth_date','avg_birth_weight','source_sow','source_boar','weaning_date',
        'avg_weaning_weight','expected_market_date','status','remarks'
    ];
    foreach ($fields as $f) $$f = $_POST[$f] ?? null;

    $stmt = $pdo->prepare("
        UPDATE batch_records SET
        batch_no=?, building_position=?, num_pigs_total=?, num_male=?, num_female=?, breed=?, birth_date=?,
        avg_birth_weight=?, source_sow=?, source_boar=?, weaning_date=?, avg_weaning_weight=?,
        expected_market_date=?, status=?, remarks=?
        WHERE batch_id=?
    ");
    $stmt->execute([
        $batch_no, $building_position, $num_pigs_total, $num_male, $num_female, $breed, $birth_date,
        $avg_birth_weight, $source_sow, $source_boar, $weaning_date, $avg_weaning_weight,
        $expected_market_date, $status, $remarks, $id
    ]);
    header("Location: list_batch.php?updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Edit Batch</title></head>
<body>
<h2>Edit Batch: <?= htmlspecialchars($b['batch_no']) ?></h2>
<form method="POST">
    Batch No: <input type="text" name="batch_no" value="<?= $b['batch_no'] ?>"><br><br>
    Building / Pen: <input type="text" name="building_position" value="<?= $b['building_position'] ?>"><br><br>
    Total Pigs: <input type="number" name="num_pigs_total" value="<?= $b['num_pigs_total'] ?>">
    &nbsp; Male: <input type="number" name="num_male" value="<?= $b['num_male'] ?>">
    &nbsp; Female: <input type="number" name="num_female" value="<?= $b['num_female'] ?>"><br><br>
    Breed: <input type="text" name="breed" value="<?= $b['breed'] ?>"><br><br>
    Birth Date: <input type="date" name="birth_date" value="<?= $b['birth_date'] ?>">
    &nbsp; Avg Birth Wt (kg): <input type="number" step="0.01" name="avg_birth_weight" value="<?= $b['avg_birth_weight'] ?>"><br><br>
    Source Sow: <input type="text" name="source_sow" value="<?= $b['source_sow'] ?>">
    &nbsp; Source Boar: <input type="text" name="source_boar" value="<?= $b['source_boar'] ?>"><br><br>
    Weaning Date: <input type="date" name="weaning_date" value="<?= $b['weaning_date'] ?>">
    &nbsp; Avg Weaning Wt (kg): <input type="number" step="0.01" name="avg_weaning_weight" value="<?= $b['avg_weaning_weight'] ?>"><br><br>
    Expected Market Date: <input type="date" name="expected_market_date" value="<?= $b['expected_market_date'] ?>"><br><br>
    Status:
    <select name="status">
        <option value="Active" <?= $b['status']=='Active'?'selected':'' ?>>Active</option>
        <option value="Sold" <?= $b['status']=='Sold'?'selected':'' ?>>Sold</option>
        <option value="Closed" <?= $b['status']=='Closed'?'selected':'' ?>>Closed</option>
    </select><br><br>
    Remarks:<br>
    <textarea name="remarks" rows="4" cols="50"><?= htmlspecialchars($b['remarks']) ?></textarea><br><br>

    <button type="submit">Update Batch</button>
</form>
<br>
<a href="list_batch.php">← Back to list</a>
</body>
</html>
