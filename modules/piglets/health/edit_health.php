<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request.");

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM piglet_health_record WHERE health_id=?");
$stmt->execute([$id]);
$h = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$h) die("Record not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['piglet_id','record_type','record_date','stage','symptoms','findings','treatment',
               'type_of_vaccine','product_description','type_of_vitamins','farm_vet','cost','remarks'];
    foreach ($fields as $f) $$f = $_POST[$f] ?? null;

    $stmt = $pdo->prepare("UPDATE piglet_health_record SET 
        piglet_id=?, record_type=?, record_date=?, stage=?, symptoms=?, findings=?, treatment=?, 
        type_of_vaccine=?, product_description=?, type_of_vitamins=?, farm_vet=?, cost=?, remarks=? 
        WHERE health_id=?");
    $stmt->execute([
        $piglet_id,$record_type,$record_date,$stage,$symptoms,$findings,$treatment,
        $type_of_vaccine,$product_description,$type_of_vitamins,$farm_vet,$cost,$remarks,$id
    ]);

    header("Location: list_health.php?updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Piglet Health Record</title></head>
<body>
<h2>Edit Piglet Health Record</h2>
<form method="POST">
    Piglet ID: <input type="number" name="piglet_id" value="<?= $h['piglet_id'] ?>" required><br><br>

    Record Type:
    <select name="record_type">
        <option <?= $h['record_type']=='Disease'?'selected':'' ?>>Disease</option>
        <option <?= $h['record_type']=='Vaccination'?'selected':'' ?>>Vaccination</option>
        <option <?= $h['record_type']=='Deworming'?'selected':'' ?>>Deworming</option>
        <option <?= $h['record_type']=='Vitamins'?'selected':'' ?>>Vitamins</option>
    </select><br><br>

    Stage:
    <select name="stage">
        <option <?= $h['stage']=='Creep Feed'?'selected':'' ?>>Creep Feed</option>
        <option <?= $h['stage']=='Booster'?'selected':'' ?>>Booster</option>
        <option <?= $h['stage']=='Starter'?'selected':'' ?>>Starter</option>
        <option <?= $h['stage']=='Weaning'?'selected':'' ?>>Weaning</option>
    </select><br><br>

    Record Date: <input type="date" name="record_date" value="<?= $h['record_date'] ?>"><br><br>

    <b>Disease</b><br>
    Symptoms:<br><textarea name="symptoms" rows="2" cols="40"><?= $h['symptoms'] ?></textarea><br>
    Findings:<br><textarea name="findings" rows="2" cols="40"><?= $h['findings'] ?></textarea><br>
    Treatment:<br><textarea name="treatment" rows="2" cols="40"><?= $h['treatment'] ?></textarea><br><br>

    <b>Vaccination</b><br>
    Type of Vaccine:<br><input type="text" name="type_of_vaccine" size="40" value="<?= $h['type_of_vaccine'] ?>"><br><br>

    <b>Deworming</b><br>
    Product Description:<br><input type="text" name="product_description" size="40" value="<?= $h['product_description'] ?>"><br><br>

    <b>Vitamins</b><br>
    Type of Vitamins:<br><input type="text" name="type_of_vitamins" size="40" value="<?= $h['type_of_vitamins'] ?>"><br><br>

    Farm Vet: <input type="text" name="farm_vet" value="<?= $h['farm_vet'] ?>"><br><br>
    Cost (₱): <input type="number" step="0.01" name="cost" value="<?= $h['cost'] ?>"><br><br>
    Remarks:<br><textarea name="remarks" rows="3" cols="40"><?= $h['remarks'] ?></textarea><br><br>
    <button type="submit">Update</button>
</form>
</body>
</html>
