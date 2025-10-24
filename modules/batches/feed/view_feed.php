<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT f.*, b.batch_no
    FROM batch_feed_consumption f
    JOIN batch_records b ON f.batch_id = b.batch_id
    WHERE f.feed_id=?
");
$stmt->execute([$id]);
$f = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$f) die("Feed record not found");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Feed Record - <?= htmlspecialchars($f['batch_no']) ?> | HogLog</title>
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
    max-width: 900px;
    animation: fadeIn 0.5s ease;
}

.card-header {
    background: linear-gradient(135deg, #4fc3f7, #29b6f6);
    color: #fff;
    border-radius: 16px 16px 0 0;
    font-weight: 600;
}

.table th {
    width: 30%;
    font-weight: 600;
}

.btn {
    border-radius: 10px;
    font-weight: 600;
    transition: 0.3s ease;
}

.btn-warning:hover { transform: translateY(-2px); }
.btn-danger:hover { transform: translateY(-2px); }
.btn-info:hover { transform: translateY(-2px); }
.btn-secondary:hover { transform: translateY(-2px); }

@keyframes fadeIn {
  from {opacity: 0; transform: translateY(20px);}
  to {opacity: 1; transform: translateY(0);}
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .table th, .table td { display: block; width: 100%; }
    .table tbody tr { display: block; margin-bottom: 10px; }
    .text-end { text-align: center !important; }
}
</style>
</head>
<body>

<div class="card">
    <div class="card-header">
        Feed Record - <?= htmlspecialchars($f['batch_no']) ?>
    </div>

    <div class="card-body">
        <table class="table table-borderless">
            <tbody>
                <tr><th>Feed Stage</th><td><?= htmlspecialchars($f['feed_stage']) ?></td></tr>
                <tr><th>Start Date</th><td><?= htmlspecialchars($f['start_date']) ?></td></tr>
                <tr><th>End Date</th><td><?= htmlspecialchars($f['end_date']) ?></td></tr>
                <tr><th>Expected Days</th><td><?= htmlspecialchars($f['expected_days']) ?></td></tr>
                <tr><th>Expected Intake/Day</th><td><?= htmlspecialchars($f['expected_intake_per_day']) ?> kg</td></tr>
                <tr><th>Expected Feed Total</th><td><?= htmlspecialchars($f['expected_feed_total']) ?> kg</td></tr>
                <tr><th>Actual Feed Total</th><td><?= htmlspecialchars($f['actual_feed_total']) ?> kg</td></tr>
                <tr><th>Price per kg</th><td>₱<?= htmlspecialchars($f['price_per_kg']) ?></td></tr>
                <tr><th>Total Feed Cost</th><td>₱<?= number_format($f['actual_feed_total'] * $f['price_per_kg'], 2) ?></td></tr>
                <tr><th>Remarks</th><td><?= nl2br(htmlspecialchars($f['remarks'])) ?></td></tr>
            </tbody>
        </table>

        <div class="text-end mt-3">
            <a href="edit_feed.php?id=<?= $f['feed_id'] ?>" class="btn btn-warning me-2">✏ Edit</a>
            <a href="delete_feed.php?id=<?= $f['feed_id'] ?>" class="btn btn-danger me-2" onclick="return confirm('Are you sure you want to delete this feed record?')">🗑 Delete</a>
            <a href="list_feed.php" class="btn btn-secondary">← Back to List</a>
        </div>
    </div>
</div>

</body>
</html>
