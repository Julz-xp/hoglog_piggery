<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die('Invalid request');
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM batch_records WHERE batch_id=?");
$stmt->execute([$id]);
$b = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$b) die('Batch not found');
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>View Batch</title></head>
<body>
<h2>Batch: <?= htmlspecialchars($b['batch_no']) ?></h2>

<p><b>Building:</b> <?= htmlspecialchars($b['building_position']) ?></p>
<p><b>Total Pigs:</b> <?= $b['num_pigs_total'] ?> (♂ <?= $b['num_male'] ?> / ♀ <?= $b['num_female'] ?>)</p>
<p><b>Breed:</b> <?= htmlspecialchars($b['breed']) ?></p>
<p><b>Birth Date:</b> <?= $b['birth_date'] ?></p>
<p><b>Average Birth Weight:</b> <?= $b['avg_birth_weight'] ?> kg</p>
<p><b>Source Sow:</b> <?= htmlspecialchars($b['source_sow']) ?></p>
<p><b>Source Boar:</b> <?= htmlspecialchars($b['source_boar']) ?></p>
<p><b>Weaning Date:</b> <?= $b['weaning_date'] ?></p>
<p><b>Average Weaning Weight:</b> <?= $b['avg_weaning_weight'] ?> kg</p>
<p><b>Expected Market Date:</b> <?= $b['expected_market_date'] ?></p>
<p><b>Status:</b> <?= $b['status'] ?></p>
<p><b>Remarks:</b><br><?= nl2br(htmlspecialchars($b['remarks'])) ?></p>

<br>
<a href="edit_batch.php?id=<?= $b['batch_id'] ?>">✏️ Edit</a> |
<a href="list_batch.php">← Back</a>
</body>
</html>
