<?php
require_once __DIR__ . '/../../../config/db.php';

$query = "SELECT fc.*, fr.ear_tag_no 
          FROM fattener_feed_consumption fc
          JOIN fattener_records fr ON fc.fattener_id = fr.fattener_id
          ORDER BY fc.feed_id DESC";
$stmt = $pdo->query($query);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Feed Records</title></head>
<body>
<h2>🐖 Feed Consumption Records</h2>
<a href="add_feed.php">➕ Add Feed Record</a>
<br><br>

<?php if (isset($_GET['success'])) echo "<p style='color:green;'>Record added successfully!</p>"; ?>
<?php if (isset($_GET['updated'])) echo "<p style='color:blue;'>Record updated successfully!</p>"; ?>
<?php if (isset($_GET['deleted'])) echo "<p style='color:red;'>Record deleted successfully!</p>"; ?>

<table border="1" cellpadding="8" cellspacing="0">
<tr>
    <th>ID</th>
    <th>Ear Tag</th>
    <th>Feed Type</th>
    <th>Days</th>
    <th>Daily Intake</th>
    <th>Total Feed (kg)</th>
    <th>Total Cost (₱)</th>
    <th>Actions</th>
</tr>

<?php if ($records): foreach ($records as $r): ?>
<tr>
    <td><?= $r['feed_id'] ?></td>
    <td><?= htmlspecialchars($r['ear_tag_no']) ?></td>
    <td><?= htmlspecialchars($r['feed_type']) ?></td>
    <td><?= htmlspecialchars($r['total_days']) ?></td>
    <td><?= htmlspecialchars($r['daily_intake']) ?></td>
    <td><?= htmlspecialchars($r['total_feed_consumed']) ?></td>
    <td><?= htmlspecialchars($r['total_feed_cost']) ?></td>
    <td>
        <a href="view_feed.php?id=<?= $r['feed_id'] ?>">View</a> |
        <a href="edit_feed.php?id=<?= $r['feed_id'] ?>">Edit</a> |
        <a href="delete_feed.php?id=<?= $r['feed_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
    </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="8" align="center">No feed records found.</td></tr>
<?php endif; ?>
</table>

</body>
</html>
