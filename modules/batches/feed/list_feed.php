<?php
require_once __DIR__ . '/../../../config/db.php';
$stmt = $pdo->query("
    SELECT f.*, b.batch_no
    FROM batch_feed_consumption f
    JOIN batch_records b ON f.batch_id = b.batch_id
    ORDER BY f.created_at DESC
");
$feeds = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Batch Feed Records</title></head>
<body>
<h2>Batch Feed Consumption</h2>
<a href="add_feed.php">➕ Add Feed Record</a><br><br>

<?php if (isset($_GET['success'])) echo "<p style='color:green;'>✅ Feed record added successfully.</p>"; ?>
<?php if (isset($_GET['updated'])) echo "<p style='color:blue;'>✏️ Record updated successfully.</p>"; ?>
<?php if (isset($_GET['deleted'])) echo "<p style='color:red;'>🗑️ Feed record deleted.</p>"; ?>

<table border="1" cellpadding="8">
<tr>
  <th>ID</th><th>Batch</th><th>Stage</th><th>Start</th><th>End</th><th>Feed (kg)</th><th>₱/kg</th><th>Actions</th>
</tr>
<?php if ($feeds): foreach ($feeds as $f): ?>
<tr>
  <td><?= $f['feed_id'] ?></td>
  <td><?= htmlspecialchars($f['batch_no']) ?></td>
  <td><?= $f['feed_stage'] ?></td>
  <td><?= $f['start_date'] ?></td>
  <td><?= $f['end_date'] ?></td>
  <td><?= $f['actual_feed_total'] ?></td>
  <td><?= $f['price_per_kg'] ?></td>
  <td>
    <a href="view_feed.php?id=<?= $f['feed_id'] ?>">View</a> |
    <a href="edit_feed.php?id=<?= $f['feed_id'] ?>">Edit</a> |
    <a href="delete_feed.php?id=<?= $f['feed_id'] ?>" onclick="return confirm('Delete this record?')">Delete</a>
  </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="8">No feed records found.</td></tr>
<?php endif; ?>
</table>
</body>
</html>
