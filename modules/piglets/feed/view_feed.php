<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request.");

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT f.*, p.farrowing_date 
                       FROM piglet_feed_consumption f 
                       JOIN piglet_records p ON f.piglet_id = p.piglet_id 
                       WHERE f.feed_id = ?");
$stmt->execute([$id]);
$f = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$f) die("Record not found.");
?>

<!DOCTYPE html>
<html>
<head><title>View Piglet Feed</title></head>
<body>
<h2>Feed Record Details</h2>
<p><b>Piglet ID:</b> <?= $f['piglet_id'] ?></p>
<p><b>Feed Type:</b> <?= $f['feed_type'] ?></p>
<p><b>Start Date:</b> <?= $f['start_date'] ?></p>
<p><b>End Date:</b> <?= $f['end_date'] ?></p>
<p><b>Daily Intake:</b> <?= $f['daily_intake'] ?> kg</p>
<p><b>Total Days:</b> <?= $f['total_days'] ?></p>
<p><b>Total Feed Consumed:</b> <?= $f['total_feed_consumed'] ?> kg</p>
<p><b>Price per kg:</b> ₱<?= $f['price_per_kg'] ?></p>
<p><b>Total Feed Cost:</b> ₱<?= $f['total_feed_cost'] ?></p>
<p><b>Remarks:</b> <?= nl2br(htmlspecialchars($f['remarks'])) ?></p>
<br>
<a href="edit_feed.php?id=<?= $f['feed_id'] ?>">Edit</a> |
<a href="list_feed.php">Back to List</a>
</body>
</html>
