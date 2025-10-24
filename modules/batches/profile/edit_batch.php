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

    header("Location: /hoglog_piggery/modules/batches/profile/list_batches.php?updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Edit Batch</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-header bg-warning">
            <h4 class="mb-0">✏ Edit Batch - <?= htmlspecialchars($b['batch_no']) ?></h4>
        </div>

        <form method="POST" class="card-body">

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Batch No</label>
                    <input type="text" name="batch_no" class="form-control" value="<?= htmlspecialchars($b['batch_no']) ?>">
                </div>
                <div class="col">
                    <label class="form-label">Building / Pen</label>
                    <input type="text" name="building_position" class="form-control" value="<?= htmlspecialchars($b['building_position']) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Total Pigs</label>
                    <input type="number" name="num_pigs_total" class="form-control" value="<?= $b['num_pigs_total'] ?>">
                </div>
                <div class="col">
                    <label class="form-label">Male</label>
                    <input type="number" name="num_male" class="form-control" value="<?= $b['num_male'] ?>">
                </div>
                <div class="col">
                    <label class="form-label">Female</label>
                    <input type="number" name="num_female" class="form-control" value="<?= $b['num_female'] ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Breed</label>
                <input type="text" name="breed" class="form-control" value="<?= htmlspecialchars($b['breed']) ?>">
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Birth Date</label>
                    <input type="date" name="birth_date" class="form-control" value="<?= $b['birth_date'] ?>">
                </div>
                <div class="col">
                    <label class="form-label">Avg Birth Weight (kg)</label>
                    <input type="number" step="0.01" name="avg_birth_weight" class="form-control" value="<?= $b['avg_birth_weight'] ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Source Sow</label>
                    <input type="text" name="source_sow" class="form-control" value="<?= htmlspecialchars($b['source_sow']) ?>">
                </div>
                <div class="col">
                    <label class="form-label">Source Boar</label>
                    <input type="text" name="source_boar" class="form-control" value="<?= htmlspecialchars($b['source_boar']) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Weaning Date</label>
                    <input type="date" name="weaning_date" class="form-control" value="<?= $b['weaning_date'] ?>">
                </div>
                <div class="col">
                    <label class="form-label">Avg Weaning Weight (kg)</label>
                    <input type="number" step="0.01" name="avg_weaning_weight" class="form-control" value="<?= $b['avg_weaning_weight'] ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Expected Market Date</label>
                <input type="date" name="expected_market_date" class="form-control" value="<?= $b['expected_market_date'] ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Active" <?= $b['status']=='Active'?'selected':'' ?>>Active</option>
                    <option value="Sold" <?= $b['status']=='Sold'?'selected':'' ?>>Sold</option>
                    <option value="Closed" <?= $b['status']=='Closed'?'selected':'' ?>>Closed</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Remarks</label>
                <textarea name="remarks" rows="4" class="form-control"><?= htmlspecialchars($b['remarks']) ?></textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-warning">💾 Update Batch</button>
                <a href="/hoglog_piggery/modules/batches/profile/list_batches.php" class="btn btn-secondary">← Back to List</a>
            </div>

        </form>
    </div>

</div>

</body>
</html>
