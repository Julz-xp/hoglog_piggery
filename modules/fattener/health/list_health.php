<?php
require_once __DIR__ . '/../../../config/db.php';

$query = "SELECT h.*, f.ear_tag_no 
          FROM fattener_health_record h
          JOIN fattener_records f ON h.fattener_id = f.fattener_id
          ORDER BY h.health_id DESC";
$stmt = $pdo->query($query);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Health Records</title></head>
<body>
<h2>🐖 Fattener Health Records</h2>
<a href="add_health.php">➕ Add Health Record</a>
<br><br>

<?php if (isset($_GET['success'])) echo "<p style='color:green;'>Record added successfully!</p>"; ?>
<?php if (isset($_GET['updated'])) echo "<p style='color:blue;'>Record updated successfully!</p>"; ?>
<?php if (isset($_GET['deleted'])) echo "<p style='color:red;'>Record deleted successfully!</p>"; ?>

<table border="1" cellpadding="8" cellspacing="0">
<tr>
    <th>ID</th>
    <th>Ear Tag</th>
    <th>Type</th>
    <th>Date</th>
    <th>Stage</th>
    <th>Vet</th>
    <th>Cost (₱)</th>
    <th>Actions</th>
</tr>

<?php if ($records): foreach ($records as $r): ?>
<tr>
    <td><?= $r['health_id'] ?></td>
    <td><?= htmlspecialchars($r['ear_tag_no']) ?></td>
    <td><?= htmlspecialchars($r['record_type']) ?></td>
    <td><?= htmlspecialchars($r['record_date']) ?></td>
    <td><?= htmlspecialchars($r['stage']) ?></td>
    <td><?= htmlspecialchars($r['farm_vet']) ?></td>
    <td><?= htmlspecialchars($r['cost']) ?></td>
    <td>
        <a href="view_health.php?id=<?= $r['health_id'] ?>">View</a> |
        <a href="edit_health.php?id=<?= $r['health_id'] ?>">Edit</a> |
        <a href="delete_health.php?id=<?= $r['health_id'] ?>" onclick="return confirm('Delete this record?')">Delete</a>
    </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="8" align="center">No health records found.</td></tr>
<?php endif; ?>
</table>

</body>
</html>
