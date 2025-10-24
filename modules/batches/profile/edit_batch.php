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
<title>Edit Batch - <?= htmlspecialchars($b['batch_no']) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    font-family: "Poppins", sans-serif;
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(15px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    border-radius: 16px;
    width: 100%;
    max-width: 800px;
    animation: fadeIn 0.5s ease;
}

.card-header {
    background: linear-gradient(135deg, #ffb74d, #ff9800);
    color: #fff;
    border-radius: 16px 16px 0 0;
    font-weight: 600;
}

.form-label {
    font-weight: 500;
}

.btn {
    border-radius: 10px;
    font-weight: 600;
    transition: 0.3s ease;
}

.btn-warning:hover { transform: translateY(-2px); }
.btn-secondary:hover { transform: translateY(-2px); }

@keyframes fadeIn {
  from {opacity: 0; transform: translateY(20px);}
  to {opacity: 1; transform: translateY(0);}
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .row > .col { margin-bottom: 15px; }
    .text-end { text-align: center !important; }
}
</style>
</head>
<body>

<div class="card p-4">
    <div class="card-header mb-3">
        <h4 class="mb-0">✏ Edit Batch - <?= htmlspecialchars($b['batch_no']) ?></h4>
    </div>

    <form method="POST">

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

</body>
</html>
