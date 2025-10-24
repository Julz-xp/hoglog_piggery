<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch sows for dropdown
$sows = $pdo->query("SELECT sow_id, ear_tag_no FROM sows ORDER BY ear_tag_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sow_id             = $_POST['sow_id'];
    $culling_date       = $_POST['culling_date'];
    $reason_for_culling = $_POST['reason_for_culling'];
    $last_parity_summary= $_POST['last_parity_summary'];
    $final_weight       = $_POST['final_weight'] ?: null;
    $sale_price         = $_POST['sale_price'] ?: null;
    $health_condition   = $_POST['health_condition'];
    $exit_type          = $_POST['exit_type'];
    $disposal_notes     = $_POST['disposal_notes'];

    $stmt = $pdo->prepare("
        INSERT INTO sow_exit_record 
        (sow_id, culling_date, reason_for_culling, last_parity_summary, final_weight, sale_price, health_condition, exit_type, disposal_notes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$sow_id, $culling_date, $reason_for_culling, $last_parity_summary, $final_weight, $sale_price, $health_condition, $exit_type, $disposal_notes]);

    header("Location: list_exit.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Exit Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">➕ Add Exit Record</h2>
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

    <div class="mb-3"><label>Culling Date</label><input type="date" name="culling_date" class="form-control" required></div>
    <div class="mb-3"><label>Reason for Culling</label><input type="text" name="reason_for_culling" class="form-control" required></div>
    <div class="mb-3"><label>Last Parity Summary</label><textarea name="last_parity_summary" class="form-control" rows="2"></textarea></div>

    <div class="row g-3">
      <div class="col-md-4"><label>Final Weight (kg)</label><input type="number" step="0.01" name="final_weight" class="form-control"></div>
      <div class="col-md-4"><label>Sale Price (₱)</label><input type="number" step="0.01" name="sale_price" class="form-control"></div>
      <div class="col-md-4">
        <label>Exit Type</label>
        <select name="exit_type" class="form-select" required>
          <option value="">-- Select Type --</option>
          <option value="Culled">Culled</option>
          <option value="Sold">Sold</option>
          <option value="Died">Died</option>
        </select>
      </div>
    </div>

    <div class="mt-3 mb-3"><label>Final Health Condition</label><input type="text" name="health_condition" class="form-control"></div>
    <div class="mb-3"><label>Disposal Notes</label><textarea name="disposal_notes" class="form-control" rows="3"></textarea></div>

    <button type="submit" class="btn btn-success">Save</button>
    <a href="list_exit.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
