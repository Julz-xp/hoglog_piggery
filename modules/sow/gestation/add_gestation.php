<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch Sows and A.I. attempts
$sows = $pdo->query("SELECT sow_id, ear_tag_no FROM sows ORDER BY ear_tag_no ASC")->fetchAll();
$ai_attempts = $pdo->query("SELECT ai_id, ai_date FROM sow_ai_attempts WHERE confirmation='Positive' ORDER BY ai_id DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sow_id = $_POST['sow_id'];
    $ai_id = $_POST['ai_id'];
    $breeding_date = $_POST['breeding_date'];
    $expected_farrowing_date = $_POST['expected_farrowing_date'];
    $boar_source = $_POST['boar_source'];
    $body_condition_score = $_POST['body_condition_score'];
    $notes = $_POST['notes'];

    $stmt = $pdo->prepare("
        INSERT INTO sow_gestation (sow_id, ai_id, breeding_date, expected_farrowing_date, boar_source, body_condition_score, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$sow_id, $ai_id, $breeding_date, $expected_farrowing_date, $boar_source, $body_condition_score, $notes]);

    header("Location: list_gestation.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Gestation Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">➕ Add Gestation Record</h2>
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
    <div class="mb-3">
      <label>A.I. Attempt (if applicable):</label>
      <select name="ai_id" class="form-select">
        <option value="">-- Select A.I. Attempt --</option>
        <?php foreach ($ai_attempts as $ai): ?>
          <option value="<?= $ai['ai_id'] ?>">#<?= $ai['ai_id'] ?> — <?= $ai['ai_date'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3"><label>Breeding Date:</label><input type="date" name="breeding_date" class="form-control"></div>
    <div class="mb-3"><label>Expected Farrowing Date:</label><input type="date" name="expected_farrowing_date" class="form-control"></div>
    <div class="mb-3"><label>Boar Source:</label><input type="text" name="boar_source" class="form-control"></div>
    <div class="mb-3"><label>Body Condition Score (1–5):</label><input type="text" name="body_condition_score" class="form-control"></div>
    <div class="mb-3"><label>Notes:</label><textarea name="notes" class="form-control"></textarea></div>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="list_gestation.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
