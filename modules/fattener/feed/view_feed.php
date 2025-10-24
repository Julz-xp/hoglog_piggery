<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT fc.*, fr.ear_tag_no 
                       FROM fattener_feed_consumption fc
                       JOIN fattener_records fr ON fc.fattener_id = fr.fattener_id
                       WHERE fc.feed_id = ?");
$stmt->execute([$id]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$r) die("Record not found.");
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>View Feed Record</title></head>
<body>
<h2>View Feed Record</h2>

<p><b>Fattener:</b> <?= htmlspecialchars($r['ear_tag_no']) ?></p>
<p><b>Feed Type:</b> <?= htmlspecialchars($r['feed_type']) ?></p>
<p><b>Start Date:</b> <?= htmlspecialchars($r['start_date']) ?></p>
<p><b>End Date:</b> <?= htmlspecialchars($r['end_date']) ?></p>
<p><b>Total Days:</b> <?= htmlspecialchars($r['total_days']) ?></p>
<p><b>Daily Intake:</b> <?= htmlspecialchars($r['daily_intake']) ?> kg</p>
<p><b>Total Feed Consumed:</b> <?= htmlspecialchars($r['total_feed_consumed']) ?> kg</p>
<p><b>Price per kg:</b> ₱<?= htmlspecialchars($r['price_per_kg']) ?></p>
<p><b>Total Feed Cost:</b> ₱<?= htmlspecialchars($r['total_feed_cost']) ?></p>
<p><b>Remarks:</b> <?= nl2br(htmlspecialchars($r['remarks'])) ?></p>

<br>
<a href="edit_feed.php?id=<?= $r['feed_id'] ?>">✏️ Edit</a> |
<a href="list_feed.php">⬅️ Back to List</a>
</body>
</html>
