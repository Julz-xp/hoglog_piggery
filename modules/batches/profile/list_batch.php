<?php
require_once __DIR__ . '/../../../config/db.php';
$stmt = $pdo->query("SELECT * FROM batch_records ORDER BY created_at DESC");
$batches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Batch Records</title></head>
<body>
<h2>Batch Records</h2>
<a href="add_batch.php">➕ Add Batch</a><br><br>

<?php if (isset($_GET['success'])) echo "<p style='color:green;'>✅ Batch added successfully.</p>"; ?>
<?php if (isset($_GET['updated'])) echo "<p style='color:blue;'>✏️ Batch updated successfully.</p>"; ?>
<?php if (isset($_GET['deleted'])) echo "<p style='color:red;'>🗑️ Batch deleted.</p>"; ?>

<table border="1" cellpadding="8">
<tr>
  <th>ID</th><th>Batch No</th><th>Total Pigs</th><th>Breed</th><th>Birth Date</th><th>Status</th><th>Actions</th>
</tr>
<?php if ($batches): foreach ($batches as $b): ?>
<tr>
  <td><?= $b['batch_id'] ?></td>
  <td><?= htmlspecialchars($b['batch_no']) ?></td>
  <td><?= $b['num_pigs_total'] ?></td>
  <td><?= htmlspecialchars($b['breed']) ?></td>
  <td><?= $b['birth_date'] ?></td>
  <td><?= $b['status'] ?></td>
  <td>
    <a href="view_batch.php?id=<?= $b['batch_id'] ?>">View</a> |
    <a href="edit_batch.php?id=<?= $b['batch_id'] ?>">Edit</a> |
    <a href="delete_batch.php?id=<?= $b['batch_id'] ?>" onclick="return confirm('Delete this batch?')">Delete</a>
  </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="7">No batch records found.</td></tr>
<?php endif; ?>
</table>
</body>
</html>
