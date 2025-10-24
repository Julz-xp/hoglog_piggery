<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

// Fetch record
$stmt = $pdo->prepare("SELECT * FROM batch_growth_summary WHERE growth_id=?");
$stmt->execute([$id]);
$g = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$g) die("Record not found");

// Fetch batches for dropdown
$batches = $pdo->query("SELECT batch_id, batch_no FROM batch_records ORDER BY batch_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch_id         = $_POST['batch_id'];
    $stage            = $_POST['stage'];
    $avg_initial_weight = $_POST['avg_initial_weight'];
    $avg_final_weight   = $_POST['avg_final_weight'];
    $avg_adg          = $_POST['avg_adg'];
    $avg_fcr          = $_POST['avg_fcr'];
    $avg_feed_consumed = $_POST['avg_feed_consumed'];
    $mortality_count  = $_POST['mortality_count'];
    $remarks          = $_POST['remarks'];

    $stmt = $pdo->prepare("
        UPDATE batch_growth_summary SET
        batch_id=?, stage=?, avg_initial_weight=?, avg_final_weight=?, avg_adg=?, avg_fcr=?, avg_feed_consumed=?, mortality_count=?, remarks=?
        WHERE growth_id=?
    ");
    $stmt->execute([$batch_id, $stage, $avg_initial_weight, $avg_final_weight, $avg_adg, $avg_fcr, $avg_feed_consumed, $mortality_count, $remarks, $id]);

    header("Location: list_growth.php?updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Edit Growth Record</title></head>
<body>
<h2>Edit Batch Growth Record</h2>
<form method="POST">
    Batch:
    <select name="batch_id" required>
        <?php foreach ($batches as $b): ?>
        <option value="<?= $b['batch_id'] ?>" <?= $b['batch_id']==$g['batch_id']?'selected':'' ?>>
            <?= htmlspecialchars($b['batch_no']) ?>
        </option>
        <?php endforeach; ?>
    </select><br><br>

    Stage:
    <select name="stage" required>
        <?php
        $stages = ['Weaning-Starter','Starter-Grower','Grower-Finisher','Finisher-Market'];
        foreach ($stages as $s) {
            $sel = ($g['stage']==$s)?'selected':'';
            echo "<option value='$s' $sel>$s</option>";
        }
        ?>
    </select><br><br>

    Avg Initial Wt: <input type="number" step="0.01" name="avg_initial_weight" value="<?= $g['avg_initial_weight'] ?>"><br><br>
    Avg Final Wt: <input type="number" step="0.01" name="avg_final_weight" value="<?= $g['avg_final_weight'] ?>"><br><br>
    ADG: <input type="number" step="0.001" name="avg_adg" value="<?= $g['avg_adg'] ?>"><br><br>
    FCR: <input type="number" step="0.001" name="avg_fcr" value="<?= $g['avg_fcr'] ?>"><br><br>
    Avg Feed Consumed (kg): <input type="number" step="0.01" name="avg_feed_consumed" value="<?= $g['avg_feed_consumed'] ?>"><br><br>
    Mortality Count: <input type="number" name="mortality_count" value="<?= $g['mortality_count'] ?>"><br><br>
    Remarks:<br><textarea name="remarks" rows="4" cols="50"><?= htmlspecialchars($g['remarks']) ?></textarea><br><br>

    <button type="submit">Update</button>
</form>
<br>
<a href="list_growth.php">← Back</a>
</body>
</html>
