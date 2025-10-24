<?php
require_once __DIR__ . '/../../config/db.php';

$stmt = $pdo->query("SELECT * FROM sop_master_categories ORDER BY created_at DESC");
?>
<h2>SOP Categories</h2>
<a href="add_categories.php">Add Category</a>
<hr>

<table border="1" cellpadding="5">
<tr>
  <th>ID</th>
  <th>Category Name</th>
  <th>Group</th>
  <th>Description</th>
  <th>Created</th>
  <th>Actions</th>
</tr>

<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
<tr>
  <td><?= $row['category_id'] ?></td>
  <td><?= htmlspecialchars($row['category_name']) ?></td>
  <td><?= htmlspecialchars($row['category_group']) ?></td>
  <td><?= htmlspecialchars($row['description']) ?></td>
  <td><?= $row['created_at'] ?></td>
  <td>
    <a href="view_categories.php?id=<?= $row['category_id'] ?>">View</a> |
    <a href="edit_categories.php?id=<?= $row['category_id'] ?>">Edit</a> |
    <a href="delete_categories.php?id=<?= $row['category_id'] ?>" onclick="return confirm('Delete this category?')">Delete</a>
  </td>
</tr>
<?php endwhile; ?>
</table>
