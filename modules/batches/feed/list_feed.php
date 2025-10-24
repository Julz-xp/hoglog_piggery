<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch Feed Records joined with Batch
$stmt = $pdo->query("
    SELECT f.*, b.batch_no
    FROM batch_feed_consumption f
    JOIN batch_records b ON f.batch_id = b.batch_id
    ORDER BY f.feed_id DESC
");
$feeds = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feed Records | HogLog</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { 
            background: #f8f9fa; 
            font-family: 'Poppins', sans-serif; 
            font-size: 14px;
        }
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
            font-size: 14px;
        }
        .sidebar a:hover, .sidebar a.active { 
            background: #0d6efd; 
            color: #fff; 
        }
        .sidebar a.text-warning { 
            color: #ffc107 !important; 
            font-weight: 600; 
        }
        .sidebar a.text-warning:hover { 
            background-color: #ffc107; 
            color: #212529 !important; 
        }
        .content { 
            margin-left: 260px; 
            padding: 25px; 
        }
        .topbar {
            margin-left: 240px;
            height: 60px;
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            font-weight: 500;
        }
        th { 
            background: #0d6efd !important; 
            color: white; 
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .btn-primary {
            border-radius: 8px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4 class="text-center mb-4">HogLog</h4>

    <a href="../dashboard/batch_fattener.php">Dashboard</a>
    <a href="../profile/add_batch.php">Add Batch</a>
    <a href="../profile/list_batches.php">Batch List</a>
    <a href="#" class="active">Feed Records</a>
    <a href="../growth_summary.php">Growth Summary</a>
    <a href="../mortality.php">Mortality</a>
    <a href="../sales.php">Sales</a>

    <a href="/hoglog_piggery/modules/users/user_dashboard.php" class="text-warning mt-3">
        ⬅️ Back to User Dashboard
    </a>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <h5 class="m-0">Feed Records</h5>
    <span class="text-secondary">HogLog Smart Piggery System</span>
</div>

<!-- CONTENT -->
<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Batch Feed Consumption</h4>
        <a href="add_feed.php" class="btn btn-primary btn-sm">
            Add Feed Record
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Feed record added successfully.</div>
    <?php endif; ?>
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-info">Record updated successfully.</div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-danger">Feed record deleted.</div>
    <?php endif; ?>

    <div class="card p-3 shadow-sm">
        <table class="table table-hover align-middle">
            <thead>
            <tr>
                <th>ID</th>
                <th>Batch</th>
                <th>Stage</th>
                <th>Start</th>
                <th>End</th>
                <th>Feed (kg)</th>
                <th>₱/kg</th>
                <th width="120">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($feeds): foreach ($feeds as $f): ?>
                <tr>
                    <td><?= $f['feed_id'] ?></td>
                    <td><?= htmlspecialchars($f['batch_no']) ?></td>
                    <td><?= $f['feed_stage'] ?></td>
                    <td><?= $f['start_date'] ?></td>
                    <td><?= $f['end_date'] ?></td>
                    <td><?= $f['actual_feed_total'] ?></td>
                    <td><?= $f['price_per_kg'] ?></td>
                    <td>
                        <a href="view_feed.php?id=<?= $f['feed_id'] ?>" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="8" class="text-center">No feed records found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
