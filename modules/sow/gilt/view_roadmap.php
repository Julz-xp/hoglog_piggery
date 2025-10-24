<?php
require_once __DIR__ . '/../../../config/db.php';

$sow_id = isset($_GET['sow_id']) ? (int) $_GET['sow_id'] : 0;

// Fetch sow basic info
$stmt = $pdo->prepare("SELECT sow_id, ear_tag_no, breed_line, date_of_birth, status FROM sows WHERE sow_id = ?");
$stmt->execute([$sow_id]);
$sow = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch roadmaps
$feed_stmt = $pdo->prepare("SELECT * FROM gilt_feed_roadmap WHERE sow_id = ? ORDER BY id ASC");
$feed_stmt->execute([$sow_id]);
$feed_roadmap = $feed_stmt->fetchAll(PDO::FETCH_ASSOC);

$health_stmt = $pdo->prepare("SELECT * FROM gilt_health_roadmap WHERE sow_id = ? ORDER BY id ASC");
$health_stmt->execute([$sow_id]);
$health_roadmap = $health_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Gilt Roadmap View</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
<style>
  body { background-color: #f8f9fa; }
  .table thead { background-color: #198754; color: #fff; }
  .status-badge { padding: 0.35em 0.65em; border-radius: 0.5rem; font-size: 0.85em; text-transform: capitalize; }
  .status-completed { background-color: #d1e7dd; color: #0f5132; }
  .status-current   { background-color: #fff3cd; color: #664d03; }
  .status-upcoming  { background-color: #e2e3e5; color: #41464b; }
</style>
</head>
<body class="p-4">
<div class="container">

  <div class="text-center mb-4">
    <i class="bi bi-piggy-bank fs-1 text-success"></i>
    <h2 class="mt-2">Gilt Roadmap Overview</h2>
    <p class="text-muted mb-0">Feed and Health Management Plan</p>
  </div>

  <?php if ($sow) { ?>
    <div class="card mb-4 shadow-sm">
      <div class="card-body">
        <h5 class="card-title text-success mb-3">Sow Information</h5>
        <p class="mb-1"><strong>Ear Tag:</strong> <?= htmlspecialchars($sow['ear_tag_no']) ?></p>
        <p class="mb-1"><strong>Breed / Line:</strong> <?= htmlspecialchars($sow['breed_line']) ?></p>
        <p class="mb-1"><strong>Date of Birth:</strong> <?= htmlspecialchars($sow['date_of_birth']) ?></p>
        <p class="mb-0"><strong>Status:</strong> <?= htmlspecialchars($sow['status']) ?></p>
      </div>
    </div>

    <!-- Feed Roadmap -->
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-success text-white">
        <i class="bi bi-egg-fried"></i> Gilt Feed Roadmap
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Stage</th>
                <th>Duration</th>
                <th>Age Range</th>
                <th>Feed Type</th>
                <th>Daily Feed (kg)</th>
                <th>Purpose</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($feed_roadmap)) { ?>
                <?php foreach ($feed_roadmap as $row) { ?>
                  <tr>
                    <td><?= htmlspecialchars($row['stage_name']) ?></td>
                    <td><?= htmlspecialchars($row['duration_days']) ?></td>
                    <td><?= htmlspecialchars($row['age_range']) ?></td>
                    <td><?= htmlspecialchars($row['feed_type']) ?></td>
                    <td><?= htmlspecialchars($row['daily_feed']) ?></td>
                    <td><?= htmlspecialchars($row['purpose']) ?></td>
                    <td><span class="status-badge status-<?= htmlspecialchars($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                  </tr>
                <?php } ?>
              <?php } else { ?>
                <tr><td colspan="7" class="text-center text-muted">No feed roadmap entries found.</td></tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Health Roadmap -->
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-info text-white">
        <i class="bi bi-heart-pulse"></i> Gilt Health Roadmap
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Stage</th>
                <th>Age Range</th>
                <th>Treatment / Action</th>
                <th>Purpose</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($health_roadmap)) { ?>
                <?php foreach ($health_roadmap as $row) { ?>
                  <tr>
                    <td><?= htmlspecialchars($row['stage_name']) ?></td>
                    <td><?= htmlspecialchars($row['age_range']) ?></td>
                    <td><?= htmlspecialchars($row['treatment_action']) ?></td>
                    <td><?= htmlspecialchars($row['purpose']) ?></td>
                    <td><span class="status-badge status-<?= htmlspecialchars($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                  </tr>
                <?php } ?>
              <?php } else { ?>
                <tr><td colspan="5" class="text-center text-muted">No health roadmap entries found.</td></tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="text-center">
      <a href="../profile/list_sow.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left-circle"></i> Back to List
      </a>
    </div>

  <?php } else { ?>
    <div class="alert alert-danger">
      <i class="bi bi-exclamation-triangle"></i> No sow found for the given ID.
    </div>
    <a href="../profile/list_sow.php" class="btn btn-secondary">
      <i class="bi bi-arrow-left-circle"></i> Back to List
    </a>
  <?php } ?>

</div>
</body>
</html>
