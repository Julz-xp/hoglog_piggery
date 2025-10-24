<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch sow list
$sows = $pdo->query("SELECT sow_id, ear_tag_no FROM sows ORDER BY ear_tag_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sow_id = $_POST['sow_id'];
    $heat_detection_date = $_POST['heat_detection_date'];
    $ai_date = $_POST['ai_date'];
    $breeding_type = $_POST['breeding_type'];
    $boar_source = $_POST['boar_source'];
    $farm_vet = $_POST['farm_vet'];
    $pregnancy_check_date = $_POST['pregnancy_check_date'];
    $confirmation = $_POST['confirmation'];
    $cost = $_POST['cost'];
    $estrus_notes = $_POST['estrus_notes'];

    $stmt = $pdo->prepare("
        INSERT INTO sow_ai_attempts
        (sow_id, heat_detection_date, ai_date, breeding_type, boar_source, farm_vet, pregnancy_check_date, confirmation, cost, estrus_notes)
        VALUES (?,?,?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([$sow_id, $heat_detection_date, $ai_date, $breeding_type, $boar_source, $farm_vet, $pregnancy_check_date, $confirmation, $cost, $estrus_notes]);

    // ✅ If pregnancy is confirmed, auto insert gestation record
    if ($confirmation === 'Positive') {
        $ai_id = $pdo->lastInsertId();
        $expected_farrowing = date('Y-m-d', strtotime($ai_date . ' +114 days'));

        $insertGest = $pdo->prepare("
            INSERT INTO sow_gestation (sow_id, ai_id, breeding_date, boar_source, expected_farrowing_date, notes)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $insertGest->execute([$sow_id, $ai_id, $ai_date, $boar_source, $expected_farrowing, 'Auto-generated after confirmed pregnancy.']);
    }

    header("Location: list_ai.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add A.I. Attempt</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">➕ Add A.I. Attempt Record</h2>
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
    <div class="mb-3"><label>A.I. Date:</label><input type="date" name="ai_date" class="form-control"></div>
    <div class="mb-3"><label>Breeding Type:</label><input type="text" name="breeding_type" class="form-control" placeholder="e.g., AI, Double AI, Natural"></div>
    <div class="mb-3"><label>Boar Source:</label><input type="text" name="boar_source" class="form-control"></div>
    <div class="mb-3"><label>Farm Vet / Technician:</label><input type="text" name="farm_vet" class="form-control"></div>
    <div class="mb-3"><label>Pregnancy Check Date:</label><input type="date" name="pregnancy_check_date" class="form-control"></div>
    <div class="mb-3"><label>Confirmation:</label>
      <select name="confirmation" class="form-select">
        <option value="Pending">Pending</option>
        <option value="Positive">Positive</option>
        <option value="Negative">Negative</option>
      </select>
    </div>
    <div class="mb-3"><label>Cost (₱):</label><input type="number" step="0.01" name="cost" class="form-control"></div>
    <div class="mb-3"><label>Estrus Notes:</label><textarea name="estrus_notes" class="form-control"></textarea></div>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="list_ai.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
