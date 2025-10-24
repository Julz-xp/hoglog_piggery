<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch sow list
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

    $stmt = $pdo->prepare("
        INSERT INTO sow_health_record 
        (sow_id, record_type, record_date, stage, description, findings, treatment, product_description, farm_vet, cost, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$sow_id, $record_type, $record_date, $stage, $description, $findings, $treatment, $product_description, $farm_vet, $cost, $remarks]);

    header("Location: list_health.php");
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
<div class="container">
  <h2 class="mb-4">➕ Add Health & Treatment Record</h2>

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

    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Record Type</label>
        <select name="record_type" class="form-select" required>
          <option value="">-- Select Type --</option>
          <option value="Disease">Disease</option>
          <option value="Vaccination">Vaccination</option>
          <option value="Deworming">Deworming</option>
          <option value="Vitamins">Vitamins</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Record Date</label>
        <input type="date" name="record_date" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Stage</label>
        <select name="stage" class="form-select" required>
          <option value="">-- Select Stage --</option>
          <option value="Gilt">Gilt</option>
          <option value="Gestation">Gestation</option>
          <option value="Lactation">Lactation</option>
          <option value="Dry">Dry</option>
        </select>
      </div>
    </div>

    <hr>
    <div class="mb-3"><label>Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
    <div class="mb-3"><label>Findings / Observation</label><textarea name="findings" class="form-control" rows="2"></textarea></div>
    <div class="mb-3"><label>Treatment / Procedure</label><textarea name="treatment" class="form-control" rows="2"></textarea></div>
    <div class="mb-3"><label>Product Description (Medicine / Vaccine)</label><input type="text" name="product_description" class="form-control"></div>
    <div class="mb-3"><label>Farm Vet / Handler</label><input type="text" name="farm_vet" class="form-control"></div>
    <div class="mb-3"><label>Cost (₱)</label><input type="number" step="0.01" name="cost" class="form-control"></div>
    <div class="mb-3"><label>Remarks</label><textarea name="remarks" class="form-control" rows="2"></textarea></div>

    <button type="submit" class="btn btn-success">Save Record</button>
    <a href="list_health.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
