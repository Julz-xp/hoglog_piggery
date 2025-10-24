<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT m.*, b.batch_no
    FROM batch_mortality_record m
    JOIN batch_records b ON m.batch_id = b.batch_id
    WHERE m.mortality_id=?
");
$stmt->execute([$id]);
$m = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$m) die("Record not found");
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>View Mortality Record</title></head>
<body>
<h2>Mortality Record (Batch <?= htmlspecialchars($m['batch_no']) ?>)</h2>

<p><b>Pig ID:</b> <?= htmlspecialchars($m['pig_id']) ?></p>
<p><b>Sex:</b> <?= $m['sex'] ?></p>
<p><b>Date of Mortality:</b> <?= $m['date_of_mortality'] ?></p>
<p><b>Stage:</b> <?= $m['stage'] ?></p>
<p><b>Cause of Death:</b><br><?= nl2br(htmlspecialchars($m['cause_of_death'])) ?></p>
<p><b>Remarks:</b><br><?= nl2br(htmlspecialchars($m['remarks'])) ?></p>

<br>
<a href="edit_mortality.php?id=<?= $m['mortality_id'] ?>">✏️ Edit</a> |
<a href="list_mortality.php">← Back</a>
</body>
</html>
