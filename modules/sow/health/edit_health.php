<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

// Fetch the selected record
$stmt = $pdo->prepare("SELECT * FROM sow_health_record WHERE health_id = ?");
$stmt->execute([$id]);
$record = $stmt->fetch();
if (!$record) die("Record not found!");

// Fetch sow list for dropdown
$sows = $pdo->query("SELECT sow_id, ear_tag_no FROM sows ORDER BY ear_tag_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sow_id = $_POST['sow_id'];
    $record_type = $_POST['record_type'];
    $record_date = $_POST['record_date'];
    $stage = $_POST['stage'];
    $description = $_POST['description'];
    $findings = $_POST['findings'];
    $treatment = $_POST['treatment'];
    $product_description = $_POST['product_description'];
    $farm_vet = $_POST['farm_vet'];
    $cost = $_POST['cost'] ?: null;
    $remarks = $_POST['remarks'];

    $update = $pdo->prepare("
        UPDATE sow_health_record 
        SET sow_id=?, record_type=?, record_date=?, stage=?, description=?, findings=?, treatment=?, product_description=?, farm_vet=?, cost=?, remarks=? 
        WHERE health_id=?
    ");
    $update->execute([$sow_id, $record_type, $record_date, $stage, $description, $findings, $treatment, $product_description, $farm_vet, $cost, $remarks, $id]);

    header("Location: list_health.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Health Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">✏️ Edit Health & Treatment Record</h2>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Sow (Ear Tag)</label>
      <select name="sow_id" class="form-select" required>
        <?php foreach ($sows as $sow): ?>
          <option value="<?= $sow['sow_id'] ?>" <?= ($sow['sow_id']==$record['sow_id'])?'selected':'' ?>>
            <?= htmlspecialchars($sow['ear_tag_no']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="row g-3">
      <div class="col-md-4">
        <label>Record Type</label>
        <select name="record_type" class="form-select" required>
          <?php 
            $types = ['Disease', 'Vaccination', 'Deworming', 'Vitamins'];
            foreach ($types as $type): 
          ?>
            <option value="<?= $type ?>" <?= ($type == $record['record_type'])?'selected':'' ?>><?= $type ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label>Record Date</label>
        <input type="date" name="record_date" value="<?= $record['record_date'] ?>" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label>Stage</label>
        <select name="stage" class="form-select" required>
          <?php 
            $stages = ['Gilt', 'Gestation', 'Lactation', 'Dry'];
            foreach ($stages as $st): 
          ?>
            <option value="<?= $st ?>" <?= ($st == $record['stage'])?'selected':'' ?>><?= $st ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <hr>
    <div class="mb-3"><label>Description</label><textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($record['description']) ?></textarea></div>
    <div class="mb-3"><label>Findings / Observation</label><textarea name="findings" class="form-control" rows="2"><?= htmlspecialchars($record['findings']) ?></textarea></div>
    <div class="mb-3"><label>Treatment / Procedure</label><textarea name="treatment" class="form-control" rows="2"><?= htmlspecialchars($record['treatment']) ?></textarea></div>
    <div class="mb-3"><label>Product Description (Medicine / Vaccine)</label><input type="text" name="product_description" value="<?= htmlspecialchars($record['product_description']) ?>" class="form-control"></div>
    <div class="mb-3"><label>Farm Vet / Handler</label><input type="text" name="farm_vet" value="<?= htmlspecialchars($record['farm_vet']) ?>" class="form-control"></div>
    <div class="mb-3"><label>Cost (₱)</label><input type="number" step="0.01" name="cost" value="<?= htmlspecialchars($record['cost']) ?>" class="form-control"></div>
    <div class="mb-3"><label>Remarks</label><textarea name="remarks" class="form-control" rows="2"><?= htmlspecialchars($record['remarks']) ?></textarea></div>

    <button type="submit" class="btn btn-warning">Update</button>
    <a href="list_health.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
