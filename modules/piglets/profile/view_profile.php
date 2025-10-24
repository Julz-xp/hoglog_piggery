<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request.");

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT p.*, s.ear_tag_no FROM piglet_records p 
                       JOIN sows s ON p.sow_id = s.sow_id WHERE p.piglet_id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$p) die("Record not found.");
?>

<!DOCTYPE html>
<html>
<head><title>View Piglet Record</title></head>
<body>
<h2>Piglet Batch Details</h2>
<p><b>Sow Ear Tag:</b> <?= htmlspecialchars($p['ear_tag_no']) ?></p>
<p><b>Farrowing Date:</b> <?= $p['farrowing_date'] ?></p>
<p><b>Total Born:</b> <?= $p['total_born'] ?></p>
<p><b>Alive (Male/Female):</b> <?= $p['alive_male'] ?>/<?= $p['alive_female'] ?></p>
<p><b>Stillborn:</b> <?= $p['stillborn'] ?></p>
<p><b>Mummified:</b> <?= $p['mummified'] ?></p>
<p><b>Avg. Birth Weight:</b> <?= $p['avg_birth_weight'] ?> kg</p>
<p><b>Weaning Date:</b> <?= $p['weaning_date'] ?></p>
<p><b>Total Weaned:</b> <?= $p['total_weaned'] ?></p>
<p><b>Avg. Weaning Weight:</b> <?= $p['avg_weaning_weight'] ?> kg</p>
<p><b>Survival Rate:</b> <?= $p['survival_rate'] ?>%</p>
<p><b>Remarks:</b> <?= nl2br(htmlspecialchars($p['remarks'])) ?></p>

<br>
<a href="edit_piglet.php?id=<?= $p['piglet_id'] ?>">Edit</a> |
<a href="list_piglet.php">Back to List</a>
</body>
</html>
