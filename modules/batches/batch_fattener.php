<?php
require_once __DIR__ . '/../../config/db.php';

// ===== FETCH SUMMARY DATA =====
$total_batches = $pdo->query("SELECT COUNT(*) FROM batch_records")->fetchColumn();
$total_pigs = $pdo->query("SELECT SUM(num_pigs_total) FROM batch_records")->fetchColumn();
$active_batches = $pdo->query("SELECT COUNT(*) FROM batch_records WHERE status='Active'")->fetchColumn();
$total_feed_cost = $pdo->query("SELECT SUM(total_feed_cost) FROM batch_expenses")->fetchColumn();
$avg_profit = $pdo->query("SELECT AVG(profit_per_head) FROM batch_sales_record")->fetchColumn();

// ===== CHART DATA =====
$growthData = $pdo->query("
    SELECT b.batch_no, g.avg_final_weight
    FROM batch_growth_summary g
    JOIN batch_records b ON b.batch_id = g.batch_id
    ORDER BY b.batch_no ASC
")->fetchAll(PDO::FETCH_ASSOC);

$feedData = $pdo->query("
    SELECT b.batch_no, e.total_feed_cost
    FROM batch_expenses e
    JOIN batch_records b ON b.batch_id = e.batch_id
    ORDER BY b.batch_no ASC
")->fetchAll(PDO::FETCH_ASSOC);

$recent_batches = $pdo->query("
    SELECT batch_no, breed, num_pigs_total, status, expected_market_date 
    FROM batch_records 
    ORDER BY batch_id DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fatteners Dashboard | HogLog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        /* Sidebar */
        .sidebar {
            height: 100vh;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #212529;
            color: #fff;
            padding-top: 25px;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
        }
        .sidebar h4 {
            text-align: center;
            font-weight: 600;
            margin-bottom: 25px;
        }
        .sidebar a {
            display: block;
            color: #adb5bd;
            padding: 12px 20px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #0d6efd;
            color: white;
        }
        /* Topbar */
        .topbar {
            margin-left: 240px;
            height: 60px;
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .topbar h5 {
            margin: 0;
            font-weight: 600;
        }
        .content {
            margin-left: 250px;
            padding: 25px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        th {
            background-color: #0d6efd;
            color: white;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4>🐖 HogLog</h4>
    <a href="#" class="active">Dashboard</a>
    <a href="../profile/add_batch.php">Add Batch</a>
    <a href="../profile/list_batch.php">Batch List</a>
    <a href="../feed/list_feed.php">Feed Records</a>
    <a href="../growth_summary.php">Growth Summary</a>
    <a href="../mortality.php">Mortality</a>
    <a href="../sales.php">Sales</a>
</div>


<!-- TOPBAR -->
<div class="topbar">
    <h5>Fatteners Dashboard</h5>
    <span class="text-secondary">HogLog Smart Piggery System</span>
</div>

<!-- CONTENT -->
<div class="content">
    <!-- Summary Cards -->
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card p-3 bg-light text-center">
                <h6>Total Batches</h6>
                <h3 class="fw-bold"><?= $total_batches ?: 0 ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 bg-light text-center">
                <h6>Total Pigs</h6>
                <h3 class="fw-bold"><?= $total_pigs ?: 0 ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 bg-light text-center">
                <h6>Active Batches</h6>
                <h3 class="fw-bold"><?= $active_batches ?: 0 ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 bg-light text-center">
                <h6>Total Feed Cost</h6>
                <h3 class="fw-bold text-primary">₱<?= number_format($total_feed_cost ?: 0, 2) ?></h3>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-md-3">
            <div class="card p-3 bg-light text-center">
                <h6>Avg. Profit per Head</h6>
                <h3 class="fw-bold text-success">₱<?= number_format($avg_profit ?: 0, 2) ?></h3>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="mb-3">Average Final Weight per Batch</h5>
                <canvas id="growthChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="mb-3">Feed Cost per Batch</h5>
                <canvas id="feedChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Batch Records -->
    <div class="mt-5">
        <h5 class="mb-3 fw-bold">Recent Batch Records</h5>
        <table class="table table-striped table-hover align-middle">
            <thead>
                <tr>
                    <th>Batch No</th>
                    <th>Breed</th>
                    <th>Total Pigs</th>
                    <th>Status</th>
                    <th>Expected Market Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_batches as $batch): ?>
                    <tr>
                        <td><?= htmlspecialchars($batch['batch_no']) ?></td>
                        <td><?= htmlspecialchars($batch['breed']) ?></td>
                        <td><?= htmlspecialchars($batch['num_pigs_total']) ?></td>
                        <td><?= htmlspecialchars($batch['status']) ?></td>
                        <td><?= htmlspecialchars($batch['expected_market_date']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($recent_batches)): ?>
                    <tr><td colspan="5" class="text-center">No records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JS for Charts -->
<script>
const growthCtx = document.getElementById('growthChart');
const feedCtx = document.getElementById('feedChart');

new Chart(growthCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($growthData, 'batch_no')) ?>,
        datasets: [{
            label: 'Avg. Final Weight (kg)',
            data: <?= json_encode(array_column($growthData, 'avg_final_weight')) ?>,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13,110,253,0.3)',
            fill: true,
            tension: 0.3
        }]
    }
});

new Chart(feedCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($feedData, 'batch_no')) ?>,
        datasets: [{
            label: 'Total Feed Cost (₱)',
            data: <?= json_encode(array_column($feedData, 'total_feed_cost')) ?>,
            backgroundColor: 'rgba(255,193,7,0.6)',
            borderColor: '#ffc107'
        }]
    }
});
</script>

</body>
</html>
