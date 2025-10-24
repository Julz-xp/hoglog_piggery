<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

// Fetch record
$stmt = $pdo->prepare("SELECT * FROM sow_exit_record WHERE exit_id = ?");
$stmt->execute([$id]);
$exit = $stmt->fetch();
if (!$exit) die("Record not found!");

// Fetch sows
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

    $update = $pdo->prepare("
        UPDATE sow_exit_record 
        SET sow_id=?, culling_date=?, reason_for_culling=?, last_parity_summary=?, final_weight=?, sale_price=?, health_condition=?, exit_type=?, disposal_notes=?
        WHERE exit_id=?
    ");
    $update->execute([$sow_id, $culling_date, $reason_for_culling, $last_parity_summary, $final_weight, $sale_price, $health_condition, $exit_type, $disposal_notes, $id]);

    header("Location: list_exit.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Exit Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">✏️ Edit Exit Record</h2>
  <form method="POST">
    <div class="mb-3">
      <label>Sow (Ear Tag)</label>
      <select name="sow_id" class="form-select" required>
        <?php foreach ($sows as $sow): ?>
          <option value="<?= $sow['sow_id'] ?>" <?= ($sow['sow_id']==$exit['sow_id'])?'selected':'' ?>>
            <?= htmlspecialchars($sow['ear_tag_no']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3"><label>Culling Date</label><input type="date" name="culling_date" value="<?= $exit['culling_date'] ?>" class="form-control" required></div>
    <div class="mb-3"><label>Reason for Culling</label><input type="text" name="reason_for_culling" value="<?= htmlspecialchars($exit['reason_for_culling']) ?>" class="form-control" required></div>
    <div class="mb-3"><label>Last Parity Summary</label><textarea name="last_parity_summary" class="form-control" rows="2"><?= htmlspecialchars($exit['last_parity_summary']) ?></textarea></div>

    <div class="row g-3">
      <div class="col-md-4"><label>Final Weight (kg)</label><input type="number" step="0.01" name="final_weight" value="<?= $exit['final_weight'] ?>" class="form-control"></div>
      <div class="col-md-4"><label>Sale Price (₱)</label><input type="number" step="0.01" name="sale_price" value="<?= $exit['sale_price'] ?>" class="form-control"></div>
      <div class="col-md-4">
        <label>Exit Type</label>
        <select name="exit_type" class="form-select" required>
          <option value="Culled" <?= $exit['exit_type']=='Culled'?'selected':'' ?>>Culled</option>
          <option value="Sold" <?= $exit['exit_type']=='Sold'?'selected':'' ?>>Sold</option>
          <option value="Died" <?= $exit['exit_type']=='Died'?'selected':'' ?>>Died</option>
        </select>
      </div>
    </div>

    <div class="mt-3 mb-3"><label>Final Health Condition</label><input type="text" name="health_condition" value="<?= htmlspecialchars($exit['health_condition']) ?>" class="form-control"></div>
    <div class="mb-3"><label>Disposal Notes</label><textarea name="disposal_notes" class="form-control" rows="3"><?= htmlspecialchars($exit['disposal_notes']) ?></textarea></div>

    <button type="submit" class="btn btn-warning">Update</button>
    <a href="list_exit.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
