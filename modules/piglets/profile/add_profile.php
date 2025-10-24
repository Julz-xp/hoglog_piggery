<?php
require_once __DIR__ . '/../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sow_id = $_POST['sow_id'];
    $farrowing_date = $_POST['farrowing_date'];
    $total_born = $_POST['total_born'];
    $alive_male = $_POST['alive_male'];
    $alive_female = $_POST['alive_female'];
    $stillborn = $_POST['stillborn'];
    $mummified = $_POST['mummified'];
    $avg_birth_weight = $_POST['avg_birth_weight'];
    $mortality_during_lactation = $_POST['mortality_during_lactation'];
    $weaning_date = $_POST['weaning_date'];
    $total_weaned = $_POST['total_weaned'];
    $avg_weaning_weight = $_POST['avg_weaning_weight'];
    $remarks = $_POST['remarks'];

    // ✅ Compute survival rate
    $survival_rate = ($total_born > 0) ? round(($total_weaned / $total_born) * 100, 2) : 0;

    $stmt = $pdo->prepare("INSERT INTO piglet_records 
        (sow_id, farrowing_date, total_born, alive_male, alive_female, stillborn, mummified, avg_birth_weight,
         mortality_during_lactation, weaning_date, total_weaned, avg_weaning_weight, survival_rate, remarks)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

    $stmt->execute([
        $sow_id, $farrowing_date, $total_born, $alive_male, $alive_female, $stillborn, $mummified, $avg_birth_weight,
        $mortality_during_lactation, $weaning_date, $total_weaned, $avg_weaning_weight, $survival_rate, $remarks
    ]);

    header("Location: list_piglet.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Piglet Record</title></head>
<body>
<h2>Add Piglet Record</h2>
<form method="POST">
    Sow ID: <input type="number" name="sow_id" required><br><br>
    Farrowing Date: <input type="date" name="farrowing_date"><br><br>
    Total Born: <input type="number" name="total_born"><br><br>
    Alive (Male): <input type="number" name="alive_male"><br><br>
    Alive (Female): <input type="number" name="alive_female"><br><br>
    Stillborn: <input type="number" name="stillborn"><br><br>
    Mummified: <input type="number" name="mummified"><br><br>
    Average Birth Weight (kg): <input type="number" step="0.01" name="avg_birth_weight"><br><br>
    Mortality During Lactation: <input type="number" name="mortality_during_lactation"><br><br>
    Weaning Date: <input type="date" name="weaning_date"><br><br>
    Total Weaned: <input type="number" name="total_weaned"><br><br>
    Average Weaning Weight (kg): <input type="number" step="0.01" name="avg_weaning_weight"><br><br>
    Remarks:<br>
    <textarea name="remarks" rows="4" cols="40"></textarea><br><br>
    <button type="submit">Save</button>
</form>
</body>
</html>
