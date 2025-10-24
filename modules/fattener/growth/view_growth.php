<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die("Invalid request");

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT g.*, f.ear_tag_no 
                       FROM fattener_growth_record g
                       JOIN fattener_records f ON g.fattener_id = f.fattener_id
                       WHERE g.growth_id = ?");
$stmt->execute([$id]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$r) die("Record not found.");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>View Growth Record</title>
</head>
<body>
<h2>View Fattener Growth Record</h2>

<p><b>Fattener Ear Tag:</b> <?= htmlspecialchars($r['ear_tag_no']) ?></p>
<p><b>Stage:</b> <?= htmlspecialchars($r['stage']) ?></p>
<p><b>Initial Weight:</b> <?= htmlspecialchars($r['initial_weight']) ?> kg</p>
<p><b>Final Weight:</b> <?= htmlspecialchars($r['final_weight']) ?> kg</p>
<p><b>Days in Stage:</b> <?= htmlspecialchars($r['days_in_stage']) ?></p>
<p><b>Feed Consumed:</b> <?= htmlspecialchars($r['feed_consumed']) ?> kg</p>
<p><b>ADG:</b> <?= htmlspecialchars($r['adg']) ?> kg/day</p>
<p><b>FCR:</b> <?= htmlspecialchars($r['fcr']) ?></p>
<p><b>Remarks:</b> <?= nl2br(htmlspecialchars($r['remarks'])) ?></p>

<br>
<a href="edit_growth.php?id=<?= $r['growth_id'] ?>">✏️ Edit</a> |
<a href="list_growth.php">⬅️ Back to List</a>
</body>
</html>

