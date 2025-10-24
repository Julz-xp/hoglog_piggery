<?php
require_once __DIR__ . '/../../../config/db.php';

// FIXED: Remove created_at sorting (column doesn't exist)
$stmt = $pdo->query("SELECT * FROM batch_records ORDER BY batch_id DESC");
$batches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Batch List | HogLog</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { background: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .sidebar {
            height: 100vh;
            width: 240px;
            position: fixed;
            left: 0; top: 0;
            background: #212529;
            padding-top: 25px;
            color: white;
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            color: #adb5bd;
            font-size: 15px;
        }
        .sidebar a i { margin-right: 8px; }
        .sidebar a:hover, .sidebar a.active { background: #0d6efd; color: #fff; }
        .content { margin-left: 260px; padding: 25px; }
        .topbar {
            margin-left: 240px;
            height: 60px;
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 0 25px;
        }
        th { background: #0d6efd !important; color: white; }
        .btn-sm { padding: 4px 8px; font-size: 13px; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4 class="text-center mb-4"><i class="fa-solid fa-piggy-bank"></i> HogLog</h4>

    <a href="../dashboard/batch_fattener.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="add_batch.php"><i class="fa-solid fa-folder-plus"></i> Add Batch</a>
    <a href="#" class="active"><i class="fa-solid fa-list"></i> Batch List</a>
    <a href="/hoglog_piggery/modules/batches/feed/list_feed.php"><i class="fa-solid fa-wheat-awn"></i> Feed Records</a>
    <a href="../growth_summary.php"><i class="fa-solid fa-chart-simple"></i> Growth Summary</a>
    <a href="../mortality.php"><i class="fa-solid fa-skull-crossbones"></i> Mortality</a>
    <a href="../sales.php"><i class="fa-solid fa-hand-holding-dollar"></i> Sales</a>
</div>


<!-- TOPBAR -->
<div class="topbar">
    <h5 class="m-0"><i class="fa-solid fa-list"></i> Batch Records</h5>
    <span class="text-secondary">HogLog Smart Piggery System</span>
</div>

<!-- CONTENT -->
<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Batch List</h4>
        <a href="add_batch.php" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Add Batch
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><i class="fa-solid fa-check"></i> Batch added successfully.</div>
    <?php endif; ?>
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-info"><i class="fa-solid fa-pen-to-square"></i> Batch updated successfully.</div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-danger"><i class="fa-solid fa-trash"></i> Batch deleted.</div>
    <?php endif; ?>

    <div class="card p-3 shadow-sm">
        <table class="table table-hover align-middle">
            <thead>
            <tr>
                <th>ID</th>
                <th>Batch No</th>
                <th>Total Pigs</th>
                <th>Breed</th>
                <th>Birth Date</th>
                <th>Status</th>
                <th width="100">View</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($batches): foreach ($batches as $b): ?>
                <tr>
                    <td><?= $b['batch_id'] ?></td>
                    <td><?= htmlspecialchars($b['batch_no']) ?></td>
                    <td><?= $b['num_pigs_total'] ?></td>
                    <td><?= htmlspecialchars($b['breed']) ?></td>
                    <td><?= $b['birth_date'] ?></td>
                    <td>
                        <span class="badge <?= $b['status'] == 'Active' ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $b['status'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="view_batch.php?id=<?= $b['batch_id'] ?>" class="btn btn-sm btn-info text-white">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="7" class="text-center">No batch records found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
