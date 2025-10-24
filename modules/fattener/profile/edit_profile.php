<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die("Invalid Request");

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM fattener_records WHERE fattener_id = ?");
$stmt->execute([$id]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$record) die("Record not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ear_tag_no = $_POST['ear_tag_no'];
    $batch_no = $_POST['batch_no'];
    $sex = $_POST['sex'];
    $breed_line = $_POST['breed_line'];
    $birth_date = $_POST['birth_date'];
    $weaning_date = $_POST['weaning_date'];
    $weaning_weight = $_POST['weaning_weight'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];

    $update = $pdo->prepare("UPDATE fattener_records SET 
        ear_tag_no=?, batch_no=?, sex=?, breed_line=?, birth_date=?, weaning_date=?, weaning_weight=?, status=?, notes=? 
        WHERE fattener_id=?");
    $update->execute([$ear_tag_no, $batch_no, $sex, $breed_line, $birth_date, $weaning_date, $weaning_weight, $status, $notes, $id]);

    header("Location: list_profile.php?updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Fattener Profile</title>
</head>
<body>
    <h2>Edit Fattener Profile</h2>
    <form method="POST">
        Ear Tag No: <input type="text" name="ear_tag_no" value="<?= htmlspecialchars($record['ear_tag_no']) ?>" required><br><br>
        Batch No: <input type="text" name="batch_no" value="<?= htmlspecialchars($record['batch_no']) ?>" required><br><br>
        Sex:
        <select name="sex" required>
            <option value="Male" <?= $record['sex'] === 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $record['sex'] === 'Female' ? 'selected' : '' ?>>Female</option>
        </select><br><br>
        Breed Line: <input type="text" name="breed_line" value="<?= htmlspecialchars($record['breed_line']) ?>" required><br><br>
        Birth Date: <input type="date" name="birth_date" value="<?= htmlspecialchars($record['birth_date']) ?>" required><br><br>
        Weaning Date: <input type="date" name="weaning_date" value="<?= htmlspecialchars($record['weaning_date']) ?>" required><br><br>
        Weaning Weight (kg): <input type="number" step="0.01" name="weaning_weight" value="<?= htmlspecialchars($record['weaning_weight']) ?>" required><br><br>
        Status:
        <select name="status">
            <option value="Active" <?= $record['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
            <option value="Market Ready" <?= $record['status'] === 'Market Ready' ? 'selected' : '' ?>>Market Ready</option>
            <option value="Sold" <?= $record['status'] === 'Sold' ? 'selected' : '' ?>>Sold</option>
            <option value="Dead" <?= $record['status'] === 'Dead' ? 'selected' : '' ?>>Dead</option>
        </select><br><br>
        Notes:<br>
        <textarea name="notes" rows="3" cols="40"><?= htmlspecialchars($record['notes']) ?></textarea><br><br>
        <input type="submit" value="Update Record">
    </form>
    <br>
    <a href="list_profile.php">Back to List</a>
</body>
</html>
