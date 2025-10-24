<?php
require_once __DIR__ . '/../../../config/db.php';
$stmt = $pdo->query("
    SELECT m.*, b.batch_no
    FROM batch_mortality_record m
    JOIN batch_records b ON m.batch_id = b.batch_id
    ORDER BY m.date_of_mortality DESC
");
$morts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Batch Mortality Records</title></head>
<body>
<h2>Batch Mortality Records</h2>
<a href="add_mortality.php">➕ Add Mortality Record</a><br><br>

<?php if (isset($_GET['success'])) echo "<p style='color:green;'>✅ Record added successfully.</p>"; ?>
<?php if (isset($_GET['updated'])) echo "<p style='color:blue;'>✏️ Record updated successfully.</p>"; ?>
<?php if (isset($_GET['deleted'])) echo "<p style='color:red;'>🗑️ Record deleted.</p>"; ?>

<table border="1" cellpadding="8">
<tr>
  <th>ID</th><th>Batch</th><th>Pig ID</th><th>Sex</th><th>Date</th><th>Stage</th><th>Cause</th><th>Actions</th>
</tr>
<?php if ($morts): foreach ($morts as $m): ?>
<tr>
  <td><?= $m['mortality_id'] ?></td>
  <td><?= htmlspecialchars($m['batch_no']) ?></td>
  <td><?= htmlspecialchars($m['pig_id']) ?></td>
  <td><?= $m['sex'] ?></td>
  <td><?= $m['date_of_mortality'] ?></td>
  <td><?= $m['stage'] ?></td>
  <td><?= htmlspecialchars(substr($m['cause_of_death'], 0, 40)) ?>...</td>
  <td>
    <a href="view_mortality.php?id=<?= $m['mortality_id'] ?>">View</a> |
    <a href="edit_mortality.php?id=<?= $m['mortality_id'] ?>">Edit</a> |
    <a href="delete_mortality.php?id=<?= $m['mortality_id'] ?>" onclick="return confirm('Delete this record?')">Delete</a>
  </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="8">No mortality records found.</td></tr>
<?php endif; ?>
</table>
</body>
</html>
