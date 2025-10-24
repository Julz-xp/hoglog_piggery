<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die("Invalid request.");
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM piglet_records WHERE piglet_id = ?");
$stmt->execute([$id]);
$piglet = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$piglet) die("Record not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['sow_id','farrowing_date','total_born','alive_male','alive_female','stillborn','mummified',
               'avg_birth_weight','mortality_during_lactation','weaning_date','total_weaned','avg_weaning_weight','remarks'];
    foreach ($fields as $f) $$f = $_POST[$f];

    $survival_rate = ($total_born > 0) ? round(($total_weaned / $total_born) * 100, 2) : 0;

    $stmt = $pdo->prepare("UPDATE piglet_records SET
        sow_id=?, farrowing_date=?, total_born=?, alive_male=?, alive_female=?, stillborn=?, mummified=?, avg_birth_weight=?,
        mortality_during_lactation=?, weaning_date=?, total_weaned=?, avg_weaning_weight=?, survival_rate=?, remarks=?
        WHERE piglet_id=?");
    $stmt->execute([
        $sow_id,$farrowing_date,$total_born,$alive_male,$alive_female,$stillborn,$mummified,$avg_birth_weight,
        $mortality_during_lactation,$weaning_date,$total_weaned,$avg_weaning_weight,$survival_rate,$remarks,$id
    ]);

    header("Location: list_piglet.php?updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Piglet Record</title></head>
<body>
<h2>Edit Piglet Record</h2>
<form method="POST">
    Sow ID: <input type="number" name="sow_id" value="<?= $piglet['sow_id'] ?>" required><br><br>
    Farrowing Date: <input type="date" name="farrowing_date" value="<?= $piglet['farrowing_date'] ?>"><br><br>
    Total Born: <input type="number" name="total_born" value="<?= $piglet['total_born'] ?>"><br><br>
    Alive (Male): <input type="number" name="alive_male" value="<?= $piglet['alive_male'] ?>"><br><br>
    Alive (Female): <input type="number" name="alive_female" value="<?= $piglet['alive_female'] ?>"><br><br>
    Stillborn: <input type="number" name="stillborn" value="<?= $piglet['stillborn'] ?>"><br><br>
    Mummified: <input type="number" name="mummified" value="<?= $piglet['mummified'] ?>"><br><br>
    Avg. Birth Weight (kg): <input type="number" step="0.01" name="avg_birth_weight" value="<?= $piglet['avg_birth_weight'] ?>"><br><br>
    Mortality During Lactation: <input type="number" name="mortality_during_lactation" value="<?= $piglet['mortality_during_lactation'] ?>"><br><br>
    Weaning Date: <input type="date" name="weaning_date" value="<?= $piglet['weaning_date'] ?>"><br><br>
    Total Weaned: <input type="number" name="total_weaned" value="<?= $piglet['total_weaned'] ?>"><br><br>
    Avg. Weaning Weight (kg): <input type="number" step="0.01" name="avg_weaning_weight" value="<?= $piglet['avg_weaning_weight'] ?>"><br><br>
    Remarks:<br>
    <textarea name="remarks" rows="4" cols="40"><?= $piglet['remarks'] ?></textarea><br><br>
    <button type="submit">Update</button>
</form>
</body>
</html>
