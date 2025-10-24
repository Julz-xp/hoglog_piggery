<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT g.*, b.batch_no
    FROM batch_growth_summary g
    JOIN batch_records b ON g.batch_id = b.batch_id
    WHERE g.growth_id=?
");
$stmt->execute([$id]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$r) die("Record not found");
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>View Growth Record</title></head>
<body>
<h2>Growth Summary (Batch <?= htmlspecialchars($r['batch_no']) ?>)</h2>

<p><b>Stage:</b> <?= $r['stage'] ?></p>
<p><b>Average Initial Weight:</b> <?= $r['avg_initial_weight'] ?> kg</p>
<p><b>Average Final Weight:</b> <?= $r['avg_final_weight'] ?> kg</p>
<p><b>ADG:</b> <?= $r['avg_adg'] ?> kg/day</p>
<p><b>FCR:</b> <?= $r['avg_fcr'] ?></p>
<p><b>Average Feed Consumed:</b> <?= $r['avg_feed_consumed'] ?> kg</p>
<p><b>Mortality Count:</b> <?= $r['mortality_count'] ?></p>
<p><b>Remarks:</b><br><?= nl2br(htmlspecialchars($r['remarks'])) ?></p>

<br>
<a href="edit_growth.php?id=<?= $r['growth_id'] ?>">✏️ Edit</a> |
<a href="list_growth.php">← Back</a>
</body>
</html>
