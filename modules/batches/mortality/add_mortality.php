<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch batch list
$batches = $pdo->query("SELECT batch_id, batch_no FROM batch_records ORDER BY batch_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch_id        = $_POST['batch_id'];
    $pig_id          = $_POST['pig_id'] ?? null;
    $sex             = $_POST['sex'] ?? null;
    $date_of_mortality = $_POST['date_of_mortality'];
    $cause_of_death  = $_POST['cause_of_death'];
    $stage           = $_POST['stage'];
    $remarks         = $_POST['remarks'];

    $stmt = $pdo->prepare("
        INSERT INTO batch_mortality_record
        (batch_id, pig_id, sex, date_of_mortality, cause_of_death, stage, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$batch_id, $pig_id, $sex, $date_of_mortality, $cause_of_death, $stage, $remarks]);

    header("Location: list_mortality.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Add Mortality Record</title></head>
<body>
<h2>Add Mortality Record</h2>
<form method="POST">
    Batch:
    <select name="batch_id" required>
        <option value="">-- Select Batch --</option>
        <?php foreach ($batches as $b): ?>
        <option value="<?= $b['batch_id'] ?>"><?= htmlspecialchars($b['batch_no']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    Pig ID (optional): <input type="text" name="pig_id"><br><br>
    Sex:
    <select name="sex">
        <option value="">-- Select --</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select><br><br>

    Date of Mortality: <input type="date" name="date_of_mortality" required><br><br>
    Cause of Death:<br><textarea name="cause_of_death" rows="3" cols="50"></textarea><br><br>
    Stage:
    <select name="stage" required>
        <option value="Weaning-Starter">Weaning → Starter</option>
        <option value="Starter-Grower">Starter → Grower</option>
        <option value="Grower-Finisher">Grower → Finisher</option>
        <option value="Finisher-Market">Finisher → Market</option>
    </select><br><br>
    Remarks:<br><textarea name="remarks" rows="4" cols="50"></textarea><br><br>

    <button type="submit">Save Mortality Record</button>
</form>
<br>
<a href="list_mortality.php">← Back to list</a>
</body>
</html>
