<?php
require_once __DIR__ . '/../../../config/db.php';

// ✅ Fetch all fattener records
$stmt = $pdo->query("SELECT * FROM fattener_records ORDER BY fattener_id DESC");
$fatteners = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fattener Profiles</title>
</head>
<body>
    <h2>🐖 Fattener Profiles</h2>

    <a href="add_profile.php">➕ Add New Fattener</a>
    <br><br>

    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">Record added successfully!</p>
    <?php endif; ?>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Ear Tag No</th>
            <th>Batch No</th>
            <th>Sex</th>
            <th>Breed Line</th>
            <th>Birth Date</th>
            <th>Weaning Date</th>
            <th>Weaning Weight</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php if (count($fatteners) > 0): ?>
            <?php foreach ($fatteners as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['fattener_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['ear_tag_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['batch_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['sex']); ?></td>
                    <td><?php echo htmlspecialchars($row['breed_line']); ?></td>
                    <td><?php echo htmlspecialchars($row['birth_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['weaning_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['weaning_weight']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <a href="view_profile.php?id=<?php echo $row['fattener_id']; ?>">👁 View</a> |
                        <a href="edit_profile.php?id=<?php echo $row['fattener_id']; ?>">✏️ Edit</a> |
                        <a href="delete_profile.php?id=<?php echo $row['fattener_id']; ?>" onclick="return confirm('Are you sure?');">🗑 Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="10" align="center">No records found.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
