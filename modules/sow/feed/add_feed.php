<?php
require_once __DIR__ . '/../../../config/db.php';

$sow_id = $_GET['sow_id'] ?? null;
$stage = $_GET['stage'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sow_id = $_POST['sow_id'];
    $stage = $_POST['stage'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $type_of_feed = $_POST['type_of_feed'];
    $daily_intake = $_POST['daily_intake'];
    $total_days = $_POST['total_days'];
    $price_per_kg = $_POST['price_per_kg'];

    $total_feed_consumed = $daily_intake * $total_days;
    $total_feed_cost = $total_feed_consumed * $price_per_kg;

    $stmt = $pdo->prepare("INSERT INTO sow_feed_consumption 
        (sow_id, stage, start_date, end_date, type_of_feed, daily_intake, total_days, total_feed_consumed, price_per_kg, total_feed_cost)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$sow_id, $stage, $start_date, $end_date, $type_of_feed, $daily_intake, $total_days, $total_feed_consumed, $price_per_kg, $total_feed_cost]);

    // Optionally mark roadmap stage as completed:
    $pdo->prepare("UPDATE gilt_feed_roadmap SET status='completed' WHERE sow_id=? AND stage_name=?")->execute([$sow_id, $stage]);

    header("Location: ../gilt/view_roadmap.php?sow_id=" . $sow_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Feed Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container" style="max-width:600px;">
  <h3 class="mb-4 text-success">🐖 Add Feed Record for Stage: <?= htmlspecialchars($stage) ?></h3>
  <form method="POST">
    <input type="hidden" name="sow_id" value="<?= htmlspecialchars($sow_id) ?>">
    <input type="hidden" name="stage" value="<?= htmlspecialchars($stage) ?>">

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control" required>
      </div>
      <div class="col-12">
        <label class="form-label">Type of Feed</label>
        <input type="text" name="type_of_feed" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Daily Intake (kg)</label>
        <input type="number" step="0.01" name="daily_intake" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Total Days</label>
        <input type="number" name="total_days" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Price per kg</label>
        <input type="number" step="0.01" name="price_per_kg" class="form-control" required>
      </div>
    </div>

    <div class="mt-4 d-flex justify-content-end gap-2">
      <button type="submit" class="btn btn-success"><i class="bi bi-save2"></i> Save Record</button>
      <a href="../gilt/view_roadmap.php?sow_id=<?= $sow_id ?>" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
</body>
</html>
