<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die('Invalid request');
$id = $_GET['id'];

// Fetch batch details
$stmt = $pdo->prepare("SELECT * FROM batch_records WHERE batch_id=?");
$stmt->execute([$id]);
$b = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$b) die('Batch not found');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Batch Details - <?= htmlspecialchars($b['batch_no']) ?></title>
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
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Batch Details - <?= htmlspecialchars($b['batch_no']) ?></h4>
    </div>

    <div class="card-body">
        <table class="table table-borderless">
            <tbody>
                <tr><th>Building</th><td><?= htmlspecialchars($b['building_position']) ?></td></tr>
                <tr><th>Total Pigs</th><td><?= $b['num_pigs_total'] ?> (♂ <?= $b['num_male'] ?> / ♀ <?= $b['num_female'] ?>)</td></tr>
                <tr><th>Breed</th><td><?= htmlspecialchars($b['breed']) ?></td></tr>
                <tr><th>Birth Date</th><td><?= $b['birth_date'] ?></td></tr>
                <tr><th>Average Birth Weight</th><td><?= $b['avg_birth_weight'] ?> kg</td></tr>
                <tr><th>Source Sow</th><td><?= htmlspecialchars($b['source_sow']) ?></td></tr>
                <tr><th>Source Boar</th><td><?= htmlspecialchars($b['source_boar']) ?></td></tr>
                <tr><th>Weaning Date</th><td><?= $b['weaning_date'] ?></td></tr>
                <tr><th>Average Weaning Weight</th><td><?= $b['avg_weaning_weight'] ?> kg</td></tr>
                <tr><th>Expected Market Date</th><td><?= $b['expected_market_date'] ?></td></tr>
                <tr><th>Status</th><td><span class="badge bg-success"><?= htmlspecialchars($b['status']) ?></span></td></tr>
                <tr><th>Remarks</th><td><?= nl2br(htmlspecialchars($b['remarks'])) ?></td></tr>
            </tbody>
        </table>

        <div class="text-end mt-3">
            <a href="edit_batch.php?id=<?= $b['batch_id'] ?>" class="btn btn-warning me-2">✏ Edit</a>
            <a href="delete_batch.php?id=<?= $b['batch_id'] ?>" class="btn btn-danger me-2" onclick="return confirm('Are you sure you want to delete this batch?')">🗑 Delete</a>
            <a href="http://localhost/hoglog_piggery/modules/fattener/profile/add_profile.php?id=<?= $b['batch_id'] ?>" class="btn btn-info me-2">🐖 Add Fattener</a>
            <a href="/hoglog_piggery/modules/batches/profile/list_batches.php" class="btn btn-secondary">← Back to List</a>
        </div>
    </div>
</div>

</body>
</html>
