<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

// Fetch record
$stmt = $pdo->prepare("SELECT * FROM sow_lactation WHERE lactation_id = ?");
$stmt->execute([$id]);
$l = $stmt->fetch();
if (!$l) die("Record not found!");

// Fetch sow list
$sows = $pdo->query("SELECT sow_id, ear_tag_no FROM sows ORDER BY ear_tag_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sow_id = $_POST['sow_id'];
    $farrowing_date = $_POST['farrowing_date'];
    $avg_birth_weight = $_POST['avg_birth_weight'];
    $total_born = $_POST['total_born'];
    $stillborn_male = $_POST['stillborn_male'];
    $stillborn_female = $_POST['stillborn_female'];
    $mummified_male = $_POST['mummified_male'];
    $mummified_female = $_POST['mummified_female'];
    $total_mortality_birth = $_POST['total_mortality_birth'];
    $piglets_alive_male = $_POST['piglets_alive_male'];
    $piglets_alive_female = $_POST['piglets_alive_female'];
    $total_piglets_alive = $_POST['total_piglets_alive'];
    $mortality_during_lactation = $_POST['mortality_during_lactation'];
    $total_weaned = $_POST['total_weaned'];
    $avg_weaning_weight = $_POST['avg_weaning_weight'];
    $survival_rate = $_POST['survival_rate'];
    $remarks = $_POST['remarks'];

    $update = $pdo->prepare("
        UPDATE sow_lactation
        SET sow_id=?, farrowing_date=?, avg_birth_weight=?, total_born=?, stillborn_male=?, stillborn_female=?,
            mummified_male=?, mummified_female=?, total_mortality_birth=?, piglets_alive_male=?, piglets_alive_female=?,
            total_piglets_alive=?, mortality_during_lactation=?, total_weaned=?, avg_weaning_weight=?, survival_rate=?, remarks=?
        WHERE lactation_id=?
    ");
    $update->execute([$sow_id, $farrowing_date, $avg_birth_weight, $total_born, $stillborn_male, $stillborn_female,
                      $mummified_male, $mummified_female, $total_mortality_birth, $piglets_alive_male,
                      $piglets_alive_female, $total_piglets_alive, $mortality_during_lactation,
                      $total_weaned, $avg_weaning_weight, $survival_rate, $remarks, $id]);

    header("Location: list_lactation.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Lactation Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">✏️ Edit Lactation Record</h2>
  <form method="POST">
    <div class="mb-3">
      <label>Sow (Ear Tag):</label>
      <select name="sow_id" class="form-select">
        <?php foreach ($sows as $sow): ?>
          <option value="<?= $sow['sow_id'] ?>" <?= ($sow['sow_id']==$l['sow_id'])?'selected':'' ?>>
            <?= htmlspecialchars($sow['ear_tag_no']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3"><label>Farrowing Date:</label><input type="date" name="farrowing_date" value="<?= $l['farrowing_date'] ?>" class="form-control"></div>
    <div class="mb-3"><label>Average Birth Weight (kg):</label><input type="number" step="0.01" name="avg_birth_weight" value="<?= $l['avg_birth_weight'] ?>" class="form-control"></div>
    <div class="mb-3"><label>Total Born:</label><input type="number" name="total_born" value="<?= $l['total_born'] ?>" class="form-control"></div>

    <div class="row">
      <div class="col"><label>Stillborn Male:</label><input type="number" name="stillborn_male" value="<?= $l['stillborn_male'] ?>" class="form-control"></div>
      <div class="col"><label>Stillborn Female:</label><input type="number" name="stillborn_female" value="<?= $l['stillborn_female'] ?>" class="form-control"></div>
    </div><br>

    <div class="row">
      <div class="col"><label>Mummified Male:</label><input type="number" name="mummified_male" value="<?= $l['mummified_male'] ?>" class="form-control"></div>
      <div class="col"><label>Mummified Female:</label><input type="number" name="mummified_female" value="<?= $l['mummified_female'] ?>" class="form-control"></div>
    </div><br>

    <div class="mb-3"><label>Total Mortality at Birth:</label><input type="number" name="total_mortality_birth" value="<?= $l['total_mortality_birth'] ?>" class="form-control"></div>
    <div class="row">
      <div class="col"><label>Piglets Alive (Male):</label><input type="number" name="piglets_alive_male" value="<?= $l['piglets_alive_male'] ?>" class="form-control"></div>
      <div class="col"><label>Piglets Alive (Female):</label><input type="number" name="piglets_alive_female" value="<?= $l['piglets_alive_female'] ?>" class="form-control"></div>
    </div><br>

    <div class="mb-3"><label>Total Piglets Alive:</label><input type="number" name="total_piglets_alive" value="<?= $l['total_piglets_alive'] ?>" class="form-control"></div>
    <div class="mb-3"><label>Mortality During Lactation:</label><input type="number" name="mortality_during_lactation" value="<?= $l['mortality_during_lactation'] ?>" class="form-control"></div>
    <div class="mb-3"><label>Total Weaned:</label><input type="number" name="total_weaned" value="<?= $l['total_weaned'] ?>" class="form-control"></div>
    <div class="mb-3"><label>Average Weaning Weight (kg):</label><input type="number" step="0.01" name="avg_weaning_weight" value="<?= $l['avg_weaning_weight'] ?>" class="form-control"></div>
    <div class="mb-3"><label>Survival Rate (%):</label><input type="number" step="0.01" name="survival_rate" value="<?= $l['survival_rate'] ?>" class="form-control"></div>
    <div class="mb-3"><label>Remarks:</label><textarea name="remarks" class="form-control"><?= $l['remarks'] ?></textarea></div>
    <button type="submit" class="btn btn-warning">Update</button>
    <a href="list_lactation.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
