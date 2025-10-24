<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM fattener_health_record WHERE health_id = ?");
$stmt->execute([$id]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$r) die("Record not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $record_type = $_POST['record_type'];
    $record_date = $_POST['record_date'];
    $stage = $_POST['stage'];
    $description = $_POST['description'];
    $findings = $_POST['findings'];
    $treatment = $_POST['treatment'];
    $farm_vet = $_POST['farm_vet'];
    $cost = $_POST['cost'];
    $remarks = $_POST['remarks'];

    $stmt = $pdo->prepare("UPDATE fattener_health_record 
        SET record_type=?, record_date=?, stage=?, description=?, findings=?, treatment=?, farm_vet=?, cost=?, remarks=? 
        WHERE health_id=?");
    $stmt->execute([$record_type, $record_date, $stage, $description, $findings, $treatment, $farm_vet, $cost, $remarks, $id]);

    header("Location: list_health.php?updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Edit Health Record</title></head>
<body>
<h2>Edit Health Record</h2>

<form method="POST">
    Record Type:
    <select name="record_type">
        <option value="Disease" <?= $r['record_type']=='Disease'?'selected':'' ?>>Disease</option>
        <option value="Vaccination" <?= $r['record_type']=='Vaccination'?'selected':'' ?>>Vaccination</option>
        <option value="Deworming" <?= $r['record_type']=='Deworming'?'selected':'' ?>>Deworming</option>
        <option value="Vitamins" <?= $r['record_type']=='Vitamins'?'selected':'' ?>>Vitamins</option>
    </select><br><br>

    Record Date: <input type="date" name="record_date" value="<?= $r['record_date'] ?>"><br><br>
    Stage:
    <select name="stage">
        <option value="Weaning" <?= $r['stage']=='Weaning'?'selected':'' ?>>Weaning</option>
        <option value="Starter" <?= $r['stage']=='Starter'?'selected':'' ?>>Starter</option>
        <option value="Grower" <?= $r['stage']=='Grower'?'selected':'' ?>>Grower</option>
        <option value="Finisher" <?= $r['stage']=='Finisher'?'selected':'' ?>>Finisher</option>
    </select><br><br>

    Description:<br><textarea name="description" rows="3" cols="40"><?= htmlspecialchars($r['description']) ?></textarea><br><br>
    Findings:<br><textarea name="findings" rows="3" cols="40"><?= htmlspecialchars($r['findings']) ?></textarea><br><br>
    Treatment:<br><textarea name="treatment" rows="3" cols="40"><?= htmlspecialchars($r['treatment']) ?></textarea><br><br>
    Farm Vet: <input type="text" name="farm_vet" value="<?= htmlspecialchars($r['farm_vet']) ?>"><br><br>
    Cost (₱): <input type="number" step="0.01" name="cost" value="<?= $r['cost'] ?>"><br><br>
    Remarks:<br><textarea name="remarks" rows="3" cols="40"><?= htmlspecialchars($r['remarks']) ?></textarea><br><br>

    <input type="submit" value="Update Record">
</form>

<br>
<a href="list_health.php">⬅️ Back to List</a>
</body>
</html>
