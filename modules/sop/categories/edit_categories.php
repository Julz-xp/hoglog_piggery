<?php
require_once __DIR__ . '/../../config/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM sop_master_categories WHERE category_id=?");
$stmt->execute([$id]);
$row = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['category_name'];
    $desc = $_POST['description'];
    $group = $_POST['category_group'];

    $update = $pdo->prepare("UPDATE sop_master_categories SET category_name=?, description=?, category_group=? WHERE category_id=?");
    $update->execute([$name, $desc, $group, $id]);
    header("Location: list_categories.php");
    exit;
}
?>

<h2>Edit Category</h2>
<form method="POST">
  <label>Name:</label><br>
  <input type="text" name="category_name" value="<?= htmlspecialchars($row['category_name']) ?>" required><br><br>

  <label>Description:</label><br>
  <textarea name="description"><?= htmlspecialchars($row['description']) ?></textarea><br><br>

  <label>Group:</label><br>
  <select name="category_group">
    <option value="Operations" <?= $row['category_group']=='Operations'?'selected':'' ?>>Operations</option>
    <option value="Health & Safety" <?= $row['category_group']=='Health & Safety'?'selected':'' ?>>Health & Safety</option>
    <option value="Administrative" <?= $row['category_group']=='Administrative'?'selected':'' ?>>Administrative</option>
    <option value="Support" <?= $row['category_group']=='Support'?'selected':'' ?>>Support</option>
  </select><br><br>

  <button type="submit">Update</button>
</form>

<a href="list_categories.php">Back</a>
