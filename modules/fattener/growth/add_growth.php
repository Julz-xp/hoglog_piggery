<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch fattener list
$fatteners = $pdo->query("SELECT fattener_id, ear_tag_no FROM fattener_records ORDER BY fattener_id DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fattener_id = $_POST['fattener_id'];
    $stage = $_POST['stage'];
    $initial_weight = $_POST['initial_weight'];
    $initial_date = $_POST['initial_date'];
    $final_weight = $_POST['final_weight'];
    $final_date = $_POST['final_date'];
    $feed_consumed = $_POST['feed_consumed'];
    $remarks = $_POST['remarks'];

    // 🧮 Compute derived values
    $days_in_stage = (strtotime($final_date) - strtotime($initial_date)) / 86400; // days difference
    $weight_gain = $final_weight - $initial_weight;
    $adg = ($days_in_stage > 0) ? round($weight_gain / $days_in_stage, 3) : 0;
    $fcr = ($weight_gain > 0) ? round($feed_consumed / $weight_gain, 3) : 0;

    // 🗄️ Insert record
    $stmt = $pdo->prepare("INSERT INTO fattener_growth_record 
        (fattener_id, stage, initial_weight, initial_date, final_weight, final_date, days_in_stage, feed_consumed, adg, fcr, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$fattener_id, $stage, $initial_weight, $initial_date, $final_weight, $final_date, $days_in_stage, $feed_consumed, $adg, $fcr, $remarks]);

    header("Location: list_growth.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Add Growth Record</title>
</head>
<body>
<h2>Add Growth Record (Auto ADG & FCR)</h2>

<form method="POST">
    Fattener: 
    <select name="fattener_id" required>
        <option value="">-- Select Fattener --</option>
        <?php foreach ($fatteners as $f): ?>
            <option value="<?= $f['fattener_id'] ?>"><?= htmlspecialchars($f['ear_tag_no']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    Stage:
    <select name="stage" required>
        <option value="Weaning-Starter">Weaning → Starter</option>
        <option value="Starter-Grower">Starter → Grower</option>
        <option value="Grower-Finisher">Grower → Finisher</option>
        <option value="Finisher-Market">Finisher → Market</option>
    </select><br><br>

    Initial Weight (kg): <input type="number" step="0.01" name="initial_weight" required><br><br>
    Initial Date: <input type="date" name="initial_date" required><br><br>
    Final Weight (kg): <input type="number" step="0.01" name="final_weight" required><br><br>
    Final Date: <input type="date" name="final_date" required><br><br>
    Feed Consumed (kg): <input type="number" step="0.01" name="feed_consumed" required><br><br>

    Remarks:<br>
    <textarea name="remarks" rows="3" cols="40"></textarea><br><br>

    <input type="submit" value="Add Record">
</form>

<br>
<a href="list_growth.php">Back to List</a>
</body>
</html>
