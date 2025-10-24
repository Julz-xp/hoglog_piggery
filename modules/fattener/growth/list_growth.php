<?php
require_once __DIR__ . '/../../../config/db.php';

// ✅ Fetch all growth records joined with fattener info
$query = "SELECT g.*, f.ear_tag_no 
          FROM fattener_growth_record g
          JOIN fattener_records f ON g.fattener_id = f.fattener_id
          ORDER BY g.growth_id DESC";
$stmt = $pdo->query($query);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Fattener Growth Records</title>
</head>
<body>
<h2>Fattener Growth Records</h2>
<a href="add_growth.php">➕ Add Growth Record</a>
<br><br>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">✅ Record added successfully!</p>
<?php elseif (isset($_GET['updated'])): ?>
    <p style="color: blue;">✏️ Record updated successfully!</p>
<?php elseif (isset($_GET['deleted'])): ?>
    <p style="color: red;">🗑️ Record deleted successfully!</p>
<?php endif; ?>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Ear Tag</th>
        <th>Stage</th>
        <th>Initial Wt</th>
        <th>Final Wt</th>
        <th>Days</th>
        <th>Feed (kg)</th>
        <th>ADG</th>
        <th>FCR</th>
        <th>Actions</th>
    </tr>

    <?php if ($records): ?>
        <?php foreach ($records as $r): ?>
            <tr>
                <td><?= $r['growth_id'] ?></td>
                <td><?= htmlspecialchars($r['ear_tag_no']) ?></td>
                <td><?= htmlspecialchars($r['stage']) ?></td>
                <td><?= htmlspecialchars($r['initial_weight']) ?></td>
                <td><?= htmlspecialchars($r['final_weight']) ?></td>
                <td><?= htmlspecialchars($r['days_in_stage']) ?></td>
                <td><?= htmlspecialchars($r['feed_consumed']) ?></td>
                <td><?= htmlspecialchars($r['adg']) ?></td>
                <td><?= htmlspecialchars($r['fcr']) ?></td>
                <td>
                    <a href="view_growth.php?id=<?= $r['growth_id'] ?>">View</a> |
                    <a href="edit_growth.php?id=<?= $r['growth_id'] ?>">Edit</a> |
                    <a href="delete_growth.php?id=<?= $r['growth_id'] ?>" onclick="return confirm('Delete this record?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="10" align="center">No growth records found.</td></tr>
    <?php endif; ?>
</table>

<br>
<a href="../profile/list_profile.php">⬅️ Back to Profiles</a>
</body>
</html>
