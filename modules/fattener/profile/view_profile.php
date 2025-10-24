<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) {
    die("Invalid Request");
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM fattener_records WHERE fattener_id = ?");
$stmt->execute([$id]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$record) die("Record not found.");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>View Fattener Profile</title>
</head>
<body>
    <h2>View Fattener Profile</h2>

    <p><b>ID:</b> <?= htmlspecialchars($record['fattener_id']) ?></p>
    <p><b>Ear Tag No:</b> <?= htmlspecialchars($record['ear_tag_no']) ?></p>
    <p><b>Batch No:</b> <?= htmlspecialchars($record['batch_no']) ?></p>
    <p><b>Sex:</b> <?= htmlspecialchars($record['sex']) ?></p>
    <p><b>Breed Line:</b> <?= htmlspecialchars($record['breed_line']) ?></p>
    <p><b>Birth Date:</b> <?= htmlspecialchars($record['birth_date']) ?></p>
    <p><b>Weaning Date:</b> <?= htmlspecialchars($record['weaning_date']) ?></p>
    <p><b>Weaning Weight:</b> <?= htmlspecialchars($record['weaning_weight']) ?> kg</p>
    <p><b>Status:</b> <?= htmlspecialchars($record['status']) ?></p>
    <p><b>Notes:</b> <?= nl2br(htmlspecialchars($record['notes'])) ?></p>

    <br>
    <a href="edit_profile.php?id=<?= $record['fattener_id'] ?>">Edit</a> |
    <a href="list_profile.php">Back to List</a>
</body>
</html>
