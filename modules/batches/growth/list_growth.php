<?php
require_once __DIR__ . '/../../../config/db.php';
$stmt = $pdo->query("
    SELECT g.*, b.batch_no
    FROM batch_growth_summary g
    JOIN batch_records b ON g.batch_id = b.batch_id
    ORDER BY g.created_at DESC
");
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Batch Growth Records</title></head>
<body>
<h2>Batch Growth Records</h2>
<a href="add_growth.php">➕ Add Growth Record</a><br><br>

<?php if (isset($_GET['success'])) echo "<p style='color:green;'>✅ Record added successfully.</p>"; ?>
<?php if (isset($_GET['updated'])) echo "<p style='color:blue;'>✏️ Record updated successfully.</p>"; ?>
<?php if (isset($_GET['deleted'])) echo "<p style='color:red;'>🗑️ Record deleted.</p>"; ?>

<table border="1" cellpadding="8">
<tr>
  <th>ID</th><th>Batch</th><th>Stage</th><th>Initial Wt</th><th>Final Wt</th><th>ADG</th><th>FCR</th><th>Actions</th>
</tr>
<?php if ($records): foreach ($records as $r): ?>
<tr>
  <td><?= $r['growth_id'] ?></td>
  <td><?= htmlspecialchars($r['batch_no']) ?></td>
  <td><?= $r['stage'] ?></td>
  <td><?= $r['avg_initial_weight'] ?></td>
  <td><?= $r['avg_final_weight'] ?></td>
  <td><?= $r['avg_adg'] ?></td>
  <td><?= $r['avg_fcr'] ?></td>
  <td>
    <a href="view_growth.php?id=<?= $r['growth_id'] ?>">View</a> |
    <a href="edit_growth.php?id=<?= $r['growth_id'] ?>">Edit</a> |
    <a href="delete_growth.php?id=<?= $r['growth_id'] ?>" onclick="return confirm('Delete this record?')">Delete</a>
  </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="8">No records found.</td></tr>
<?php endif; ?>
</table>
</body>
</html>
