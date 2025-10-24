<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT f.*, b.batch_no
    FROM batch_feed_consumption f
    JOIN batch_records b ON f.batch_id = b.batch_id
    WHERE f.feed_id=?
");
$stmt->execute([$id]);
$f = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$f) die("Feed record not found");
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>View Feed Record</title></head>
<body>
<h2>Feed Record for Batch <?= htmlspecialchars($f['batch_no']) ?></h2>

<p><b>Feed Stage:</b> <?= $f['feed_stage'] ?></p>
<p><b>Start Date:</b> <?= $f['start_date'] ?></p>
<p><b>End Date:</b> <?= $f['end_date'] ?></p>
<p><b>Expected Days:</b> <?= $f['expected_days'] ?></p>
<p><b>Expected Intake per Day:</b> <?= $f['expected_intake_per_day'] ?> kg</p>
<p><b>Expected Feed Total:</b> <?= $f['expected_feed_total'] ?> kg</p>
<p><b>Actual Feed Total:</b> <?= $f['actual_feed_total'] ?> kg</p>
<p><b>Price per kg:</b> ₱<?= $f['price_per_kg'] ?></p>
<p><b>Total Feed Cost:</b> ₱<?= number_format($f['actual_feed_total'] * $f['price_per_kg'], 2) ?></p>
<p><b>Remarks:</b><br><?= nl2br(htmlspecialchars($f['remarks'])) ?></p>

<br>
<a href="edit_feed.php?id=<?= $f['feed_id'] ?>">✏️ Edit</a> |
<a href="list_feed.php">← Back</a>
</body>
</html>
