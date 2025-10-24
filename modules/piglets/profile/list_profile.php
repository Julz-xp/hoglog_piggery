<?php
require_once __DIR__ . '/../../../config/db.php';

$stmt = $pdo->query("SELECT p.*, s.ear_tag_no 
                     FROM piglet_records p 
                     JOIN sows s ON p.sow_id = s.sow_id 
                     ORDER BY p.created_at DESC");
$piglets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><title>Piglet Records</title></head>
<body>
<h2>Piglet Records</h2>
<a href="add_piglet.php">➕ Add New Piglet Record</a>
<br><br>

<?php if (isset($_GET['success'])) echo "<p style='color:green;'>✅ Record added successfully!</p>"; ?>
<?php if (isset($_GET['updated'])) echo "<p style='color:blue;'>✏️ Record updated successfully!</p>"; ?>

<table border="1" cellpadding="8">
<tr>
    <th>ID</th><th>Sow Ear Tag</th><th>Farrowing</th><th>Total Born</th>
    <th>Weaned</th><th>Survival %</th><th>Actions</th>
</tr>

<?php if ($piglets): foreach ($piglets as $p): ?>
<tr>
    <td><?= $p['piglet_id'] ?></td>
    <td><?= htmlspecialchars($p['ear_tag_no']) ?></td>
    <td><?= $p['farrowing_date'] ?></td>
    <td><?= $p['total_born'] ?></td>
    <td><?= $p['total_weaned'] ?></td>
    <td><?= $p['survival_rate'] ?>%</td>
    <td>
        <a href="view_piglet.php?id=<?= $p['piglet_id'] ?>">View</a> |
        <a href="edit_piglet.php?id=<?= $p['piglet_id'] ?>">Edit</a> |
        <a href="delete_piglet.php?id=<?= $p['piglet_id'] ?>" onclick="return confirm('Delete this record?')">Delete</a>
    </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="7">No records found.</td></tr>
<?php endif; ?>
</table>
</body>
</html>
