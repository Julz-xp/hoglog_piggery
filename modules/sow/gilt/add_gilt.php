<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch all sows for dropdown
$sows = $pdo->query("SELECT sow_id, ear_tag_no FROM sows ORDER BY ear_tag_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sow_id = $_POST['sow_id'];
    $heat_detection_date = $_POST['heat_detection_date'];
    $breeding_date = $_POST['breeding_date'];
    $service_type = $_POST['service_type'];
    $semen_source = $_POST['semen_source'];
    $technician_handler = $_POST['technician_handler'];
    $heat_observation_notes = $_POST['heat_observation_notes'];
    $pregnancy_check_date = $_POST['pregnancy_check_date'];
    $pregnancy_result = $_POST['pregnancy_result'];
    $rebreeding_date = $_POST['rebreeding_date'];
    $mother_parify_no = $_POST['mother_parify_no'];
    $remarks = $_POST['remarks'];

    $stmt = $pdo->prepare("
        INSERT INTO sow_gilt_stage 
        (sow_id, heat_detection_date, breeding_date, service_type, semen_source, technician_handler, heat_observation_notes,
        pregnancy_check_date, pregnancy_result, rebreeding_date, mother_parify_no, remarks)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([$sow_id, $heat_detection_date, $breeding_date, $service_type, $semen_source, $technician_handler,
        $heat_observation_notes, $pregnancy_check_date, $pregnancy_result, $rebreeding_date, $mother_parify_no, $remarks]);

    header("Location: list_gilt.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Gilt Stage Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">➕ Add Gilt Stage Record</h2>
  <form method="POST">
    <div class="mb-3">
      <label>Sow (Ear Tag):</label>
      <select name="sow_id" class="form-select" required>
        <option value="">-- Select Sow --</option>
        <?php foreach ($sows as $sow): ?>
          <option value="<?= $sow['sow_id'] ?>"><?= htmlspecialchars($sow['ear_tag_no']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3"><label>Heat Detection Date:</label><input type="date" name="heat_detection_date" class="form-control"></div>
    <div class="mb-3"><label>Breeding / AI Attempt Date:</label><input type="date" name="breeding_date" class="form-control"></div>
    <div class="mb-3">
      <label>Service Type:</label>
      <select name="service_type" class="form-select">
        <option value="AI">AI</option>
        <option value="Natural">Natural</option>
      </select>
    </div>
    <div class="mb-3"><label>Boar / Semen Source (ID, Batch No.):</label><input type="text" name="semen_source" class="form-control"></div>
    <div class="mb-3"><label>Technician / Handler:</label><input type="text" name="technician_handler" class="form-control"></div>
    <div class="mb-3"><label>Heat Observation Notes:</label><textarea name="heat_observation_notes" class="form-control"></textarea></div>
    <div class="mb-3"><label>Pregnancy Check Date:</label><input type="date" name="pregnancy_check_date" class="form-control"></div>
    <div class="mb-3"><label>Pregnancy Result:</label>
      <select name="pregnancy_result" class="form-select">
        <option value="Pending">Pending</option>
        <option value="Positive">Positive</option>
        <option value="Negative">Negative</option>
      </select>
    </div>
    <div class="mb-3"><label>Rebreeding Date:</label><input type="date" name="rebreeding_date" class="form-control"></div>
    <div class="mb-3"><label>Mother’s Parity No.:</label><input type="number" name="mother_parify_no" class="form-control"></div>
    <div class="mb-3"><label>Remarks:</label><textarea name="remarks" class="form-control"></textarea></div>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="list_gilt.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
