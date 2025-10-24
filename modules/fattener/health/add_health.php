<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch fattener list
$fatteners = $pdo->query("SELECT fattener_id, ear_tag_no FROM fattener_records ORDER BY fattener_id DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fattener_id = $_POST['fattener_id'];
    $record_type = $_POST['record_type'];
    $record_date = $_POST['record_date'];
    $stage = $_POST['stage'];
    $description = $_POST['description'];
    $findings = $_POST['findings'];
    $treatment = $_POST['treatment'];
    $farm_vet = $_POST['farm_vet'];
    $cost = $_POST['cost'];
    $remarks = $_POST['remarks'];

    $stmt = $pdo->prepare("INSERT INTO fattener_health_record 
        (fattener_id, record_type, record_date, stage, description, findings, treatment, farm_vet, cost, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$fattener_id, $record_type, $record_date, $stage, $description, $findings, $treatment, $farm_vet, $cost, $remarks]);

    header("Location: list_health.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Add Health Record</title>
</head>
<body>
<h2>➕ Add Health Record</h2>

<form method="POST">
    Fattener:
    <select name="fattener_id" required>
        <option value="">-- Select Fattener --</option>
        <?php foreach ($fatteners as $f): ?>
            <option value="<?= $f['fattener_id'] ?>"><?= htmlspecialchars($f['ear_tag_no']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    Record Type:
    <select name="record_type" required>
        <option value="Disease">Disease</option>
        <option value="Vaccination">Vaccination</option>
        <option value="Deworming">Deworming</option>
        <option value="Vitamins">Vitamins</option>
    </select><br><br>

    Record Date: <input type="date" name="record_date" required><br><br>

    Stage:
    <select name="stage" required>
        <option value="Weaning">Weaning</option>
        <option value="Starter">Starter</option>
        <option value="Grower">Grower</option>
        <option value="Finisher">Finisher</option>
    </select><br><br>

    Description:<br><textarea name="description" rows="3" cols="40"></textarea><br><br>
    Findings:<br><textarea name="findings" rows="3" cols="40"></textarea><br><br>
    Treatment:<br><textarea name="treatment" rows="3" cols="40"></textarea><br><br>
    Farm Vet: <input type="text" name="farm_vet"><br><br>
    Cost (₱): <input type="number" step="0.01" name="cost"><br><br>
    Remarks:<br><textarea name="remarks" rows="3" cols="40"></textarea><br><br>

    <input type="submit" value="Add Record">
</form>

<br>
<a href="list_health.php">⬅️ Back to List</a>
</body>
</html>
