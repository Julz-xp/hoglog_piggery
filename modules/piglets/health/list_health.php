<?php
require_once __DIR__ . '/../../../config/db.php';
$stmt = $pdo->query("SELECT h.*, p.farrowing_date 
                     FROM piglet_health_record h
                     JOIN piglet_records p ON h.piglet_id = p.piglet_id
                     ORDER BY h.health_id DESC");
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><title>Piglet Health Records</title></head>
<body>
<h2>Piglet Health Records</h2>
<a href="add_health.php">➕ Add Health Record</a>
<br><br>

<?php if (isset($_GET['success'])) echo "<p style='color:green;'>✅ Record added successfully!</p>"; ?>
<?php if (isset($_GET['updated'])) echo "<p style='color:blue;'>✏️ Record updated successfully!</p>"; ?>
<?php if (isset($_GET['deleted'])) echo "<p style='color:red;'>🗑️ Record deleted successfully!</p>"; ?>

<table border="1" cellpadding="8">
<tr>
  <th>ID</th><th>Piglet ID</th><th>Type</th><th>Stage</th>
  <th>Date</th><th>Vet</th><th>Cost</th><th>Actions</th>
</tr>

<?php if ($records): foreach ($records as $r): ?>
<tr>
  <td><?= $r['health_id'] ?></td>
  <td><?= $r['piglet_id'] ?></td>
  <td><?= $r['record_type'] ?></td>
  <td><?= $r['stage'] ?></td>
  <td><?= $r['record_date'] ?></td>
  <td><?= htmlspecialchars($r['farm_vet']) ?></td>
  <td>₱<?= number_format($r['cost'], 2) ?></td>
  <td>
    <a href="view_health.php?id=<?= $r['health_id'] ?>">View</a> |
    <a href="edit_health.php?id=<?= $r['health_id'] ?>">Edit</a> |
    <a href="delete_health.php?id=<?= $r['health_id'] ?>" onclick="return confirm('Delete this record?')">Delete</a>
  </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="8">No records found.</td></tr>
<?php endif; ?>
</table>
</body>
</html>
