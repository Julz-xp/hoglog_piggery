<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM batch_mortality_record WHERE mortality_id=?");
$stmt->execute([$id]);
$m = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$m) die("Record not found");

// Fetch batches
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
        UPDATE batch_mortality_record SET
        batch_id=?, pig_id=?, sex=?, date_of_mortality=?, cause_of_death=?, stage=?, remarks=?
        WHERE mortality_id=?
    ");
    $stmt->execute([$batch_id, $pig_id, $sex, $date_of_mortality, $cause_of_death, $stage, $remarks, $id]);

    header("Location: list_mortality.php?updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Edit Mortality Record</title></head>
<body>
<h2>Edit Mortality Record</h2>
<form method="POST">
    Batch:
    <select name="batch_id" required>
        <?php foreach ($batches as $b): ?>
        <option value="<?= $b['batch_id'] ?>" <?= $b['batch_id']==$m['batch_id']?'selected':'' ?>>
            <?= htmlspecialchars($b['batch_no']) ?>
        </option>
        <?php endforeach; ?>
    </select><br><br>

    Pig ID: <input type="text" name="pig_id" value="<?= htmlspecialchars($m['pig_id']) ?>"><br><br>
    Sex:
    <select name="sex">
        <option value="">-- Select --</option>
        <option value="Male" <?= $m['sex']=='Male'?'selected':'' ?>>Male</option>
        <option value="Female" <?= $m['sex']=='Female'?'selected':'' ?>>Female</option>
    </select><br><br>

    Date of Mortality: <input type="date" name="date_of_mortality" value="<?= $m['date_of_mortality'] ?>"><br><br>
    Cause of Death:<br><textarea name="cause_of_death" rows="3" cols="50"><?= htmlspecialchars($m['cause_of_death']) ?></textarea><br><br>
    Stage:
    <select name="stage" required>
        <?php
        $stages = ['Weaning-Starter','Starter-Grower','Grower-Finisher','Finisher-Market'];
        foreach ($stages as $s) {
            $sel = ($m['stage']==$s)?'selected':'';
            echo "<option value='$s' $sel>$s</option>";
        }
        ?>
    </select><br><br>
    Remarks:<br><textarea name="remarks" rows="4" cols="50"><?= htmlspecialchars($m['remarks']) ?></textarea><br><br>

    <button type="submit">Update Record</button>
</form>
<br>
<a href="list_mortality.php">← Back</a>
</body>
</html>
