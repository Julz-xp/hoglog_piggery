<?php
require_once __DIR__ . '/../../../config/db.php';
$stmt = $pdo->query("SELECT f.*, p.farrowing_date 
                     FROM piglet_feed_consumption f
                     JOIN piglet_records p ON f.piglet_id = p.piglet_id
                     ORDER BY f.feed_id DESC");
$feeds = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><title>Piglet Feed Records</title></head>
<body>
<h2>Piglet Feed Records</h2>
<a href="add_feed.php">➕ Add Feed Record</a>
<br><br>

<?php if (isset($_GET['success'])) echo "<p style='color:green;'>✅ Record added successfully!</p>"; ?>
<?php if (isset($_GET['updated'])) echo "<p style='color:blue;'>✏️ Record updated successfully!</p>"; ?>
<?php if (isset($_GET['deleted'])) echo "<p style='color:red;'>🗑️ Record deleted successfully!</p>"; ?>

<table border="1" cellpadding="8">
<tr>
    <th>ID</th><th>Piglet ID</th><th>Feed Type</th><th>Days</th>
    <th>Total Feed (kg)</th><th>Total Cost (₱)</th><th>Actions</th>
</tr>

<?php if ($feeds): foreach ($feeds as $f): ?>
<tr>
    <td><?= $f['feed_id'] ?></td>
    <td><?= $f['piglet_id'] ?></td>
    <td><?= $f['feed_type'] ?></td>
    <td><?= $f['total_days'] ?></td>
    <td><?= $f['total_feed_consumed'] ?></td>
    <td><?= $f['total_feed_cost'] ?></td>
    <td>
        <a href="view_feed.php?id=<?= $f['feed_id'] ?>">View</a> |
        <a href="edit_feed.php?id=<?= $f['feed_id'] ?>">Edit</a> |
        <a href="delete_feed.php?id=<?= $f['feed_id'] ?>" onclick="return confirm('Delete this record?')">Delete</a>
    </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="7">No records found.</td></tr>
<?php endif; ?>
</table>
</body>
</html>
