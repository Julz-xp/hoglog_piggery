<?php
require_once __DIR__ . '/../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $piglet_id = $_POST['piglet_id'];
    $record_type = $_POST['record_type'];
    $record_date = $_POST['record_date'];
    $stage = $_POST['stage'];

    // flexible fields
    $symptoms = $_POST['symptoms'] ?? null;
    $findings = $_POST['findings'] ?? null;
    $treatment = $_POST['treatment'] ?? null;
    $type_of_vaccine = $_POST['type_of_vaccine'] ?? null;
    $product_description = $_POST['product_description'] ?? null;
    $type_of_vitamins = $_POST['type_of_vitamins'] ?? null;
    $farm_vet = $_POST['farm_vet'];
    $cost = $_POST['cost'];
    $remarks = $_POST['remarks'];

    $stmt = $pdo->prepare("INSERT INTO piglet_health_record 
        (piglet_id, record_type, record_date, stage, symptoms, findings, treatment,
         type_of_vaccine, product_description, type_of_vitamins, farm_vet, cost, remarks)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");

    $stmt->execute([
        $piglet_id, $record_type, $record_date, $stage,
        $symptoms, $findings, $treatment,
        $type_of_vaccine, $product_description, $type_of_vitamins,
        $farm_vet, $cost, $remarks
    ]);

    header("Location: list_health.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Piglet Health Record</title></head>
<body>
<h2>Add Piglet Health Record</h2>
<form method="POST">
    Piglet ID: <input type="number" name="piglet_id" required><br><br>

    Record Type:
    <select name="record_type" required>
        <option value="Disease">Disease</option>
        <option value="Vaccination">Vaccination</option>
        <option value="Deworming">Deworming</option>
        <option value="Vitamins">Vitamins</option>
    </select><br><br>

    Stage:
    <select name="stage">
        <option value="Creep Feed">Creep Feed</option>
        <option value="Booster">Booster</option>
        <option value="Starter">Starter</option>
        <option value="Weaning">Weaning</option>
    </select><br><br>

    Record Date: <input type="date" name="record_date" required><br><br>

    <!-- DISEASE -->
    <b>Disease Record</b><br>
    Symptoms:<br><textarea name="symptoms" rows="2" cols="40"></textarea><br>
    Findings:<br><textarea name="findings" rows="2" cols="40"></textarea><br>
    Treatment:<br><textarea name="treatment" rows="2" cols="40"></textarea><br><br>

    <!-- VACCINE -->
    <b>Vaccination Record</b><br>
    Type of Vaccine:<br><input type="text" name="type_of_vaccine" size="40"><br><br>

    <!-- DEWORMING -->
    <b>Deworming Record</b><br>
    Product Description:<br><input type="text" name="product_description" size="40"><br><br>

    <!-- VITAMINS -->
    <b>Vitamins Record</b><br>
    Type of Vitamins:<br><input type="text" name="type_of_vitamins" size="40"><br><br>

    Farm Vet / Technician: <input type="text" name="farm_vet"><br><br>
    Cost (₱): <input type="number" step="0.01" name="cost"><br><br>
    Remarks:<br>
    <textarea name="remarks" rows="3" cols="40"></textarea><br><br>

    <button type="submit">Save</button>
</form>
</body>
</html>
