<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch sows for dropdown
$sows = $pdo->query("SELECT sow_id, ear_tag_no FROM sows ORDER BY ear_tag_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sow_id = $_POST['sow_id'];
    $weaning_date = $_POST['weaning_date'];
    $heat_detection_date = $_POST['heat_detection_date'];
    $weaning_estrus_interval = $_POST['weaning_estrus_interval'] ?: null;

    $stmt = $pdo->prepare("
        INSERT INTO sow_dry_stage (sow_id, weaning_date, heat_detection_date, weaning_estrus_interval)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$sow_id, $weaning_date, $heat_detection_date, $weaning_estrus_interval]);

    header("Location: list_dry.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Dry Sow Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script>
function calcInterval() {
  const wean = new Date(document.querySelector('[name=weaning_date]').value);
  const heat = new Date(document.querySelector('[name=heat_detection_date]').value);
  if (wean && heat && heat > wean) {
    const diff = Math.ceil((heat - wean) / (1000 * 60 * 60 * 24));
    document.querySelector('[name=weaning_estrus_interval]').value = diff;
  }
}
</script>
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">➕ Add Dry Sow Record</h2>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Sow (Ear Tag)</label>
      <select name="sow_id" class="form-select" required>
        <option value="">-- Select Sow --</option>
        <?php foreach ($sows as $sow): ?>
          <option value="<?= $sow['sow_id'] ?>"><?= htmlspecialchars($sow['ear_tag_no']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Weaning Date</label>
      <input type="date" name="weaning_date" class="form-control" required onchange="calcInterval()">
    </div>

    <div class="mb-3">
      <label class="form-label">Heat Detection Date</label>
      <input type="date" name="heat_detection_date" class="form-control" onchange="calcInterval()">
    </div>

    <div class="mb-3">
      <label class="form-label">Weaning → Estrus Interval (days)</label>
      <input type="number" name="weaning_estrus_interval" class="form-control" readonly>
    </div>

    <button type="submit" class="btn btn-success">Save</button>
    <a href="list_dry.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
