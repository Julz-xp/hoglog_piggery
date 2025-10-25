<?php
require_once __DIR__ . '/../../../config/db.php';

$sow_id = $_GET['sow_id'] ?? null;
if (!$sow_id) {
  die("No sow ID provided.");
}

// 🐖 Fetch sow info
$stmt = $pdo->prepare("SELECT * FROM sows WHERE sow_id = ?");
$stmt->execute([$sow_id]);
$sow = $stmt->fetch(PDO::FETCH_ASSOC);

// 🧾 Fetch Feed Roadmap
$feed = $pdo->prepare("SELECT * FROM gestation_feed_roadmap WHERE sow_id = ? ORDER BY id ASC");
$feed->execute([$sow_id]);
$feedRoadmap = $feed->fetchAll(PDO::FETCH_ASSOC);

// 💉 Fetch Health Roadmap
$health = $pdo->prepare("SELECT * FROM gestation_health_roadmap WHERE sow_id = ? ORDER BY id ASC");
$health->execute([$sow_id]);
$healthRoadmap = $health->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Gestation Roadmap</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background-color: #f8f9fa; }
.card { border: none; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
h2, h3 { color: #2f3640; font-weight: 600; }
.table th { background-color: #198754; color: white; }
.table td, .table th { vertical-align: middle; }
.section-title { background: #198754; color: #fff; padding: 10px; border-radius: 8px; margin-bottom: 15px; }
.btn-add { background-color: #0d6efd; color: #fff; border: none; }
.btn-add:hover { background-color: #0b5ed7; }
</style>
</head>
<body class="p-4">
<div class="container">
  <div class="card p-4">
    <div class="text-center mb-4">
      <i class="bi bi-diagram-3 fs-1 text-success"></i>
      <h2>Gestation Roadmap</h2>
      <p class="text-muted mb-0">Feed & Health Automation for Sow</p>
    </div>

    <?php if ($sow): ?>
      <div class="alert alert-secondary py-2">
        <strong>🐷 Ear Tag:</strong> <?= htmlspecialchars($sow['ear_tag_no']) ?> |
        <strong>Breed:</strong> <?= htmlspecialchars($sow['breed_line']) ?> |
        <strong>Status:</strong> <?= htmlspecialchars($sow['status']) ?>
      </div>
    <?php endif; ?>

    <!-- 🧩 FEED ROADMAP -->
    <div class="section-title mt-4"><i class="bi bi-basket-fill"></i> Feed Roadmap</div>
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="text-center">
          <tr>
            <th>Stage</th>
            <th>Duration</th>
            <th>Feed Type</th>
            <th>Daily Feed (kg)</th>
            <th>Purpose</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <?php if ($feedRoadmap): ?>
            <?php foreach ($feedRoadmap as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['stage_name']) ?></td>
                <td><?= htmlspecialchars($row['duration_days']) ?></td>
                <td><?= htmlspecialchars($row['feed_type']) ?></td>
                <td><?= htmlspecialchars($row['daily_feed']) ?></td>
                <td><?= htmlspecialchars($row['purpose']) ?></td>
                <td>
                  <span class="badge bg-secondary"><?= htmlspecialchars($row['status']) ?></span>
                </td>
                <td>
                  <a href="../feed/add_feed.php?sow_id=<?= $sow_id ?>" class="btn btn-sm btn-add">
                    <i class="bi bi-plus-circle"></i> Add Feed Record
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" class="text-muted">No feed roadmap found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- 💉 HEALTH ROADMAP -->
    <div class="section-title mt-5"><i class="bi bi-heart-pulse-fill"></i> Health Roadmap</div>
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="text-center">
          <tr>
            <th>Stage</th>
            <th>Days Range</th>
            <th>Treatment / Action</th>
            <th>Purpose</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <?php if ($healthRoadmap): ?>
            <?php foreach ($healthRoadmap as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['stage_name']) ?></td>
                <td><?= htmlspecialchars($row['days_range']) ?></td>
                <td><?= htmlspecialchars($row['treatment_action']) ?></td>
                <td><?= htmlspecialchars($row['purpose']) ?></td>
                <td>
                  <span class="badge bg-secondary"><?= htmlspecialchars($row['status']) ?></span>
                </td>
                <td>
                  <a href="../health/add_health.php?sow_id=<?= $sow_id ?>" class="btn btn-sm btn-add">
                    <i class="bi bi-plus-circle"></i> Add Health Record
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-muted">No health roadmap found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- 🔙 Back -->
    <div class="d-flex justify-content-end mt-3">
      <a href="../profile/view_sow.php?id=<?= $sow_id ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left-circle"></i> Back to Profile
      </a>
    </div>

  </div>
</div>
</body>
</html>
