<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

// Fetch record
$stmt = $pdo->prepare("SELECT * FROM sow_gilt_stage WHERE gilt_id = ?");
$stmt->execute([$id]);
$gilt = $stmt->fetch();

if (!$gilt) die("Record not found!");

// Fetch sows for dropdown
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

    $update = $pdo->prepare("
        UPDATE sow_gilt_stage 
        SET sow_id=?, heat_detection_date=?, breeding_date=?, service_type=?, semen_source=?, technician_handler=?, 
            heat_observation_notes=?, pregnancy_check_date=?, pregnancy_result=?, rebreeding_date=?, mother_parify_no=?, remarks=?
        WHERE gilt_id=?
    ");
    $update->execute([$sow_id, $heat_detection_date, $breeding_date, $service_type, $semen_source, $technician_handler,
        $heat_observation_notes, $pregnancy_check_date, $pregnancy_result, $rebreeding_date, $mother_parify_no, $remarks, $id]);

    header("Location: list_gilt.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Gilt Stage</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">✏️ Edit Gilt Record</h2>
  <form method="POST">
    <div class="mb-3">
      <label>Sow (Ear Tag):</label>
      <select name="sow_id" class="form-select">
        <?php foreach ($sows as $sow): ?>
          <option value="<?= $sow['sow_id'] ?>" <?= ($sow['sow_id'] == $gilt['sow_id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($sow['ear_tag_no']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3"><label>Heat Detection Date:</label><input type="date" name="heat_detection_date" value="<?= $gilt['heat_detection_date'] ?>" class="form-control"></div>
    <div class="mb-3"><label>Breeding Date:</label><input type="date" name="breeding_date" value="<?= $gilt['breeding_date'] ?>" class="form-control"></div>
    <div class="mb-3"><label>Service Type:</label>
      <select name="service_type" class="form-select">
        <option value="AI" <?= ($gilt['service_type']=='AI')?'selected':'' ?>>AI</option>
        <option value="Natural" <?= ($gilt['service_type']=='Natural')?'selected':'' ?>>Natural</option>
      </select>
    </div>
    <div class="mb-3"><label>Semen Source:</label><input type="text" name="semen_source" value="<?= $gilt['semen_source'] ?>" class="form-control"></div>
    <div class="mb-3"><label>Technician / Handler:</label><input type="text" name="technician_handler" value="<?= $gilt['technician_handler'] ?>" class="form-control"></div>
    <div class="mb-3"><label>Heat Observation Notes:</label><textarea name="heat_observation_notes" class="form-control"><?= $gilt['heat_observation_notes'] ?></textarea></div>
    <div class="mb-3"><label>Pregnancy Check Date:</label><input type="date" name="pregnancy_check_date" value="<?= $gilt['pregnancy_check_date'] ?>" class="form-control"></div>
    <div class="mb-3"><label>Pregnancy Result:</label>
      <select name="pregnancy_result" class="form-select">
        <option value="Pending" <?= ($gilt['pregnancy_result']=='Pending')?'selected':'' ?>>Pending</option>
        <option value="Positive" <?= ($gilt['pregnancy_result']=='Positive')?'selected':'' ?>>Positive</option>
        <option value="Negative" <?= ($gilt['pregnancy_result']=='Negative')?'selected':'' ?>>Negative</option>
      </select>
    </div>
    <div class="mb-3"><label>Rebreeding Date:</label><input type="date" name="rebreeding_date" value="<?= $gilt['rebreeding_date'] ?>" class="form-control"></div>
    <div class="mb-3"><label>Mother’s Parity No.:</label><input type="number" name="mother_parify_no" value="<?= $gilt['mother_parify_no'] ?>" class="form-control"></div>
    <div class="mb-3"><label>Remarks:</label><textarea name="remarks" class="form-control"><?= $gilt['remarks'] ?></textarea></div>
    <button type="submit" class="btn btn-warning">Update</button>
    <a href="list_gilt.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
