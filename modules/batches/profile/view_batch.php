<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die('Invalid request');
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM batch_records WHERE batch_id=?");
$stmt->execute([$id]);
$b = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$b) die('Batch not found');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Batch Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Batch Details - <?= htmlspecialchars($b['batch_no']) ?></h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <tbody>
                    <tr><th width="30%">Building</th><td><?= htmlspecialchars($b['building_position']) ?></td></tr>
                    <tr><th>Total Pigs</th><td><?= $b['num_pigs_total'] ?> (♂ <?= $b['num_male'] ?> / ♀ <?= $b['num_female'] ?>)</td></tr>
                    <tr><th>Breed</th><td><?= htmlspecialchars($b['breed']) ?></td></tr>
                    <tr><th>Birth Date</th><td><?= $b['birth_date'] ?></td></tr>
                    <tr><th>Average Birth Weight</th><td><?= $b['avg_birth_weight'] ?> kg</td></tr>
                    <tr><th>Source Sow</th><td><?= htmlspecialchars($b['source_sow']) ?></td></tr>
                    <tr><th>Source Boar</th><td><?= htmlspecialchars($b['source_boar']) ?></td></tr>
                    <tr><th>Weaning Date</th><td><?= $b['weaning_date'] ?></td></tr>
                    <tr><th>Average Weaning Weight</th><td><?= $b['avg_weaning_weight'] ?> kg</td></tr>
                    <tr><th>Expected Market Date</th><td><?= $b['expected_market_date'] ?></td></tr>
                    <tr><th>Status</th><td><span class="badge bg-success"><?= $b['status'] ?></span></td></tr>
                    <tr><th>Remarks</th><td><?= nl2br(htmlspecialchars($b['remarks'])) ?></td></tr>
                </tbody>
            </table>

            <div class="text-end mt-3">
                <a href="edit_batch.php?id=<?= $b['batch_id'] ?>" class="btn btn-warning">✏ Edit</a>
                <a href="/hoglog_piggery/modules/batches/profile/list_batches.php" class="btn btn-secondary">← Back to List</a>
            </div>

        </div>
    </div>

</div>

</body>
</html>
