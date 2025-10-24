<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['farm_id'])) {
  header("Location: /hoglog_piggery/farm_login.php");
  exit;
}

$farm_id = (int)$_SESSION['farm_id'];

/* --- COUNT HELPERS --- */
function sowCount($pdo, $farm_id, $status = null) {
  if ($status === null) {
    $q = $pdo->prepare("SELECT COUNT(*) FROM sows WHERE farm_id=?");
    $q->execute([$farm_id]);
  } else {
    $q = $pdo->prepare("SELECT COUNT(*) FROM sows WHERE farm_id=? AND status=?");
    $q->execute([$farm_id, $status]);
  }
  return (int)$q->fetchColumn();
}

$total     = sowCount($pdo, $farm_id);
$gilt      = sowCount($pdo, $farm_id, 'Gilt');
$gestation = sowCount($pdo, $farm_id, 'Gestating');
$lactation = sowCount($pdo, $farm_id, 'Lactating');
$dry       = sowCount($pdo, $farm_id, 'Dry');

/* --- FILE PARSERS (optional) --- */
function captureNumber($file) {
  if (!file_exists($file)) return 0;
  ob_start(); include $file; $html = trim(ob_get_clean());
  if (preg_match_all('/\d{1,3}(?:,\d{3})*(?:\.\d+)?|\d+\.\d+/', strip_tags($html), $m)) {
    $nums = array_map(fn($x)=>(float)str_replace(',', '', $x), $m[0]);
    rsort($nums);
    return $nums[0] ?? 0;
  }
  return 0;
}

$totalExpenses = captureNumber(__DIR__ . '/sow_total_expenses.php');
$totalFeedKg   = captureNumber(__DIR__ . '/feed_consume.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sow Dashboard | HogLog Smart Piggery System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body {
  background-color: #f8fafc;
  font-family: "Poppins", sans-serif;
}
.sidebar {
  position: fixed;
  left: 0;
  top: 0;
  height: 100%;
  width: 230px;
  background-color: #0f172a;
  color: #fff;
  padding-top: 20px;
}
.sidebar h4 {
  font-weight: 700;
  text-align: center;
  margin-bottom: 25px;
}
.sidebar a {
  display: block;
  color: #cbd5e1;
  padding: 12px 20px;
  text-decoration: none;
  transition: background 0.2s, color 0.2s;
}
.sidebar a:hover, .sidebar a.active {
  background-color: #1d4ed8;
  color: #fff;
}
.main-content {
  margin-left: 230px;
  padding: 25px;
}
.topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}
.topbar h2 {
  font-weight: 700;
}
.card {
  border: none;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  border-radius: 10px;
}
.card h5 {
  font-size: 15px;
  color: #64748b;
}
.card h3 {
  font-weight: 700;
}
.chart-container {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4>🐷 HogLog</h4>
  <a href="#" class="active">Dashboard</a>
  <a href="profile/add_sow.php">Add Sow</a>
  <a href="profile/list_sow.php">Sow List</a>
  <a href="feed_records.php">Feed Records</a>
  <a href="expenses.php">Expenses</a>
  <a href="calendar.php">Calendar</a>
  <a href="/hoglog_piggery/modules/users/user_dashboard.php">← Back to User</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <div class="topbar">
    <h2>Sow Dashboard</h2>
    <div><span style="color:#475569;">HogLog Smart Piggery System</span></div>
  </div>

  <!-- Summary Cards -->
  <div class="row g-3 mb-4">
    <div class="col-md-2">
      <div class="card text-center p-3">
        <h5>Total Sows</h5>
        <h3><?= number_format($total) ?></h3>
      </div>
    </div>
    <div class="col-md-2">
      <div class="card text-center p-3">
        <h5>Gilt</h5>
        <h3><?= number_format($gilt) ?></h3>
      </div>
    </div>
    <div class="col-md-2">
      <div class="card text-center p-3">
        <h5>Gestating</h5>
        <h3><?= number_format($gestation) ?></h3>
      </div>
    </div>
    <div class="col-md-2">
      <div class="card text-center p-3">
        <h5>Lactating</h5>
        <h3><?= number_format($lactation) ?></h3>
      </div>
    </div>
    <div class="col-md-2">
      <div class="card text-center p-3">
        <h5>Dry</h5>
        <h3><?= number_format($dry) ?></h3>
      </div>
    </div>
    <div class="col-md-2">
      <div class="card text-center p-3">
        <h5>Total Feed (kg)</h5>
        <h3><?= number_format($totalFeedKg,2) ?></h3>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card text-center p-3">
        <h5>Total Expenses (₱)</h5>
        <h3 class="text-primary">₱<?= number_format($totalExpenses,2) ?></h3>
      </div>
    </div>
  </div>

  <!-- Charts -->
  <div class="row g-4">
    <div class="col-md-6">
      <div class="chart-container">
        <h5 class="text-center mb-3">Sow Population</h5>
        <canvas id="chartPopulation"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="chart-container">
        <h5 class="text-center mb-3">Feed vs Expenses</h5>
        <canvas id="chartFeedExp"></canvas>
      </div>
    </div>
  </div>

</div>

<script>
const counts = {
  gilt: <?= $gilt ?>,
  gestation: <?= $gestation ?>,
  lactation: <?= $lactation ?>,
  dry: <?= $dry ?>,
  total: <?= $total ?>
};

// Population Bar Chart
new Chart(document.getElementById('chartPopulation'), {
  type: 'bar',
  data: {
    labels: ['Gilt', 'Gestation', 'Lactation', 'Dry'],
    datasets: [{
      label: 'No. of Sows',
      data: [counts.gilt, counts.gestation, counts.lactation, counts.dry],
      backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444']
    }]
  },
  options: {
    responsive: true,
    scales: { y: { beginAtZero: true } },
    plugins: { legend: { display: false } }
  }
});

// Feed vs Expense Doughnut
new Chart(document.getElementById('chartFeedExp'), {
  type: 'doughnut',
  data: {
    labels: ['Feed (kg)', 'Expenses (₱)'],
    datasets: [{
      data: [<?= $totalFeedKg ?>, <?= $totalExpenses ?>],
      backgroundColor: ['#facc15', '#06b6d4']
    }]
  },
  options: {
    plugins: { legend: { position: 'bottom' } },
    cutout: '70%'
  }
});
</script>

</body>
</html>
