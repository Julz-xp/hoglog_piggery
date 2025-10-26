<?php
require_once __DIR__ . '/../../../config/db.php';

$sow_id = $_GET['sow_id'] ?? null;
if (!$sow_id) die("❌ Missing sow ID.");

// 🐖 Fetch sow info
$stmt = $pdo->prepare("SELECT sow_id, ear_tag_no, breed_line, status FROM sows WHERE sow_id = ?");
$stmt->execute([$sow_id]);
$sow = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$sow) die("❌ Sow not found.");

// 📅 Determine gestation start (AI Date)
$getAI = $pdo->prepare("SELECT ai_date FROM ai_attempt WHERE sow_id = ? AND confirmation='Positive' ORDER BY ai_date DESC LIMIT 1");
$getAI->execute([$sow_id]);
$ai = $getAI->fetch(PDO::FETCH_ASSOC);
$ai_date = $ai['ai_date'] ?? null;
$days_pregnant = $ai_date ? (new DateTime())->diff(new DateTime($ai_date))->days : 0;

// 📋 Fetch roadmaps
$feedStmt = $pdo->prepare("SELECT * FROM gestation_feed_roadmap WHERE sow_id = ? ORDER BY id ASC");
$feedStmt->execute([$sow_id]);
$feedRoadmap = $feedStmt->fetchAll(PDO::FETCH_ASSOC);

$healthStmt = $pdo->prepare("SELECT * FROM gestation_health_roadmap WHERE sow_id = ? ORDER BY id ASC");
$healthStmt->execute([$sow_id]);
$healthRoadmap = $healthStmt->fetchAll(PDO::FETCH_ASSOC);

function fmt($v) { return htmlspecialchars($v ?? ''); }

function badgeClass($status) {
  return match($status) {
    'current' => 'badge bg-success',
    'completed' => 'badge bg-secondary',
    'upcoming' => 'badge bg-warning text-dark',
    default => 'badge bg-light text-dark'
  };
}

// 🧠 Determine current stage based on days pregnant
function getStageStatus($range, $days) {
  if (preg_match('/(\d+)[^\d]+(\d+)/', $range, $m)) {
    $min = (int)$m[1];
    $max = (int)$m[2];
    if ($days < $min) return 'upcoming';
    elseif ($days >= $min && $days <= $max) return 'current';
    else return 'completed';
  }
  return 'upcoming';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Gestation Roadmap – <?= fmt($sow['ear_tag_no']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background-color: #f8f9fa; }
.card { border: none; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
.table th, .table td { vertical-align: middle; }
.card-header { font-weight: 600; letter-spacing: 0.3px; }
.badge { font-size: 0.85rem; }
</style>
</head>
<body class="p-4">

<div class="container">
  <div class="card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
      <div>
        <h2 class="mb-1"><i class="bi bi-egg-fried text-warning"></i> Gestation Roadmap</h2>
        <p class="text-muted mb-0">
          <strong>Ear Tag:</strong> <?= fmt($sow['ear_tag_no']) ?> |
          <strong>Breed:</strong> <?= fmt($sow['breed_line']) ?> |
          <strong>Status:</strong> <?= fmt($sow['status']) ?> |
          <strong>Days Pregnant:</strong> <?= $days_pregnant ?> days
        </p>
      </div>
      <a href="../profile/view_sow.php?id=<?= $sow_id ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left-circle"></i> Back to Profile
      </a>
    </div>
  </div>

  <!-- 🐖 FEED ROADMAP -->
  <div class="card mb-4 border-0 shadow-sm">
    <div class="card-header text-white" style="background-color:#198754;">
      <h5 class="mb-0"><i class="bi bi-basket-fill me-2"></i>Feed Roadmap</h5>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
          <thead style="background-color:#e9f7ef;">
            <tr class="text-center">
              <th style="width:20%;">Stage</th>
              <th style="width:15%;">Age Range (days)</th>
              <th style="width:20%;">Feed Type</th>
              <th style="width:10%;">Daily Feed (kg)</th>
              <th style="width:25%;">Purpose</th>
              <th style="width:10%;">Status</th>
              <th style="width:5%;">Action</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <?php foreach ($feedRoadmap as $row): 
              $stageStatus = getStageStatus($row['duration_days'], $days_pregnant);
            ?>
            <tr>
              <td><strong><?= fmt($row['stage_name']) ?></strong></td>
              <td><?= fmt($row['duration_days']) ?></td>
              <td><?= fmt($row['feed_type']) ?></td>
              <td><?= fmt($row['daily_feed']) ?></td>
              <td class="text-start"><?= fmt($row['purpose']) ?></td>
              <td><span class="<?= badgeClass($stageStatus) ?>"><?= ucfirst($stageStatus) ?></span></td>
              <td>
                <a href="../feed/add_feed.php?sow_id=<?= $sow_id ?>&stage=<?= urlencode($row['stage_name']) ?>"
                   class="btn btn-sm btn-outline-success" title="Add Feed Record">
                  <i class="bi bi-plus-circle"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- 💉 HEALTH ROADMAP -->
  <div class="card mb-4 border-0 shadow-sm">
    <div class="card-header text-white" style="background-color:#0dcaf0;">
      <h5 class="mb-0"><i class="bi bi-heart-pulse-fill me-2"></i>Health Roadmap</h5>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
          <thead style="background-color:#e3f8ff;">
            <tr class="text-center">
              <th style="width:20%;">Stage</th>
              <th style="width:15%;">Age Range (days)</th>
              <th style="width:25%;">Treatment / Action</th>
              <th style="width:25%;">Purpose</th>
              <th style="width:10%;">Status</th>
              <th style="width:5%;">Action</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <?php foreach ($healthRoadmap as $row): 
              $range = $row['day_range'] ?? ($row['duration_days'] ?? '');
              $stageStatus = getStageStatus($range, $days_pregnant);
            ?>
            <tr>
              <td><strong><?= fmt($row['stage_name']) ?></strong></td>
              <td><?= fmt($range) ?></td>
              <td class="text-start"><?= fmt($row['treatment_action']) ?></td>
              <td class="text-start"><?= fmt($row['purpose']) ?></td>
              <td><span class="<?= badgeClass($stageStatus) ?>"><?= ucfirst($stageStatus) ?></span></td>
              <td>
                <a href="../health/add_health.php?sow_id=<?= $sow_id ?>&stage=<?= urlencode($row['stage_name']) ?>"
                   class="btn btn-sm btn-outline-info" title="Add Health Record">
                  <i class="bi bi-plus-circle"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
</body>
</html>
