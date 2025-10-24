<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM fattener_growth_record WHERE growth_id = ?");
$stmt->execute([$id]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$r) die("Record not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stage = $_POST['stage'];
    $initial_weight = $_POST['initial_weight'];
    $initial_date = $_POST['initial_date'];
    $final_weight = $_POST['final_weight'];
    $final_date = $_POST['final_date'];
    $feed_consumed = $_POST['feed_consumed'];
    $remarks = $_POST['remarks'];

    // 🧮 Recalculate
    $days_in_stage = (strtotime($final_date) - strtotime($initial_date)) / 86400;
    $weight_gain = $final_weight - $initial_weight;
    $adg = ($days_in_stage > 0) ? round($weight_gain / $days_in_stage, 3) : 0;
    $fcr = ($weight_gain > 0) ? round($feed_consumed / $weight_gain, 3) : 0;

    $update = $pdo->prepare("UPDATE fattener_growth_record SET 
        stage=?, initial_weight=?, initial_date=?, final_weight=?, final_date=?, days_in_stage=?, feed_consumed=?, adg=?, fcr=?, remarks=?
        WHERE growth_id=?");
    $update->execute([$stage, $initial_weight, $initial_date, $final_weight, $final_date, $days_in_stage, $feed_consumed, $adg, $fcr, $remarks, $id]);

    header("Location: list_growth.php?updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Edit Growth Record</title></head>
<body>
<h2>Edit Growth Record (Auto ADG & FCR)</h2>

<form method="POST">
    Stage:
    <select name="stage">
        <option value="Weaning-Starter" <?= $r['stage']=='Weaning-Starter'?'selected':'' ?>>Weaning → Starter</option>
        <option value="Starter-Grower" <?= $r['stage']=='Starter-Grower'?'selected':'' ?>>Starter → Grower</option>
        <option value="Grower-Finisher" <?= $r['stage']=='Grower-Finisher'?'selected':'' ?>>Grower → Finisher</option>
        <option value="Finisher-Market" <?= $r['stage']=='Finisher-Market'?'selected':'' ?>>Finisher → Market</option>
    </select><br><br>

    Initial Weight: <input type="number" step="0.01" name="initial_weight" value="<?= $r['initial_weight'] ?>" required><br><br>
    Initial Date: <input type="date" name="initial_date" value="<?= $r['initial_date'] ?>" required><br><br>
    Final Weight: <input type="number" step="0.01" name="final_weight" value="<?= $r['final_weight'] ?>" required><br><br>
    Final Date: <input type="date" name="final_date" value="<?= $r['final_date'] ?>" required><br><br>
    Feed Consumed: <input type="number" step="0.01" name="feed_consumed" value="<?= $r['feed_consumed'] ?>" required><br><br>

    Remarks:<br>
    <textarea name="remarks" rows="3" cols="40"><?= htmlspecialchars($r['remarks']) ?></textarea><br><br>
    <input type="submit" value="Update Record">
</form>

<br>
<a href="list_growth.php">Back to List</a>
</body>
</html>
