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
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🐖 Add Feed Record</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    border-radius: 16px;
    width: 100%;
    max-width: 700px;
    padding: 30px;
    animation: fadeIn 0.5s ease;
}

.card h3 {
    color: #0288d1;
    font-weight: 700;
    margin-bottom: 20px;
    text-align: center;
}

.btn-primary, .btn-secondary {
    border-radius: 10px;
    font-weight: 600;
    transition: 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0288d1, #03a9f4);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
}

.btn-secondary:hover {
    background: #b0bec5;
    transform: translateY(-2px);
}

@keyframes fadeIn {
  from {opacity: 0; transform: translateY(20px);}
  to {opacity: 1; transform: translateY(0);}
}

textarea { resize: none; }

@media (max-width: 576px) {
    .row > .col-md-6, .row > .col-md-4 { margin-bottom: 15px; }
}
</style>
</head>
<body>

<div class="card">
    <h3>🐖 Add Feed Record</h3>
    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Batch</label>
            <select name="batch_id" class="form-select" required>
                <option value="">-- Select Batch --</option>
                <?php foreach ($batches as $b): ?>
                <option value="<?= $b['batch_id'] ?>"><?= htmlspecialchars($b['batch_no']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Feed Stage</label>
            <select name="feed_stage" class="form-select" required>
                <option value="Pre-Starter">Pre-Starter</option>
                <option value="Starter">Starter</option>
                <option value="Grower">Grower</option>
                <option value="Finisher">Finisher</option>
                <option value="High-Energy Finisher">High-Energy Finisher</option>
            </select>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Expected Days</label>
                <input type="number" name="expected_days" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Expected Intake (kg/day)</label>
                <input type="number" step="0.01" name="expected_intake_per_day" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Expected Feed Total (kg)</label>
                <input type="number" step="0.01" name="expected_feed_total" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Actual Feed Total (kg)</label>
                <input type="number" step="0.01" name="actual_feed_total" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Price per kg (₱)</label>
            <input type="number" step="0.01" name="price_per_kg" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="3"></textarea>
        </div>

        <div class="d-flex justify-content-between">
            <a href="list_feed.php" class="btn btn-secondary">← Back to Feed List</a>
            <button type="submit" class="btn btn-primary">Save Feed Record</button>
        </div>

    </form>
</div>

</body>
</html>
