<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['sow_id'])) {
  die("Missing sow_id.");
}

$sow_id = (int)$_GET['sow_id'];

// 🐖 Fetch sow details
$stmt = $pdo->prepare("SELECT * FROM sows WHERE sow_id = ?");
$stmt->execute([$sow_id]);
$sow = $stmt->fetch();

if (!$sow) {
  die("Sow not found.");
}

// 🧮 Compute age in days
$dob = new DateTime($sow['date_of_birth']);
$today = new DateTime();
$age_in_days = $today->diff($dob)->days;

// 🩶 Fetch feed roadmap (current + near future)
$feedStmt = $pdo->prepare("
  SELECT *, 
    CASE
      WHEN ? BETWEEN start_age_days AND end_age_days THEN 'current'
      WHEN ? > end_age_days THEN 'completed'
      ELSE 'upcoming'
    END AS computed_status
  FROM gilt_feed_roadmap
  WHERE sow_id = ?
  AND (
      ? BETWEEN start_age_days - 15 AND end_age_days + 30
  )
  ORDER BY start_age_days ASC
");
$feedStmt->execute([$age_in_days, $age_in_days, $sow_id, $age_in_days]);
$feedRoadmap = $feedStmt->fetchAll();

// 💉 Fetch health roadmap (same filter)
$healthStmt = $pdo->prepare("
  SELECT *, 
    CASE
      WHEN ? BETWEEN start_age_days AND end_age_days THEN 'current'
      WHEN ? > end_age_days THEN 'completed'
      ELSE 'upcoming'
    END AS computed_status
  FROM gilt_health_roadmap
  WHERE sow_id = ?
  AND (
      ? BETWEEN start_age_days - 15 AND end_age_days + 30
  )
  ORDER BY start_age_days ASC
");
$healthStmt->execute([$age_in_days, $age_in_days, $sow_id, $age_in_days]);
$healthRoadmap = $healthStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Gilt Roadmap</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
  body { background-color: #f8f9fa; }
  h2 { font-weight: 600; color: #2f3640; }
  .status-current { background-color: #fff3cd !important; }
  .status-completed { background-color: #d4edda !important; }
  .status-upcoming { background-color: #e2e3e5 !important; }
</style>
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-3">🐖 Gilt Roadmap Overview</h2>
  <p class="text-muted">Ear Tag: <strong><?= htmlspecialchars($sow['ear_tag_no']) ?></strong> | 
  Age: <strong><?= $age_in_days ?> days</strong> | Status: <strong><?= htmlspecialchars($sow['status']) ?></strong></p>

  <!-- 🟩 FEED ROADMAP -->
  <div class="card mb-4">
    <div class="card-header bg-success text-white">
      <h5 class="mb-0">Feed Roadmap</h5>
    </div>
    <div class="card-body p-0">
      <table class="table table-bordered table-striped mb-0 text-center align-middle">
        <thead class="table-success">
          <tr>
            <th>Stage</th>
            <th>Age Range (days)</th>
            <th>Feed Type</th>
            <th>Daily Feed (kg)</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($feedRoadmap as $feed): 
          $rowClass = 'status-' . $feed['computed_status'];
        ?>
          <tr class="<?= $rowClass ?>">
            <td><?= htmlspecialchars($feed['stage_name']) ?></td>
            <td><?= htmlspecialchars($feed['start_age_days'] . '–' . $feed['end_age_days']) ?></td>
            <td><?= htmlspecialchars($feed['feed_type']) ?></td>
            <td><?= htmlspecialchars($feed['daily_feed']) ?></td>
            <td class="fw-bold text-capitalize"><?= $feed['computed_status'] ?></td>
            <td>
              <a href="../feed/add_feed.php?sow_id=<?= $sow_id ?>&stage=<?= urlencode($feed['stage_name']) ?>" 
                 class="btn btn-sm btn-outline-success">
                 <i class="bi bi-plus-circle"></i> Add Feed Record
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- 💉 HEALTH ROADMAP -->
  <div class="card mb-4">
    <div class="card-header bg-info text-white">
      <h5 class="mb-0">Health Roadmap</h5>
    </div>
    <div class="card-body p-0">
      <table class="table table-bordered table-striped mb-0 text-center align-middle">
        <thead class="table-info">
          <tr>
            <th>Stage</th>
            <th>Age Range (days)</th>
            <th>Treatment / Action</th>
            <th>Purpose</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($healthRoadmap as $health): 
          $rowClass = 'status-' . $health['computed_status'];
        ?>
          <tr class="<?= $rowClass ?>">
            <td><?= htmlspecialchars($health['stage_name']) ?></td>
            <td><?= htmlspecialchars($health['start_age_days'] . '–' . $health['end_age_days']) ?></td>
            <td><?= htmlspecialchars($health['treatment_action']) ?></td>
            <td><?= htmlspecialchars($health['purpose']) ?></td>
            <td class="fw-bold text-capitalize"><?= $health['computed_status'] ?></td>
            <td>
              <a href="../health/add_health.php?sow_id=<?= $sow_id ?>&stage=<?= urlencode($health['stage_name']) ?>" 
                 class="btn btn-sm btn-outline-info">
                 <i class="bi bi-plus-circle"></i> Add Health Record
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <a href="../profile/list_sow.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>
</body>
</html>
