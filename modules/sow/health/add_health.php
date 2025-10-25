<?php
require_once __DIR__ . '/../../../config/db.php';

$sow_id = $_GET['sow_id'] ?? null;
$stage = $_GET['stage'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sow_id = $_POST['sow_id'];
    $stage = $_POST['stage'];
    $record_type = $_POST['record_type'];
    $record_date = $_POST['record_date'];
    $description = $_POST['description'];
    $treatment = $_POST['treatment'];
    $farm_vet = $_POST['farm_vet'];
    $cost = $_POST['cost'];
    $remarks = $_POST['remarks'];

    $stmt = $pdo->prepare("INSERT INTO sow_health_record 
        (sow_id, record_type, record_date, stage, description, treatment, farm_vet, cost, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$sow_id, $record_type, $record_date, $stage, $description, $treatment, $farm_vet, $cost, $remarks]);

    // Optionally mark roadmap stage as completed
    $pdo->prepare("UPDATE gilt_health_roadmap SET status='completed' WHERE sow_id=? AND stage_name=?")->execute([$sow_id, $stage]);

    header("Location: ../gilt/view_roadmap.php?sow_id=" . $sow_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Health Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container" style="max-width:600px;">
  <h3 class="mb-4 text-info">💉 Add Health Record for Stage: <?= htmlspecialchars($stage) ?></h3>
  <form method="POST">
    <input type="hidden" name="sow_id" value="<?= htmlspecialchars($sow_id) ?>">
    <input type="hidden" name="stage" value="<?= htmlspecialchars($stage) ?>">

    <div class="mb-3">
      <label class="form-label">Record Type</label>
      <select name="record_type" class="form-select" required>
        <option value="Vaccination">Vaccination</option>
        <option value="Disease">Disease</option>
        <option value="Deworming">Deworming</option>
        <option value="Vitamin">Vitamin</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Record Date</label>
      <input type="date" name="record_date" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="3"></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Treatment Given</label>
      <input type="text" name="treatment" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">Farm Vet / Technician</label>
      <input type="text" name="farm_vet" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">Cost</label>
      <input type="number" step="0.01" name="cost" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">Remarks</label>
      <textarea name="remarks" class="form-control" rows="3"></textarea>
    </div>

    <div class="mt-4 d-flex justify-content-end gap-2">
      <button type="submit" class="btn btn-info"><i class="bi bi-save2"></i> Save Record</button>
      <a href="../gilt/view_roadmap.php?sow_id=<?= $sow_id ?>" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
</body>
</html>
