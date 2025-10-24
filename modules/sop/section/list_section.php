<?php
require_once __DIR__ . '/../../config/db.php';

// Fetch sections with category name
$sql = "SELECT s.*, c.category_name 
        FROM sop_category_sections s
        LEFT JOIN sop_master_categories c ON s.category_id = c.category_id
        ORDER BY s.created_at DESC";
$stmt = $pdo->query($sql);
?>

<h2>SOP Sections</h2>
<a href="add_section.php">Add Section</a>
<hr>

<table border="1" cellpadding="5">
<tr>
  <th>ID</th>
  <th>Section Name</th>
  <th>Category</th>
  <th>Responsible Person</th>
  <th>Frequency</th>
  <th>Created</th>
  <th>Actions</th>
</tr>

<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
<tr>
  <td><?= $row['section_id'] ?></td>
  <td><?= htmlspecialchars($row['section_name']) ?></td>
  <td><?= htmlspecialchars($row['category_name']) ?></td>
  <td><?= htmlspecialchars($row['responsible_person']) ?></td>
  <td><?= htmlspecialchars($row['frequency']) ?></td>
  <td><?= $row['created_at'] ?></td>
  <td>
    <a href="view_section.php?id=<?= $row['section_id'] ?>">View</a> |
    <a href="edit_section.php?id=<?= $row['section_id'] ?>">Edit</a> |
    <a href="delete_section.php?id=<?= $row['section_id'] ?>" onclick="return confirm('Delete this section?')">Delete</a>
  </td>
</tr>
<?php endwhile; ?>
</table>
