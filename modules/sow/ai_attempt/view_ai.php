<?php
require_once __DIR__ . '/../../../config/db.php';

// 🐷 Get sow_id from URL
$sow_id = $_GET['sow_id'] ?? null;
if (!$sow_id) {
  die("❌ Missing sow_id.");
}

// 🧾 Fetch sow info
$stmt = $pdo->prepare("SELECT sow_id, ear_tag_no, breed_line, status FROM sows WHERE sow_id = ?");
$stmt->execute([$sow_id]);
$sow = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$sow) die("❌ Sow not found.");

// 📋 Fetch all AI attempts for this sow
$q = $pdo->prepare("SELECT * FROM ai_attempt WHERE sow_id = ? ORDER BY ai_date DESC, ai_id DESC");
$q->execute([$sow_id]);
$attempts = $q->fetchAll(PDO::FETCH_ASSOC);

function badgeClass($status) {
  switch ($status) {
    case 'Positive': return 'bg-success';
    case 'Negative': return 'bg-danger';
    default: return 'bg-secondary';
  }
}

function fmt($v) {
  return htmlspecialchars($v ?? '');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>A.I. Records – <?= fmt($sow['ear_tag_no']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background-color: #f8f9fa; }
.card { border: none; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
h2 { font-weight: 600; color: #2f3640; }
.table th, .table td { vertical-align: middle; }
.badge { font-size: 0.9rem; }
.btn-confirm {
  border-color: #28a745;
  color: #28a745;
}
.btn-confirm:hover {
  background-color: #28a745;
  color: white;
}
</style>
</head>
<body class="p-4">
<div class="container">
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <div>
        <h2 class="mb-1">A.I. Attempt Records</h2>
        <div class="text-muted">
          <strong>Ear Tag:</strong> <?= fmt($sow['ear_tag_no']) ?> |
          <strong>Breed:</strong> <?= fmt($sow['breed_line']) ?> |
          <strong>Status:</strong> <?= fmt($sow['status']) ?>
        </div>
      </div>
      <div class="d-flex gap-2">
        <a href="../profile/view_sow.php?id=<?= $sow_id ?>" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Back to Profile
        </a>
        <a href="add_ai.php?sow_id=<?= $sow_id ?>" class="btn btn-primary">
          <i class="bi bi-droplet-fill"></i> Add A.I. Attempt
        </a>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark text-center">
          <tr>
            <th>#</th>
            <th>AI Date</th>
            <th>Heat Detection</th>
            <th>Preg Check</th>
            <th>Confirmation</th>
            <th>Breeding Type</th>
            <th>Boar Source</th>
            <th>Vet/Tech</th>
            <th>Expected Farrowing</th>
            <th>Cost (₱)</th>
            <th>Remarks</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <?php if ($attempts): ?>
            <?php foreach ($attempts as $a): 
              $expected = $a['ai_date'] ? date('Y-m-d', strtotime($a['ai_date'].' +114 days')) : '';
            ?>
            <tr>
              <td><?= (int)$a['ai_id'] ?></td>
              <td><?= fmt($a['ai_date']) ?></td>
              <td><?= fmt($a['heat_detection_date']) ?></td>
              <td><?= fmt($a['pregnancy_check_date']) ?></td>
              <td>
                <span class="badge <?= badgeClass($a['confirmation']) ?>">
                  <?= fmt($a['confirmation']) ?>
                </span>
              </td>
              <td><?= fmt($a['breeding_type']) ?></td>
              <td><?= fmt($a['boar_source']) ?></td>
              <td><?= fmt($a['farm_vet']) ?></td>
              <td><?= fmt($expected) ?></td>
              <td><?= $a['cost'] ? number_format($a['cost'], 2) : '' ?></td>
              <td class="text-start"><?= fmt($a['remarks']) ?></td>
              <td class="text-center">
                <!-- ✏️ Edit Button -->
                <a href="edit_ai.php?ai_id=<?= $a['ai_id'] ?>&sow_id=<?= $sow_id ?>" 
                   class="btn btn-sm btn-outline-warning"
                   title="Edit AI Record">
                  <i class="bi bi-pencil-square"></i>
                </a>

                <!-- ✅ Confirm Pregnancy Button (only if Pending) -->
                <?php if ($a['confirmation'] === 'Pending'): ?>
                <a href="edit_ai.php?ai_id=<?= $a['ai_id'] ?>&sow_id=<?= $sow_id ?>" 
                   class="btn btn-sm btn-confirm"
                   title="Confirm Pregnancy">
                  <i class="bi bi-check-circle-fill"></i>
                </a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="12" class="text-muted">No A.I. records found for this sow.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
