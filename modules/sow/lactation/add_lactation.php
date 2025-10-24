<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch sows for the dropdown
$sows = $pdo->query("SELECT sow_id, ear_tag_no FROM sows ORDER BY ear_tag_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sow_id                     = $_POST['sow_id'];
    $farrowing_date             = $_POST['farrowing_date'];
    $avg_birth_weight           = $_POST['avg_birth_weight'] ?: null;
    $total_born                 = $_POST['total_born'] ?: null;
    $stillborn_male             = $_POST['stillborn_male'] ?: null;
    $stillborn_female           = $_POST['stillborn_female'] ?: null;
    $mummified_male             = $_POST['mummified_male'] ?: null;
    $mummified_female           = $_POST['mummified_female'] ?: null;
    $total_mortality_birth      = $_POST['total_mortality_birth'] ?: null;
    $piglets_alive_male         = $_POST['piglets_alive_male'] ?: null;
    $piglets_alive_female       = $_POST['piglets_alive_female'] ?: null;
    $total_piglets_alive        = $_POST['total_piglets_alive'] ?: null;
    $mortality_during_lactation = $_POST['mortality_during_lactation'] ?: null;
    $total_weaned               = $_POST['total_weaned'] ?: null;
    $avg_weaning_weight         = $_POST['avg_weaning_weight'] ?: null;
    $survival_rate              = $_POST['survival_rate'] ?: null;
    $remarks                    = $_POST['remarks'] ?? null;

    $stmt = $pdo->prepare("
        INSERT INTO sow_lactation
        (sow_id, farrowing_date, avg_birth_weight, total_born, stillborn_male, stillborn_female,
         mummified_male, mummified_female, total_mortality_birth, piglets_alive_male, piglets_alive_female,
         total_piglets_alive, mortality_during_lactation, total_weaned, avg_weaning_weight, survival_rate, remarks)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $sow_id, $farrowing_date, $avg_birth_weight, $total_born, $stillborn_male, $stillborn_female,
        $mummified_male, $mummified_female, $total_mortality_birth, $piglets_alive_male, $piglets_alive_female,
        $total_piglets_alive, $mortality_during_lactation, $total_weaned, $avg_weaning_weight, $survival_rate, $remarks
    ]);

    header("Location: list_lactation.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Lactation Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">➕ Add Lactation Record</h2>

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

    <div class="mb-3">
      <label class="form-label">Farrowing Date</label>
      <input type="date" name="farrowing_date" class="form-control" required>
    </div>

    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Avg Birth Weight (kg)</label>
        <input type="number" step="0.01" name="avg_birth_weight" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Total Piglets Born</label>
        <input type="number" name="total_born" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Total Mortality at Birth</label>
        <input type="number" name="total_mortality_birth" class="form-control">
      </div>
    </div>

    <hr>

    <div class="row g-3">
      <div class="col-md-3">
        <label class="form-label">Stillborn Male</label>
        <input type="number" name="stillborn_male" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">Stillborn Female</label>
        <input type="number" name="stillborn_female" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">Mummified Male</label>
        <input type="number" name="mummified_male" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">Mummified Female</label>
        <input type="number" name="mummified_female" class="form-control">
      </div>
    </div>

    <hr>

    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Piglets Alive (Male)</label>
        <input type="number" name="piglets_alive_male" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Piglets Alive (Female)</label>
        <input type="number" name="piglets_alive_female" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Total Piglets Alive</label>
        <input type="number" name="total_piglets_alive" class="form-control">
      </div>
    </div>

    <hr>

    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Mortality During Lactation</label>
        <input type="number" name="mortality_during_lactation" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Total Weaned</label>
        <input type="number" name="total_weaned" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Avg Weaning Weight (kg)</label>
        <input type="number" step="0.01" name="avg_weaning_weight" class="form-control">
      </div>
    </div>

    <div class="row g-3 mt-1">
      <div class="col-md-4">
        <label class="form-label">Survival Rate (%)</label>
        <input type="number" step="0.01" name="survival_rate" class="form-control">
      </div>
      <div class="col-md-8">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" class="form-control" rows="3"></textarea>
      </div>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-success">Save</button>
      <a href="list_lactation.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
</body>
</html>

