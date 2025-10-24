<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

// Fetch record
$stmt = $pdo->prepare("SELECT * FROM batch_feed_consumption WHERE feed_id=?");
$stmt->execute([$id]);
$f = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$f) die("Record not found");

// Fetch batches
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
        UPDATE batch_feed_consumption SET
        batch_id=?, feed_stage=?, start_date=?, end_date=?, expected_days=?, expected_intake_per_day=?,
        expected_feed_total=?, actual_feed_total=?, price_per_kg=?, remarks=?
        WHERE feed_id=?
    ");
    $stmt->execute([
        $batch_id, $feed_stage, $start_date, $end_date, $expected_days, $expected_intake_per_day,
        $expected_feed_total, $actual_feed_total, $price_per_kg, $remarks, $id
    ]);

    header("Location: list_feed.php?updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Edit Feed Record | HogLog</title>
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
    max-width: 700px;
    animation: fadeIn 0.5s ease;
    padding: 25px;
}

.card-header {
    background: linear-gradient(135deg, #4fc3f7, #29b6f6);
    color: #fff;
    border-radius: 16px 16px 0 0;
    font-weight: 600;
    padding: 15px;
    text-align: center;
}

form .mb-3 { margin-bottom: 15px; }

label { font-weight: 600; }

input, select, textarea { border-radius: 8px; }

.btn {
    border-radius: 10px;
    font-weight: 600;
    transition: 0.3s ease;
}

.btn-primary:hover { transform: translateY(-2px); }
.btn-secondary:hover { transform: translateY(-2px); }

.btn-group { display: flex; gap: 10px; margin-top: 20px; justify-content: flex-end; }

@keyframes fadeIn {
  from {opacity: 0; transform: translateY(20px);}
  to {opacity: 1; transform: translateY(0);}
}
</style>
</head>
<body>

<div class="card">
    <div class="card-header">
        Edit Feed Record - <?= htmlspecialchars($f['feed_id']) ?>
    </div>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label>Batch</label>
            <select name="batch_id" class="form-select" required>
                <?php foreach ($batches as $b): ?>
                <option value="<?= $b['batch_id'] ?>" <?= $b['batch_id']==$f['batch_id']?'selected':'' ?>>
                    <?= htmlspecialchars($b['batch_no']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Feed Stage</label>
            <select name="feed_stage" class="form-select" required>
                <?php
                $stages = ['Pre-Starter','Starter','Grower','Finisher','High-Energy Finisher'];
                foreach ($stages as $s):
                    $sel = ($f['feed_stage']==$s)?'selected':'';
                ?>
                <option value="<?= $s ?>" <?= $sel ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="row">
            <div class="mb-3 col-md-6">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?= $f['start_date'] ?>">
            </div>
            <div class="mb-3 col-md-6">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?= $f['end_date'] ?>">
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-md-4">
                <label>Expected Days</label>
                <input type="number" name="expected_days" class="form-control" value="<?= $f['expected_days'] ?>">
            </div>
            <div class="mb-3 col-md-4">
                <label>Expected Intake (kg/day)</label>
                <input type="number" step="0.01" name="expected_intake_per_day" class="form-control" value="<?= $f['expected_intake_per_day'] ?>">
            </div>
            <div class="mb-3 col-md-4">
                <label>Expected Feed Total (kg)</label>
                <input type="number" step="0.01" name="expected_feed_total" class="form-control" value="<?= $f['expected_feed_total'] ?>">
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-md-6">
                <label>Actual Feed Total (kg)</label>
                <input type="number" step="0.01" name="actual_feed_total" class="form-control" value="<?= $f['actual_feed_total'] ?>">
            </div>
            <div class="mb-3 col-md-6">
                <label>Price per kg (₱)</label>
                <input type="number" step="0.01" name="price_per_kg" class="form-control" value="<?= $f['price_per_kg'] ?>">
            </div>
        </div>

        <div class="mb-3">
            <label>Remarks</label>
            <textarea name="remarks" class="form-control" rows="4"><?= htmlspecialchars($f['remarks']) ?></textarea>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Update Record</button>
            <a href="list_feed.php" class="btn btn-secondary">← Back</a>
        </div>
    </form>
</div>

</body>
</html>
